<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the XML sitemap with lastmod, priority and changefreq';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // 1. Trang chủ (Priority 1.0, Daily)
        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setLastModificationDate(Carbon::now())
        );

        // 2. Danh mục sản phẩm (tp_cate_products)
        if (Schema::hasTable('tp_cate_products')) {
            $categories = DB::table('tp_cate_products')
                ->where('status', 1)
                ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                ->get();

            foreach ($categories as $cate) {
                $lastmod = Carbon::parse($cate->updated_at ?? $cate->created_at ?? now());
                if ($cate->slug_vn) {
                    $url = Url::create('/' . $cate->slug_vn . '.html')
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($lastmod);

                    if ($cate->slug_en) {
                        $url->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $cate->slug_en . '.html', 'en');
                    }
                    $sitemap->add($url);
                }

                if ($cate->slug_en) {
                    $sitemap->add(
                        Url::create('/' . $cate->slug_en . '.html')
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($lastmod)
                            ->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $cate->slug_en . '.html', 'en')
                    );
                }
            }
        }

        // 3. Chi tiết sản phẩm (tp_products)
        if (Schema::hasTable('tp_products')) {
            $products = DB::table('tp_products')
                ->where('status', 1)
                ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                ->get();

            foreach ($products as $prod) {
                $lastmod = Carbon::parse($prod->updated_at ?? $prod->created_at ?? now());
                if ($prod->slug_vn) {
                    $url = Url::create('/' . $prod->slug_vn . '.html')
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($lastmod);

                    if ($prod->slug_en) {
                        $url->addAlternate('/' . $prod->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $prod->slug_en . '.html', 'en');
                    }
                    $sitemap->add($url);
                }

                if ($prod->slug_en) {
                    $sitemap->add(
                        Url::create('/' . $prod->slug_en . '.html')
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($lastmod)
                            ->addAlternate('/' . $prod->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $prod->slug_en . '.html', 'en')
                    );
                }
            }
        }

        // 4. Chi tiết Dự án / Công trình (tp_projects)
        if (Schema::hasTable('tp_projects')) {
            $projects = DB::table('tp_projects')
                ->where('status', 1)
                ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                ->get();

            foreach ($projects as $proj) {
                $lastmod = Carbon::parse($proj->updated_at ?? $proj->created_at ?? now());
                if ($proj->slug_vn) {
                    $sitemap->add(
                        Url::create('/' . $proj->slug_vn . '.html')
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($lastmod)
                    );
                }
            }
        }

        // 5. Danh mục tin tức (tp_cate_news)
        if (Schema::hasTable('tp_cate_news')) {
            $newsCategories = DB::table('tp_cate_news')
                ->where('status', 1)
                ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                ->get();

            foreach ($newsCategories as $cate) {
                $lastmod = Carbon::parse($cate->updated_at ?? $cate->created_at ?? now());
                if ($cate->slug_vn) {
                    $url = Url::create('/' . $cate->slug_vn . '.html')
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($lastmod);

                    if ($cate->slug_en) {
                        $url->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $cate->slug_en . '.html', 'en');
                    }
                    $sitemap->add($url);
                }

                if ($cate->slug_en) {
                    $sitemap->add(
                        Url::create('/' . $cate->slug_en . '.html')
                            ->setPriority(0.7)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($lastmod)
                            ->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $cate->slug_en . '.html', 'en')
                    );
                }
            }
        }

        // 6. Chi tiết tin tức / bài viết (tp_news)
        if (Schema::hasTable('tp_news')) {
            $newsList = DB::table('tp_news')
                ->where('status', 1)
                ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                ->get();

            foreach ($newsList as $news) {
                $lastmod = Carbon::parse($news->updated_at ?? $news->created_at ?? now());
                if ($news->slug_vn) {
                    $url = Url::create('/' . $news->slug_vn . '.html')
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($lastmod);

                    if ($news->slug_en) {
                        $url->addAlternate('/' . $news->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $news->slug_en . '.html', 'en');
                    }
                    $sitemap->add($url);
                }

                if ($news->slug_en) {
                    $sitemap->add(
                        Url::create('/' . $news->slug_en . '.html')
                            ->setPriority(0.7)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setLastModificationDate($lastmod)
                            ->addAlternate('/' . $news->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $news->slug_en . '.html', 'en')
                    );
                }
            }
        }

        // 7. Trang nội dung tĩnh (tp_pages)
        if (Schema::hasTable('tp_pages')) {
            $pages = DB::table('tp_pages')
                ->where('status', 1)
                ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
                ->get();

            foreach ($pages as $page) {
                $lastmod = Carbon::parse($page->updated_at ?? $page->created_at ?? now());
                if ($page->slug_vn) {
                    $url = Url::create('/' . $page->slug_vn . '.html')
                        ->setPriority(0.6)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($lastmod);

                    if ($page->slug_en) {
                        $url->addAlternate('/' . $page->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $page->slug_en . '.html', 'en');
                    }
                    $sitemap->add($url);
                }

                if ($page->slug_en) {
                    $sitemap->add(
                        Url::create('/' . $page->slug_en . '.html')
                            ->setPriority(0.6)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setLastModificationDate($lastmod)
                            ->addAlternate('/' . $page->slug_vn . '.html', 'vi')
                            ->addAlternate('/' . $page->slug_en . '.html', 'en')
                    );
                }
            }
        }

        // 8. Trang tĩnh Liên hệ (lien-he)
        $sitemap->add(
            Url::create('/lien-he')
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setLastModificationDate(Carbon::now())
        );

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully with full lastmod, priority and changefreq.');
    }
}
