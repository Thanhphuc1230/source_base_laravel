<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    /**
     * Render dynamic XML sitemap with 12h caching.
     */
    public function generateXml(): string
    {
        $cacheTtl = 60 * 60 * 12; // 12 hours

        return Cache::remember('dynamic_sitemap_xml', $cacheTtl, function () {
            $sitemap = Sitemap::create();

            // 1. Homepage
            $sitemap->add(
                Url::create('/')
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate(Carbon::now())
            );

            // 2. Product Categories
            $this->appendModuleSlugs($sitemap, 'tp_cate_products', 0.8, Url::CHANGE_FREQUENCY_WEEKLY, true);

            // 3. Products
            $this->appendModuleSlugs($sitemap, 'tp_products', 0.9, Url::CHANGE_FREQUENCY_DAILY, true);

            // 4. News Categories
            $this->appendModuleSlugs($sitemap, 'tp_cate_news', 0.7, Url::CHANGE_FREQUENCY_WEEKLY, false);

            // 5. News Articles
            $this->appendModuleSlugs($sitemap, 'tp_news', 0.8, Url::CHANGE_FREQUENCY_WEEKLY, false);

            // 6. Static Pages
            $this->appendModuleSlugs($sitemap, 'tp_pages', 0.6, Url::CHANGE_FREQUENCY_MONTHLY, false);

            return $sitemap->render();
        });
    }

    /**
     * Append active rows from a module table into Sitemap.
     */
    protected function appendModuleSlugs(
        Sitemap $sitemap,
        string $table,
        float $priority,
        string $frequency,
        bool $includeEnglish = false
    ): void {
        if (! Schema::hasTable($table)) {
            return;
        }

        $rows = DB::table($table)
            ->where('status', 1)
            ->select('slug_vn', 'slug_en', 'updated_at', 'created_at')
            ->get();

        foreach ($rows as $row) {
            $lastmod = Carbon::parse($row->updated_at ?? $row->created_at ?? now());

            if (! empty($row->slug_vn)) {
                $sitemap->add(
                    Url::create('/' . ltrim($row->slug_vn, '/'))
                        ->setPriority($priority)
                        ->setChangeFrequency($frequency)
                        ->setLastModificationDate($lastmod)
                );
            }

            if ($includeEnglish && ! empty($row->slug_en)) {
                $sitemap->add(
                    Url::create('/' . ltrim($row->slug_en, '/'))
                        ->setPriority($priority)
                        ->setChangeFrequency($frequency)
                        ->setLastModificationDate($lastmod)
                );
            }
        }
    }
}
