<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SlugResolutionService
{
    /**
     * Request-level memoized slug lookups.
     */
    protected static array $memoizedSlugs = [];

    /**
     * Flush in-memory slug lookup cache.
     */
    public static function flushMemoryCache(): void
    {
        static::$memoizedSlugs = [];
    }

    /**
     * Resolve content entity by slug with priority chain:
     * Product > News > CateProduct > CateNews > Service > CateService > Project > CateProject > Page > Brand
     */
    public function findContentBySlug(string $slug): ?array
    {
        $locale = app()->getLocale();
        if (! in_array($locale, ['vn', 'en'], true)) {
            $locale = 'vn';
        }

        $memoKey = "{$locale}:{$slug}";
        if (array_key_exists($memoKey, static::$memoizedSlugs)) {
            return static::$memoizedSlugs[$memoKey];
        }

        try {
            $cacheKey = "slug_resolution_{$locale}_" . md5($slug);
            $cacheTtl = (int) (config('cache.ttl.slug_resolution', 3600) / 60) ?: 60;

            $resolved = CacheService::remember('frontend', $cacheKey, $cacheTtl, function () use ($slug, $locale) {
                $slugColumn = "slug_{$locale}";
                $titleColumn = "name_{$locale}";

                $query = "
                    SELECT 'product' as type, id_product as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_products WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'news' as type, id_new as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_news WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'cate_product' as type, id_cate_product as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_cate_products WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'cate_news' as type, id_cate_new as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_cate_news WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'service' as type, id_service as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_services WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'cate_service' as type, id_cate_service as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_cate_services WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'project' as type, id_project as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_projects WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'cate_project' as type, id_cate_project as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_cate_projects WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'page' as type, id_page as id, {$slugColumn} as slug, {$titleColumn} as title
                    FROM tp_pages WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                    UNION ALL
                    SELECT 'brand' as type, id_brand as id, {$titleColumn} as slug, {$titleColumn} as title
                    FROM tp_brands WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ? OR name_vn = ?) AND status = 1
                    LIMIT 1
                ";

                $params = array_fill(0, 31, $slug);
                $result = DB::select($query, $params);

                return $result ? (array) $result[0] : null;
            });

            static::$memoizedSlugs[$memoKey] = $resolved;

            return $resolved;
        } catch (\Throwable $e) {
            Log::error("Error finding content by slug '{$slug}': " . $e->getMessage());

            return null;
        }
    }
}
