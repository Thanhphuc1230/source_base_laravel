<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\CateProject;
use App\Models\CateService;
use App\Models\Feature;
use App\Models\Feedback;
use App\Models\Gallery;
use App\Models\Menu;
use App\Models\News;
use App\Models\NewsSetting;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductSetting;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\System;
use App\Services\CartService;
use App\Services\SlugResolutionService;
use App\View\Composers\FrontendComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('frontend.*', FrontendComposer::class);

        View::composer('admin.*', function ($view) {
            static $adminSharedData = null;
            if ($adminSharedData === null && class_exists(\App\Models\System::class)) {
                $system = Cache::remember('admin_system_config', now()->addHours(24), function () {
                    return \App\Models\System::first();
                });
                $activeLocales = $system->active_languages ?? ['vi', 'en'];
                $adminLanguages = [];
                if (in_array('vi', $activeLocales)) {
                    $adminLanguages[] = 'vn';
                }
                if (in_array('en', $activeLocales)) {
                    $adminLanguages[] = 'en';
                }

                $adminSharedData = [
                    'systemConfig' => $system,
                    'adminLanguages' => $adminLanguages,
                ];
            }
            if ($adminSharedData !== null) {
                $view->with($adminSharedData);
            }
        });

        // Clear frontend cache dynamically when data changes
        $clearFrontendCache = function () {
            if (class_exists(\App\Services\CacheService::class)) {
                \App\Services\CacheService::forgetTags(['brands', 'frontend', 'system', 'menu', 'sliders', 'categories', 'pages', 'products', 'news', 'services', 'projects']);
            }
            FrontendComposer::flushMemoryCache();
            SlugResolutionService::flushMemoryCache();
            ProductSetting::flushMemoryCache();
            NewsSetting::flushMemoryCache();
            Cache::forget('frontend_global_data');
            Cache::forget('admin_system_config');
            Cache::forget('dynamic_sitemap_xml');
        };

        $watchedModels = [
            System::class,
            Menu::class,
            Slider::class,
            CateProduct::class,
            CateNew::class,
            CateService::class,
            CateProject::class,
            Page::class,
            Product::class,
            News::class,
            Service::class,
            Project::class,
            Brand::class,
            Feedback::class,
            Feature::class,
            Gallery::class,
            ProductSetting::class,
            NewsSetting::class,
        ];

        foreach ($watchedModels as $model) {
            if (class_exists($model)) {
                $model::saved($clearFrontendCache);
                $model::deleted($clearFrontendCache);
            }
        }
    }
}
