<?php

namespace Tests\Feature;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\HomeSection;
use App\Models\Product;
use App\Services\HomeLayoutService;
use App\Services\HomeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class HomeLayoutBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_seeds_eight_default_home_sections(): void
    {
        $sections = HomeSection::orderBy('stt')->get();
        $this->assertCount(8, $sections);
        $this->assertSame(
            ['hero', 'features', 'icon_categories', 'flash_sale', 'category_products', 'mid_banner', 'brands', 'news'],
            $sections->pluck('type')->all()
        );
    }

    public function test_home_layout_crud_reorder_toggle_and_cache_invalidation(): void
    {
        $service = app(HomeLayoutService::class);

        $cateProduct = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Sofa Cao Cấp', 'en' => 'Luxury Sofa'],
            'slug' => ['vi' => 'sofa-cao-cap', 'en' => 'luxury-sofa'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $cateNews = CateNew::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Kiến Trúc', 'en' => 'Architecture'],
            'slug' => ['vi' => 'kien-truc', 'en' => 'architecture'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        // Warm home cache first
        app(HomeService::class)->getHomeData();
        $this->assertTrue(Cache::tags(['frontend'])->has('home_page_aggregated_data'));

        // 1. Add category_products, news, and system_blocks
        $added1 = $service->addSections([
            'type' => 'category_products',
            'reference_ids' => [$cateProduct->id_cate_product],
            'limit' => 6,
        ]);
        $this->assertSame(1, $added1);
        $this->assertFalse(Cache::tags(['frontend'])->has('home_page_aggregated_data'), 'Adding section must flush home cache.');

        $added2 = $service->addSections([
            'type' => 'news',
            'reference_ids' => [$cateNews->id_cate_new],
            'limit' => 4,
        ]);
        $this->assertSame(1, $added2);

        $added3 = $service->addSections([
            'type' => 'system_blocks',
            'block_types' => ['brands'],
            'limit' => 10,
        ]);
        $this->assertSame(1, $added3);

        $createdSection = HomeSection::where('type', 'category_products')
            ->where('reference_id', $cateProduct->id_cate_product)
            ->first();
        $this->assertNotNull($createdSection);
        $this->assertSame(6, $createdSection->limit);

        // 2. Quick Update title & limit
        app(HomeService::class)->getHomeData();
        $this->assertTrue(Cache::tags(['frontend'])->has('home_page_aggregated_data'));

        $updated = $service->updateSection($createdSection->uuid, [
            'title_vn' => 'Bộ Sưu Tập Sofa Mới',
            'title_en' => 'New Sofa Collection',
            'limit' => 12,
        ]);
        $this->assertTrue($updated);
        $this->assertSame('Bộ Sưu Tập Sofa Mới', $createdSection->fresh()->title_vn);
        $this->assertSame(12, $createdSection->fresh()->limit);
        $this->assertFalse(Cache::tags(['frontend'])->has('home_page_aggregated_data'), 'Updating section must flush home cache.');

        // 3. Toggle status off & on
        $service->toggleStatus($createdSection->uuid, 0);
        $this->assertFalse($createdSection->fresh()->status);
        $service->toggleStatus($createdSection->uuid, 1);
        $this->assertTrue($createdSection->fresh()->status);

        // 4. Reorder (move createdSection to stt = 1)
        $allUuids = HomeSection::orderBy('stt')->pluck('uuid')->all();
        $reordered = array_values(array_unique(array_merge([$createdSection->uuid], $allUuids)));
        $service->reorder($reordered);
        $this->assertSame(1, $createdSection->fresh()->stt);

        // 5. Delete section
        $deleted = $service->deleteSection($createdSection->uuid);
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('tp_home_sections', ['uuid' => $createdSection->uuid]);
    }

    public function test_frontend_home_renders_dynamic_sections_in_configured_order(): void
    {
        $cateProduct = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Đèn Trang Trí', 'en' => 'Lighting'],
            'slug' => ['vi' => 'den-trang-tri', 'en' => 'lighting'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Đèn Chùm Đồng', 'en' => 'Brass Chandelier'],
            'slug' => ['vi' => 'den-chum-dong', 'en' => 'brass-chandelier'],
            'price' => 8500000,
            'category_id' => $cateProduct->id_cate_product,
            'status' => 1,
            'hot' => 1,
            'stt' => 1,
        ]);

        app(HomeLayoutService::class)->addSections([
            'type' => 'category_products',
            'reference_ids' => [$cateProduct->id_cate_product],
            'limit' => 4,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Đèn Trang Trí');
        $response->assertSee('Đèn Chùm Đồng');

        while (ob_get_level() > 1) {
            ob_end_clean();
        }
    }
}
