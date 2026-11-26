<?php

namespace Tests\Feature;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\News;
use App\Models\Product;
use App\Models\Redirect;
use App\Services\ContentTocService;
use App\Services\NewsService;
use App\Services\RedirectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class TocAndRedirectManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_toc_service_extracts_headings_injects_unique_ids_and_calculates_reading_time(): void
    {
        $words = implode(' ', array_fill(0, 420, 'nội'));
        $html = '<h2>Giới thiệu chung</h2><p>' . $words . '</p><h3>Chi tiết kỹ thuật</h3><h2>Giới thiệu chung</h2>';

        $result = app(ContentTocService::class)->process($html);

        $this->assertCount(3, $result['toc']);
        $this->assertSame(2, $result['toc'][0]['level']);
        $this->assertSame('gioi-thieu-chung', $result['toc'][0]['id']);
        $this->assertSame('Giới thiệu chung', $result['toc'][0]['text']);

        $this->assertSame(3, $result['toc'][1]['level']);
        $this->assertSame('chi-tiet-ky-thuat', $result['toc'][1]['id']);

        $this->assertSame(2, $result['toc'][2]['level']);
        $this->assertSame('gioi-thieu-chung-1', $result['toc'][2]['id']);

        $this->assertStringContainsString('id="gioi-thieu-chung"', $result['html']);
        $this->assertStringContainsString('id="gioi-thieu-chung-1"', $result['html']);
        $this->assertGreaterThanOrEqual(3, $result['reading_time']);
    }

    public function test_news_detail_service_and_view_render_toc_and_reading_time(): void
    {
        $cate = CateNew::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Tin tức', 'en' => 'News'],
            'slug' => ['vi' => 'tin-tuc-toc', 'en' => 'news-toc'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $news = News::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Bài viết có mục lục', 'en' => 'Article with TOC'],
            'slug' => ['vi' => 'bai-viet-co-muc-luc', 'en' => 'article-with-toc'],
            'intro' => ['vi' => 'Tóm tắt', 'en' => 'Intro'],
            'content' => [
                'vi' => '<h2>Phần Một</h2><p>Nội dung phần một.</p><h3>Mục Con 1.1</h3><p>Chi tiết.</p>',
                'en' => '<h2>Part One</h2><p>Content part one.</p>',
            ],
            'category_id' => $cate->id_cate_new,
            'status' => 1,
            'stt' => 1,
        ]);

        app()->setLocale('vi');
        $data = app(NewsService::class)->getDetailNewsData($news->id_new);
        $this->assertCount(2, $data['toc']);
        $this->assertSame(1, $data['reading_time']);
        $this->assertStringContainsString('id="phan-mot"', $data['processed_content']);

        $response = $this->get('/bai-viet-co-muc-luc');
        $response->assertStatus(200);
        $response->assertSee('Mục lục bài viết');
        $response->assertSee('Phần Một');
        $response->assertSee('phút đọc');

        while (ob_get_level() > 1) {
            ob_end_clean();
        }
    }

    public function test_redirect_service_crud_and_middleware_301_and_404_monitor(): void
    {
        Cache::flush();
        $service = app(RedirectService::class);

        // 1. Create & Update & Toggle CRUD
        $created = $service->create([
            'source_url' => 'duong-dan-cu',
            'target_url' => '/duong-dan-moi',
            'status_code' => 301,
            'is_active' => true,
            'note' => 'Test 301',
        ]);
        $this->assertSame('/duong-dan-cu', $created->source_url);

        $service->update($created->uuid, [
            'source_url' => '/duong-dan-cu',
            'target_url' => '/dich-moi',
            'status_code' => 301,
            'is_active' => true,
            'note' => 'Updated 301',
        ]);
        $this->assertSame('/dich-moi', $created->fresh()->target_url);

        // 2. Middleware 301 redirect & hit counter
        $res301 = $this->get('/duong-dan-cu');
        $res301->assertRedirect('/dich-moi');
        $res301->assertStatus(301);
        $this->assertSame(1, $created->fresh()->hits);

        // 3. 404 Monitor logs missing page & ignores static images
        $res404 = $this->get('/trang-khong-ton-tai-abc');
        $res404->assertStatus(404);
        $logged404 = Redirect::where('source_url', '/trang-khong-ton-tai-abc')->first();
        $this->assertNotNull($logged404);
        $this->assertSame(404, $logged404->status_code);
        $this->assertSame(1, $logged404->hits);

        $this->get('/trang-khong-ton-tai-abc')->assertStatus(404);
        $this->assertSame(2, $logged404->fresh()->hits);

        $this->get('/anh-khong-ton-tai.jpg')->assertStatus(404);
        $this->assertNull(Redirect::where('source_url', '/anh-khong-ton-tai.jpg')->first());

        // 4. Delete
        $service->delete($created->uuid);
        $this->assertDatabaseMissing('tp_redirects', ['uuid' => $created->uuid]);
    }

    public function test_updating_product_slug_auto_creates_301_redirect(): void
    {
        Cache::flush();

        $cate = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Tủ', 'en' => 'Cabinets'],
            'slug' => ['vi' => 'tu', 'en' => 'cabinets'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $product = Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Tủ gỗ', 'en' => 'Wood Cabinet'],
            'slug' => ['vi' => 'tu-go-cu', 'en' => 'old-wood-cabinet'],
            'price' => 5000000,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
        ]);

        $product->update([
            'slug' => ['vi' => 'tu-go-moi', 'en' => 'new-wood-cabinet'],
        ]);

        $this->assertDatabaseHas('tp_redirects', [
            'source_url' => '/tu-go-cu',
            'target_url' => '/tu-go-moi',
            'status_code' => 301,
            'is_active' => 1,
        ]);

        $this->get('/tu-go-cu')->assertRedirect('/tu-go-moi')->assertStatus(301);
    }
}
