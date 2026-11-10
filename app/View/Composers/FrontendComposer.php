<?php

namespace App\View\Composers;

use App\Models\Brand;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\NewsSetting;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductSetting;
use App\Models\Slider;
use App\Models\System;
use App\Services\CacheService;
use App\Services\CartService;
use App\Services\SiteSettingService;
use Illuminate\View\View;

class FrontendComposer
{
    protected $cartService;

    protected $siteSettingService;

    /**
     * Request-level memoized view data to prevent re-running queries across partials.
     */
    protected static ?array $memoizedData = null;

    public function __construct(CartService $cartService, SiteSettingService $siteSettingService)
    {
        $this->cartService = $cartService;
        $this->siteSettingService = $siteSettingService;
    }

    /**
     * Flush in-memory memoized data (used when models update or in test suites).
     */
    public static function flushMemoryCache(): void
    {
        static::$memoizedData = null;
        ProductSetting::flushMemoryCache();
        NewsSetting::flushMemoryCache();
    }

    public function compose(View $view)
    {
        if (static::$memoizedData === null) {
            $cacheTtl = 120; // 2 hours

            $systemData = CacheService::remember('system', 'frontend_web_data', $cacheTtl, function () {
                return System::first() ?? new System(['name_vn' => 'Base', 'name_en' => 'Base']);
            });
            if (! $systemData) {
                $systemData = new System(['name_vn' => 'Base', 'name_en' => 'Base']);
            }

            $productPreviewCols = [
                'id_product', 'uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en',
                'image_vn', 'image_en', 'price', 'price_old', 'category_id',
                'hot', 'status', 'stt', 'intro_vn', 'intro_en', 'created_at',
            ];

            $currencyUnit = ProductSetting::getCurrencyUnit();

            static::$memoizedData = [
                // 1. System settings (Tag: system)
                'web' => $systemData,
                'website' => $systemData,

                // 2. Navigation menu (Tag: menu)
                'menu' => CacheService::remember('menu', 'frontend_menu_data', $cacheTtl, function () {
                    return Menu::with(['children' => function ($query) {
                        $query->where('status', 1)->with(['children' => function ($subQuery) {
                            $subQuery->where('status', 1)->orderBy('stt', 'asc');
                        }])->orderBy('stt', 'asc');
                    }])->where('parent_id', 0)->where('status', 1)->orderBy('stt', 'asc')->get();
                }),

                // 3. Sliders / Banners (Tag: sliders)
                'ads' => CacheService::remember('sliders', 'frontend_sliders_data', $cacheTtl, function () {
                    return Slider::where('status', 1)->orderBy('stt', 'asc')->get();
                }),

                // 4. Product categories & Brands (Tag: categories, brands)
                'cate_product' => CacheService::remember('categories', 'frontend_cate_product_data', $cacheTtl, function () {
                    return CateProduct::select('id_cate_product', 'uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'parent_id', 'status', 'home', 'stt')
                        ->where('status', 1)
                        ->where('parent_id', 0)
                        ->orderBy('stt', 'asc')
                        ->get();
                }),
                'brands' => CacheService::remember('brands', 'frontend_brands_data', $cacheTtl, function () {
                    return Brand::where('status', 1)->orderBy('stt', 'asc')->get();
                }),
                'category_product_footer' => CacheService::remember('categories', 'frontend_cate_product_footer_data', $cacheTtl, function () {
                    return CateProduct::select('id_cate_product', 'uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'parent_id', 'status', 'stt')
                        ->where('status', 1)
                        ->whereIn('parent_id', [0, 1])
                        ->orderBy('stt', 'asc')
                        ->get();
                }),

                // 5. News categories (Tag: categories)
                'category_news_footer' => CacheService::remember('categories', 'frontend_cate_news_footer_data', $cacheTtl, function () {
                    return CateNew::select('id_cate_new', 'uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'parent_id', 'status', 'stt')
                        ->where('status', 1)
                        ->where('parent_id', 0)
                        ->orderBy('stt', 'asc')
                        ->get();
                }),

                // 6. Footer static pages - pruned content_vn/content_en (Rule 19)
                'footer_pages' => CacheService::remember('pages', 'frontend_footer_pages_data', $cacheTtl, function () {
                    return Page::select('id_page', 'uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'footer', 'status', 'stt')
                        ->where('status', 1)
                        ->where('footer', 1)
                        ->orderBy('stt', 'asc')
                        ->get();
                }),

                // 7. Featured hot products - pruned content_vn/content_en (Rule 19)
                'products_hot' => CacheService::remember('products', 'frontend_products_hot_data', $cacheTtl, function () use ($productPreviewCols) {
                    return Product::select($productPreviewCols)
                        ->where('status', 1)
                        ->where('hot', 1)
                        ->orderBy('stt', 'asc')
                        ->limit(10)
                        ->get();
                }),

                // 8. Product display settings (Single-query map)
                'currency_unit' => $currencyUnit,
                'product_settings' => [
                    'currency_unit' => $currencyUnit,
                    'font_size' => ProductSetting::get('products_font_size', '16px'),
                    'show_intro' => ProductSetting::get('products_show_intro', true),
                    'click_image_detail' => ProductSetting::get('products_click_image_detail', true),
                    'title_color' => ProductSetting::get('products_title_color', '#064e3b'),
                    'category_color' => ProductSetting::get('products_category_color', '#b45309'),
                    'banner_category' => ProductSetting::get('products_banner_category', ''),
                    'banner_detail' => ProductSetting::get('products_banner_detail', ''),
                ],

                // 9. News display settings (Single-query map)
                'news_settings' => [
                    'font_size' => NewsSetting::get('news_font_size', '16px'),
                    'show_intro' => NewsSetting::get('news_show_intro', true),
                    'click_image_detail' => NewsSetting::get('news_click_image_detail', true),
                    'title_color' => NewsSetting::get('news_title_color', '#064e3b'),
                    'category_color' => NewsSetting::get('news_category_color', '#b45309'),
                    'banner_category' => NewsSetting::get('news_banner_category', ''),
                    'banner_detail' => NewsSetting::get('news_banner_detail', ''),
                ],
            ];
        }

        $data = static::$memoizedData;
        $data['cart_count'] = $this->cartService->getTotalItems();

        $view->with($data);
    }
}
