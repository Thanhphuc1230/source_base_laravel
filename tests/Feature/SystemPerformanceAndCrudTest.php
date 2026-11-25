<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slider;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\CacheService;
use App\Services\HomeService;
use App\Services\ProductService;
use App\Services\SlugResolutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class SystemPerformanceAndCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_service_ttl_constants_are_in_seconds(): void
    {
        $this->assertSame(900, CacheService::TTL['short']);
        $this->assertSame(3600, CacheService::TTL['medium']);
        $this->assertSame(21600, CacheService::TTL['long']);
        $this->assertSame(86400, CacheService::TTL['day']);
    }

    public function test_crud_operations_via_repositories_across_core_modules(): void
    {
        // 1. CateProduct CRUD
        $cateProductRepo = app(CateProductRepositoryInterface::class);
        $cateProduct = $cateProductRepo->create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Nội thất gỗ', 'en' => 'Wood Furniture'],
            'slug' => ['vi' => 'noi-that-go', 'en' => 'wood-furniture'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);
        $this->assertDatabaseHas('tp_cate_products', ['id_cate_product' => $cateProduct->id_cate_product]);

        $cateProductRepo->update([
            'name' => ['vi' => 'Nội thất gỗ cao cấp', 'en' => 'Luxury Wood Furniture'],
        ], $cateProduct->id_cate_product);
        $this->assertSame('Nội thất gỗ cao cấp', $cateProduct->fresh()->getTranslation('name', 'vi'));

        // 2. Product CRUD
        $productRepo = app(ProductRepositoryInterface::class);
        $product = $productRepo->create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Bàn ăn sồi', 'en' => 'Oak Dining Table'],
            'slug' => ['vi' => 'ban-an-soi', 'en' => 'oak-dining-table'],
            'intro' => ['vi' => 'Gỗ sồi tự nhiên', 'en' => 'Natural oak'],
            'content' => ['vi' => '<p>Chi tiết</p>', 'en' => '<p>Details</p>'],
            'price' => 12000000,
            'category_id' => $cateProduct->id_cate_product,
            'status' => 1,
            'hot' => 1,
            'stt' => 1,
        ]);
        $this->assertDatabaseHas('tp_products', ['id_product' => $product->id_product]);

        $productRepo->update(['price' => 13500000], $product->id_product);
        $this->assertEquals(13500000, (float) $product->fresh()->price);

        // 3. CateNew & News CRUD
        $cateNewRepo = app(CateNewRepositoryInterface::class);
        $cateNew = $cateNewRepo->create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Tin tức', 'en' => 'News'],
            'slug' => ['vi' => 'tin-tuc', 'en' => 'news'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);
        $newsRepo = app(NewsRepositoryInterface::class);
        $news = $newsRepo->create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Xu hướng 2026', 'en' => 'Trends 2026'],
            'slug' => ['vi' => 'xu-huong-2026', 'en' => 'trends-2026'],
            'intro' => ['vi' => 'Tóm tắt', 'en' => 'Summary'],
            'content' => ['vi' => '<p>Nội dung</p>', 'en' => '<p>Content</p>'],
            'category_id' => $cateNew->id_cate_new,
            'status' => 1,
            'stt' => 1,
        ]);
        $this->assertDatabaseHas('tp_news', ['id_new' => $news->id_new]);

        // 4. Page & Brand CRUD
        $pageRepo = app(PageRepositoryInterface::class);
        $page = $pageRepo->create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Chính sách bảo hành', 'en' => 'Warranty Policy'],
            'slug' => ['vi' => 'chinh-sach-bao-hanh', 'en' => 'warranty-policy'],
            'content' => ['vi' => '<p>Bảo hành</p>', 'en' => '<p>Warranty</p>'],
            'status' => 1,
            'footer' => 1,
            'stt' => 1,
        ]);
        $this->assertDatabaseHas('tp_pages', ['id_page' => $page->id_page]);

        $brandRepo = app(BrandRepositoryInterface::class);
        $brand = $brandRepo->create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Thương hiệu A', 'en' => 'Brand A'],
            'slug' => ['vi' => 'thuong-hieu-a', 'en' => 'brand-a'],
            'status' => 1,
            'stt' => 1,
        ]);
        $this->assertDatabaseHas('tp_brands', ['id_brand' => $brand->id_brand]);

        // 5. Delete & verify cleanup
        $productRepo->delete($product->id_product);
        $this->assertDatabaseMissing('tp_products', ['id_product' => $product->id_product]);
        $newsRepo->delete($news->id_new);
        $this->assertDatabaseMissing('tp_news', ['id_new' => $news->id_new]);
        $pageRepo->delete($page->id_page);
        $this->assertDatabaseMissing('tp_pages', ['id_page' => $page->id_page]);
        $brandRepo->delete($brand->id_brand);
        $this->assertDatabaseMissing('tp_brands', ['id_brand' => $brand->id_brand]);
    }

    public function test_slug_resolution_caches_hits_and_404_sentinel_with_zero_repeat_queries(): void
    {
        $cate = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Danh mục Test', 'en' => 'Test Cate'],
            'slug' => ['vi' => 'danh-muc-test', 'en' => 'test-cate'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Sản phẩm Test', 'en' => 'Test Product'],
            'slug' => ['vi' => 'san-pham-test', 'en' => 'test-product'],
            'price' => 500000,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
        ]);

        SlugResolutionService::flushMemoryCache();
        Cache::flush();

        $service = app(SlugResolutionService::class);

        // Cold hit
        $found = $service->findContentBySlug('san-pham-test');
        $this->assertNotNull($found);
        $this->assertSame('product', $found['type']);

        // Warm hit after clearing in-memory cache (must hit Redis/Array cache, 0 DB queries)
        SlugResolutionService::flushMemoryCache();
        DB::enableQueryLog();
        $foundCached = $service->findContentBySlug('san-pham-test');
        $hitQueries = DB::getQueryLog();
        DB::disableQueryLog();
        $this->assertNotNull($foundCached);
        $this->assertCount(0, $hitQueries, 'Warm slug lookup must execute 0 SQL queries.');

        // Cold 404 miss
        $missing = $service->findContentBySlug('non-existent-404-slug');
        $this->assertNull($missing);

        // Warm 404 miss after clearing in-memory cache (must hit sentinel in cache, 0 DB queries)
        SlugResolutionService::flushMemoryCache();
        DB::enableQueryLog();
        $missingCached = $service->findContentBySlug('non-existent-404-slug');
        $missQueries = DB::getQueryLog();
        DB::disableQueryLog();
        $this->assertNull($missingCached);
        $this->assertCount(0, $missQueries, 'Repeated 404 slug lookup must hit sentinel cache with 0 SQL queries.');
    }

    public function test_home_service_warm_cache_executes_zero_queries(): void
    {
        Cache::flush();
        $homeService = app(HomeService::class);

        // Cold call populates cache
        $cold = $homeService->getHomeData();
        $this->assertIsArray($cold);

        // Warm call must execute 0 SQL queries
        DB::enableQueryLog();
        $warm = $homeService->getHomeData();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertIsArray($warm);
        $this->assertCount(0, $queries, 'HomeService warm cache must execute 0 SQL queries.');
    }

    public function test_product_category_and_detail_prune_columns_and_use_with_count(): void
    {
        $cate = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Ghế', 'en' => 'Chairs'],
            'slug' => ['vi' => 'ghe', 'en' => 'chairs'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $p1 = Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Ghế A', 'en' => 'Chair A'],
            'slug' => ['vi' => 'ghe-a', 'en' => 'chair-a'],
            'content' => ['vi' => 'LONG_CONTENT_PAYLOAD', 'en' => 'LONG_CONTENT_PAYLOAD'],
            'price' => 1000000,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
        ]);

        $p2 = Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Ghế B', 'en' => 'Chair B'],
            'slug' => ['vi' => 'ghe-b', 'en' => 'chair-b'],
            'content' => ['vi' => 'LONG_CONTENT_PAYLOAD', 'en' => 'LONG_CONTENT_PAYLOAD'],
            'price' => 2000000,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 2,
        ]);

        $productService = app(ProductService::class);

        // 1. Category page uses products_count and does NOT hydrate products relation on sidebar categories
        $catData = $productService->getCategoryProductData($cate->id_cate_product, new Request());
        $sidebarCate = $catData['category_product'][0]->first();
        $this->assertSame(2, (int) $sidebarCate->products_count);
        $this->assertFalse($sidebarCate->relationLoaded('products'));

        // 2. Detail page prunes content on related_product
        $detailData = $productService->getDetailProductData($p1->id_product);
        $related = $detailData['related_product']->first();
        $this->assertNotNull($related);
        $this->assertArrayNotHasKey('content', $related->getAttributes());
        $this->assertArrayNotHasKey('image_detail', $related->getAttributes());
    }
}
