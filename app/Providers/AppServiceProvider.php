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
use App\Models\HomeSection;
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
        if ($this->app->environment('production') || config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (request()->is('admin*')) {
            Paginator::useBootstrapFive();
        } else {
            Paginator::defaultView('frontend.partials.pagination');
            Paginator::defaultSimpleView('frontend.partials.pagination');
        }

        View::composer('frontend.*', FrontendComposer::class);

        View::composer('admin.*', function ($view) {
            static $adminSharedData = null;
            if ($adminSharedData === null && class_exists(\App\Models\System::class)) {
                $system = Cache::remember('admin_system_config', now()->addHours(24), function () {
                    return \App\Models\System::first();
                });
                $activeLocales = $system->active_languages ?? ['vi', 'en', 'de', 'ja', 'ko', 'zh'];
                $supported = config('languages.supported', []);

                $adminLanguages = [];
                foreach (array_keys($supported) as $code) {
                    if (in_array($code, $activeLocales, true)) {
                        $adminLanguages[] = $code;
                        // Support legacy 'vn' alias for 'vi' in older admin templates
                        if ($code === 'vi' && !in_array('vn', $adminLanguages, true)) {
                            $adminLanguages[] = 'vn';
                        }
                    }
                }

                $adminSharedData = [
                    'systemConfig' => $system,
                    'adminLanguages' => $adminLanguages,
                    'supportedLanguages' => $supported,
                ];
            }
            if ($adminSharedData !== null) {
                $view->with($adminSharedData);
            }
        });

        // Clear frontend cache selectively per model (avoids flushing unrelated cache tags)
        $modelTagMap = [
            System::class => ['system', 'frontend'],
            Menu::class => ['menu', 'frontend'],
            Slider::class => ['sliders', 'frontend'],
            CateProduct::class => ['categories', 'products', 'frontend'],
            CateNew::class => ['categories', 'news', 'frontend'],
            CateService::class => ['categories', 'services', 'frontend'],
            CateProject::class => ['categories', 'projects', 'frontend'],
            Page::class => ['pages', 'frontend'],
            Product::class => ['products', 'frontend'],
            News::class => ['news', 'frontend'],
            Service::class => ['services', 'frontend'],
            Project::class => ['projects', 'frontend'],
            Brand::class => ['brands', 'frontend'],
            Feedback::class => ['feedback'],
            Feature::class => ['features', 'frontend'],
            Gallery::class => ['galleries', 'frontend'],
            ProductSetting::class => ['products', 'system', 'frontend'],
            NewsSetting::class => ['news', 'system', 'frontend'],
            HomeSection::class => ['frontend', 'system', 'website', 'categories', 'products', 'sliders', 'news', 'brands', 'features'],
        ];

        foreach ($modelTagMap as $model => $tags) {
            if (class_exists($model)) {
                $flushModelCache = function () use ($tags, $model) {
                    if (class_exists(\App\Services\CacheService::class)) {
                        \App\Services\CacheService::forgetTags($tags);
                    }
                    FrontendComposer::flushMemoryCache();
                    SlugResolutionService::flushMemoryCache();
                    if ($model === ProductSetting::class || $model === System::class) {
                        ProductSetting::flushMemoryCache();
                    }
                    if ($model === NewsSetting::class || $model === System::class) {
                        NewsSetting::flushMemoryCache();
                    }
                    if ($model === System::class) {
                        Cache::forget('frontend_global_data');
                        Cache::forget('admin_system_config');
                    }
                    Cache::forget('dynamic_sitemap_xml');
                };
                $model::saved($flushModelCache);
                $model::deleted($flushModelCache);
            }
        }

        // Auto-register 301 redirects when SEO slugs change on core content models
        $slugTrackedModels = [Product::class, News::class, CateProduct::class, CateNew::class, Page::class];
        foreach ($slugTrackedModels as $slugModel) {
            if (class_exists($slugModel)) {
                $slugModel::updating(function ($record) {
                    if (! $record->isDirty('slug')) {
                        return;
                    }
                    $rawOriginal = $record->getRawOriginal('slug');
                    $oldSlugs = is_string($rawOriginal) ? (json_decode($rawOriginal, true) ?: ['vi' => $rawOriginal]) : (array) $rawOriginal;
                    $newRaw = $record->getAttributes()['slug'] ?? null;
                    $newSlugs = is_string($newRaw) ? (json_decode($newRaw, true) ?: ['vi' => $newRaw]) : (array) $newRaw;

                    $redirectService = app(\App\Services\RedirectService::class);
                    foreach ($oldSlugs as $loc => $oldSlug) {
                        $newSlug = $newSlugs[$loc] ?? null;
                        if (is_string($oldSlug) && is_string($newSlug) && $oldSlug !== '' && $newSlug !== '' && $oldSlug !== $newSlug) {
                            $prefix = ($loc === 'vi' || $loc === 'vn') ? '/' : '/' . $loc . '/';
                            $redirectService->registerSlugChangeRedirect($prefix . ltrim($oldSlug, '/'), $prefix . ltrim($newSlug, '/'));
                        }
                    }
                });
            }
        }
    }
}
