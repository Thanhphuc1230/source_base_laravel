<?php

namespace App\Services;

use App\Models\About;
use App\Models\Brand;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Feature;
use App\Models\HomeSection;
use App\Models\News;
use App\Models\Product;
use App\Models\Project;
use App\Models\Slider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class HomeService
{
    /**
     * Get aggregated home page data with column pruning (Rule 19 compliance) and caching.
     */
    public function getHomeData(): array
    {
        return CacheService::remember('frontend', 'home_page_aggregated_data', CacheService::getTtl('medium'), function () {
            $data = [];

            $data['sliders'] = Slider::where('status', 1)
                ->orderBy('stt', 'asc')
                ->get();

            $data['category_news'] = CateNew::select('id_cate_new', 'uuid', 'name', 'slug', 'image_vn', 'image_en', 'status', 'home', 'stt')
                ->where('status', 1)
                ->where('home', 1)
                ->orderBy('stt', 'asc')
                ->get();

            $previewColumns = [
                'id_product', 'uuid', 'name', 'slug',
                'image_vn', 'image_en', 'price', 'price_old', 'category_id',
                'hot', 'status', 'stt', 'intro', 'created_at',
            ];

            $data['category_product'] = CateProduct::select('id_cate_product', 'uuid', 'name', 'slug', 'image_vn', 'image_en', 'parent_id', 'status', 'home', 'stt')
                ->with(['products' => function ($q) use ($previewColumns) {
                    $q->select($previewColumns)->where('status', 1)->orderBy('stt', 'asc')->limit(8);
                }])
                ->where('status', 1)
                ->where('home', 1)
                ->orderBy('stt', 'asc')
                ->get();

            $data['hot_products'] = Product::select($previewColumns)
                ->where('status', 1)
                ->where('hot', 1)
                ->orderBy('stt', 'asc')
                ->limit(8)
                ->get();

            $data['latest_news'] = News::with('cate:id_cate_new,name,slug')
                ->select('id_new', 'uuid', 'name', 'slug', 'image_vn', 'image_en', 'intro', 'created_at', 'category_id', 'status', 'stt')
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            $data['featured_projects'] = Project::with('cate:id_cate_project,name,slug')
                ->select('id_project', 'uuid', 'name', 'slug', 'image_vn', 'image_en', 'intro', 'category_id', 'status', 'home', 'stt', 'created_at')
                ->where('status', 1)
                ->where('home', 1)
                ->orderBy('stt', 'asc')
                ->limit(6)
                ->get();

            $data['about_section'] = About::where('status', 1)
                ->orderBy('stt', 'asc')
                ->orderBy('created_at', 'desc')
                ->first();

            $data['features'] = Feature::where('status', 1)
                ->orderBy('stt', 'asc')
                ->get();

            $data['brands'] = Brand::where('status', 1)
                ->orderBy('stt', 'asc')
                ->get();

            $data['home_sections'] = $this->loadDynamicSections($previewColumns, $data);

            return $data;
        });
    }

    /**
     * Load active HomeSection blocks ordered by stt with batched relation loading (zero N+1) and test fallback.
     */
    protected function loadDynamicSections(array $previewColumns, array $baseData): Collection
    {
        $sections = collect();

        if (Schema::hasTable('tp_home_sections')) {
            $sections = HomeSection::with([
                'categoryProduct:id_cate_product,uuid,name,slug',
                'categoryNews:id_cate_new,uuid,name,slug',
            ])
                ->where('status', true)
                ->orderBy('stt', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        if ($sections->isEmpty()) {
            $sections = collect([
                new HomeSection(['type' => 'hero', 'title_vn' => 'Banner Chính', 'title_en' => 'Hero Slider', 'limit' => 5, 'stt' => 1, 'status' => true]),
                new HomeSection(['type' => 'features', 'title_vn' => 'Cam Kết & Dịch Vụ', 'title_en' => 'Features', 'limit' => 4, 'stt' => 2, 'status' => true]),
                new HomeSection(['type' => 'icon_categories', 'title_vn' => 'Danh Mục Nổi Bật', 'title_en' => 'Categories', 'limit' => 8, 'stt' => 3, 'status' => true]),
                new HomeSection(['type' => 'flash_sale', 'title_vn' => 'Sản Phẩm Nổi Bật', 'title_en' => 'Hot Sale', 'limit' => 8, 'stt' => 4, 'status' => true]),
                new HomeSection(['type' => 'category_products', 'title_vn' => 'Sản Phẩm Theo Danh Mục', 'title_en' => 'Category Products', 'limit' => 8, 'stt' => 5, 'status' => true]),
                new HomeSection(['type' => 'mid_banner', 'title_vn' => 'Banner Quảng Cáo', 'title_en' => 'Mid Banner', 'limit' => 2, 'stt' => 6, 'status' => true]),
                new HomeSection(['type' => 'brands', 'title_vn' => 'Thương Hiệu Đối Tác', 'title_en' => 'Brands', 'limit' => 12, 'stt' => 7, 'status' => true]),
                new HomeSection(['type' => 'news', 'title_vn' => 'Tin Tức & Cảm Hứng', 'title_en' => 'News', 'limit' => 3, 'stt' => 8, 'status' => true]),
            ]);
        }

        // Batch-load products for referenced category_products sections (avoids N+1)
        $prodCateIds = $sections->where('type', 'category_products')
            ->pluck('reference_id')
            ->filter()
            ->unique()
            ->values();

        $productsByCate = $prodCateIds->isNotEmpty()
            ? Product::select($previewColumns)
                ->where('status', 1)
                ->whereIn('category_id', $prodCateIds)
                ->orderBy('stt', 'asc')
                ->get()
                ->groupBy('category_id')
            : collect();

        // Batch-load news for referenced news sections (avoids N+1)
        $newsCateIds = $sections->where('type', 'news')
            ->pluck('reference_id')
            ->filter()
            ->unique()
            ->values();

        $newsByCate = $newsCateIds->isNotEmpty()
            ? News::with('cate:id_cate_new,name,slug')
                ->select('id_new', 'uuid', 'name', 'slug', 'image_vn', 'image_en', 'intro', 'created_at', 'category_id', 'status', 'stt')
                ->where('status', 1)
                ->whereIn('category_id', $newsCateIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('category_id')
            : collect();

        return $sections->map(function (HomeSection $section) use ($productsByCate, $newsByCate, $baseData) {
            if ($section->type === 'category_products' && $section->reference_id) {
                $section->setRelation('resolved_items', ($productsByCate->get($section->reference_id) ?? collect())->take($section->limit));
            } elseif ($section->type === 'news') {
                $items = $section->reference_id
                    ? ($newsByCate->get($section->reference_id) ?? collect())
                    : $baseData['latest_news'];
                $section->setRelation('resolved_items', $items->take($section->limit));
            }

            return $section;
        });
    }
}
