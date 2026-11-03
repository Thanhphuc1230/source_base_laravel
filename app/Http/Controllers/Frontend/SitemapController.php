<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Render dynamic XML sitemap with 12h-24h caching.
     */
    public function index()
    {
        $cacheTtl = 60 * 60 * 12; // 12 hours

        $xmlContent = Cache::remember('dynamic_sitemap_xml', $cacheTtl, function () {
            $sitemap = Sitemap::create();

            // 1. Homepage
            $sitemap->add(
                Url::create('/')
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate(Carbon::now())
            );

            // 2. Product Categories
            if (Schema::hasTable('tp_cate_products')) {
                $categories = DB::table('tp_cate_products')
                    ->where('status', 1)
                    ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                    ->get();

                foreach ($categories as $cate) {
                    $lastmod = Carbon::parse($cate->updated_at ?? $cate->created_at ?? now());
                    if (! empty($cate->slug_vn)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($cate->slug_vn, '/'))
                                ->setPriority(0.8)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                    if (! empty($cate->slug_en)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($cate->slug_en, '/'))
                                ->setPriority(0.8)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                }
            }

            // 3. Products
            if (Schema::hasTable('tp_products')) {
                $products = DB::table('tp_products')
                    ->where('status', 1)
                    ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                    ->get();

                foreach ($products as $prod) {
                    $lastmod = Carbon::parse($prod->updated_at ?? $prod->created_at ?? now());
                    if (! empty($prod->slug_vn)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($prod->slug_vn, '/'))
                                ->setPriority(0.9)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                    if (! empty($prod->slug_en)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($prod->slug_en, '/'))
                                ->setPriority(0.9)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                }
            }

            // 4. News Categories
            if (Schema::hasTable('tp_cate_news')) {
                $cateNews = DB::table('tp_cate_news')
                    ->where('status', 1)
                    ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                    ->get();

                foreach ($cateNews as $cn) {
                    $lastmod = Carbon::parse($cn->updated_at ?? $cn->created_at ?? now());
                    if (! empty($cn->slug_vn)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($cn->slug_vn, '/'))
                                ->setPriority(0.7)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                }
            }

            // 5. News Articles
            if (Schema::hasTable('tp_news')) {
                $news = DB::table('tp_news')
                    ->where('status', 1)
                    ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                    ->get();

                foreach ($news as $item) {
                    $lastmod = Carbon::parse($item->updated_at ?? $item->created_at ?? now());
                    if (! empty($item->slug_vn)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($item->slug_vn, '/'))
                                ->setPriority(0.8)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                }
            }

            // 6. Static Pages
            if (Schema::hasTable('tp_pages')) {
                $pages = DB::table('tp_pages')
                    ->where('status', 1)
                    ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                    ->get();

                foreach ($pages as $p) {
                    $lastmod = Carbon::parse($p->updated_at ?? $p->created_at ?? now());
                    if (! empty($p->slug_vn)) {
                        $sitemap->add(
                            Url::create('/' . ltrim($p->slug_vn, '/'))
                                ->setPriority(0.6)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                                ->setLastModificationDate($lastmod)
                        );
                    }
                }
            }

            return $sitemap->render();
        });

        return response($xmlContent, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
