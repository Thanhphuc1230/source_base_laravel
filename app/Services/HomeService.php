<?php

namespace App\Services;

use App\Models\About;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Feature;
use App\Models\News;
use App\Models\Product;
use App\Models\Project;
use App\Models\Slider;

class HomeService
{
    /**
     * Get aggregated home page data with column pruning (Rule 19 compliance) and caching.
     *
     * @return array
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
                ->limit(3)
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

            return $data;
        });
    }
}
