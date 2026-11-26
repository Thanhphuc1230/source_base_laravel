<?php

namespace Tests\Feature;

use App\Models\Analytic;
use App\Models\Brand;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\CateProject;
use App\Models\CateService;
use App\Models\Feature;
use App\Models\FeedBack;
use App\Models\Menu;
use App\Models\News;
use App\Models\OrderProduct;
use App\Models\OrderShipping;
use App\Models\OrderStatus;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminAnalyticsAndCrudAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();

        $this->admin = User::create([
            'uuid' => (string) Str::uuid(),
            'fullname' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'level' => 1,
            'status' => 1,
        ]);
    }

    public function test_analytics_dashboard_with_populated_data(): void
    {
        $cate = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Danh mục 1', 'en' => 'Category 1'],
            'slug' => ['vi' => 'danh-muc-1', 'en' => 'category-1'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $product = Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Sản phẩm A', 'en' => 'Product A'],
            'slug' => ['vi' => 'san-pham-a', 'en' => 'product-a'],
            'price' => 100000,
            'quantity' => 2,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
        ]);

        $shipping = OrderShipping::create([
            'uuid_order_shipping' => (string) Str::uuid(),
            'f_name_order' => 'Nguyen',
            'l_name_order' => 'Van A',
            'phone' => '0901234567',
            'email' => 'khach@example.com',
            'address' => '123 ABC',
        ]);

        $order = OrderStatus::create([
            'uuid_order_status' => (string) Str::uuid(),
            'shipping_id' => $shipping->id_order_shipping,
            'total' => 200000,
            'status' => 1,
            'payment_method' => 'cod',
        ]);

        OrderProduct::create([
            'uuid_order_product' => (string) Str::uuid(),
            'order_status_id' => $order->id_order_status,
            'product_id' => $product->id_product,
            'quantity' => 2,
            'price' => 100000,
        ]);

        Analytic::create([
            'visit_date' => now()->toDateString(),
            'visit_count' => 42,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/analytics');
        $response->assertStatus(200);
        $response->assertSee('Sản phẩm A');
        $response->assertSee('Danh mục 1');

        $this->actingAs($this->admin)
            ->getJson('/admin/analytics?ajax=1&chart_type=revenue&period=7_days')
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->actingAs($this->admin)
            ->getJson('/admin/analytics?ajax=1&chart_type=revenue&period=year&year=' . date('Y'))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->actingAs($this->admin)
            ->getJson('/admin/analytics?ajax=1&month=' . date('n') . '&year=' . date('Y'))
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_cate_product_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/cate_product')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/cate_product/create')->assertStatus(200);

        $res = $this->actingAs($this->admin)->post('/admin/cate_product/store', [
            'name_vn' => 'Danh mục SP Test',
            'keyword_vn' => 'danh muc sp',
            'description_vn' => 'Mo ta danh muc sp',
            'parent_id' => 0,
            'return_list' => '1',
        ]);
        $res->assertRedirect();
        $res->assertSessionHasNoErrors();

        $item = CateProduct::latest('id_cate_product')->first();
        $this->assertNotNull($item);
        $this->assertSame('Danh mục SP Test', $item->getTranslation('name', 'vi'));

        $this->actingAs($this->admin)->get("/admin/cate_product/edit/{$item->uuid}/1")->assertStatus(200);

        $resUp = $this->actingAs($this->admin)->post("/admin/cate_product/update/{$item->uuid}", [
            'name_vn' => 'Danh mục SP Updated',
            'keyword_vn' => 'danh muc sp updated',
            'description_vn' => 'Mo ta updated',
            'parent_id' => 0,
            'currentPage' => 1,
        ]);
        $resUp->assertRedirect();
        $resUp->assertSessionHasNoErrors();
        $this->assertSame('Danh mục SP Updated', $item->fresh()->getTranslation('name', 'vi'));

        $this->actingAs($this->admin)->get("/admin/cate_product/destroy/{$item->uuid}")->assertRedirect();
        $this->assertDatabaseMissing('tp_cate_products', ['id_cate_product' => $item->id_cate_product]);
    }

    public function test_product_crud(): void
    {
        $cate = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Cat 1'],
            'slug' => ['vi' => 'cat-1'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $this->actingAs($this->admin)->get('/admin/product')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/product/create')->assertStatus(200);

        $res = $this->actingAs($this->admin)->post('/admin/product/store', [
            'name_vn' => 'Sản phẩm Test CRUD',
            'intro_vn' => 'Giới thiệu ngắn',
            'content_vn' => '<p>Nội dung sản phẩm</p>',
            'keyword_vn' => 'san pham test',
            'description_vn' => 'Mo ta san pham test',
            'price' => 500000,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
            'image_vn' => UploadedFile::fake()->image('prod.jpg'),
            'return_list' => '1',
        ]);
        $res->assertRedirect();
        $res->assertSessionHasNoErrors();

        $item = Product::latest('id_product')->first();
        $this->assertNotNull($item);
        $this->assertSame('Sản phẩm Test CRUD', $item->getTranslation('name', 'vi'));

        $this->actingAs($this->admin)->get("/admin/product/edit/{$item->uuid}/1")->assertStatus(200);

        $resUp = $this->actingAs($this->admin)->post("/admin/product/update/{$item->uuid}", [
            'name_vn' => 'Sản phẩm Test Updated',
            'intro_vn' => 'Giới thiệu updated',
            'content_vn' => '<p>Nội dung updated</p>',
            'keyword_vn' => 'san pham updated',
            'description_vn' => 'Mo ta updated',
            'price' => 600000,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ]);
        $resUp->assertRedirect();
        $resUp->assertSessionHasNoErrors();
        $this->assertSame('Sản phẩm Test Updated', $item->fresh()->getTranslation('name', 'vi'));

        $this->actingAs($this->admin)->get("/admin/product/destroy/{$item->uuid}")->assertRedirect();
        $this->assertDatabaseMissing('tp_products', ['id_product' => $item->id_product]);
    }

    public function test_cate_new_and_news_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/cate_new')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/cate_new/create')->assertStatus(200);

        $res = $this->actingAs($this->admin)->post('/admin/cate_new/store', [
            'name_vn' => 'Danh mục Tin Test',
            'keyword_vn' => 'tin tuc',
            'description_vn' => 'Mo ta tin tuc',
            'parent_id' => 0,
            'return_list' => '1',
        ]);
        $res->assertRedirect();
        $res->assertSessionHasNoErrors();

        $cate = CateNew::latest('id_cate_new')->first();
        $this->assertNotNull($cate);

        $this->actingAs($this->admin)->get("/admin/cate_new/edit/{$cate->uuid}/1")->assertStatus(200);
        $resUp = $this->actingAs($this->admin)->post("/admin/cate_new/update/{$cate->uuid}", [
            'name_vn' => 'Danh mục Tin Updated',
            'keyword_vn' => 'tin tuc updated',
            'description_vn' => 'Mo ta updated',
            'parent_id' => 0,
            'currentPage' => 1,
        ]);
        $resUp->assertRedirect();
        $resUp->assertSessionHasNoErrors();

        // News CRUD
        $this->actingAs($this->admin)->get('/admin/news')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/news/create')->assertStatus(200);

        $resNews = $this->actingAs($this->admin)->post('/admin/news/store', [
            'name_vn' => 'Bài viết Test CRUD',
            'intro_vn' => 'Tóm tắt bài viết',
            'content_vn' => '<p>Nội dung bài viết</p>',
            'keyword_vn' => 'bai viet test',
            'description_vn' => 'Mo ta bai viet',
            'category_id' => $cate->id_cate_new,
            'status' => 1,
            'stt' => 1,
            'image_vn' => UploadedFile::fake()->image('news.jpg'),
            'return_list' => '1',
        ]);
        $resNews->assertRedirect();
        $resNews->assertSessionHasNoErrors();

        $news = News::latest('id_new')->first();
        $this->assertNotNull($news);

        $this->actingAs($this->admin)->get("/admin/news/edit/{$news->uuid}/1")->assertStatus(200);
        $resNewsUp = $this->actingAs($this->admin)->post("/admin/news/update/{$news->uuid}", [
            'name_vn' => 'Bài viết Updated',
            'intro_vn' => 'Tóm tắt updated',
            'content_vn' => '<p>Nội dung updated</p>',
            'keyword_vn' => 'bai viet updated',
            'description_vn' => 'Mo ta updated',
            'category_id' => $cate->id_cate_new,
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ]);
        $resNewsUp->assertRedirect();
        $resNewsUp->assertSessionHasNoErrors();

        $this->actingAs($this->admin)->get("/admin/news/destroy/{$news->uuid}")->assertRedirect();
        $this->assertDatabaseMissing('tp_news', ['id_new' => $news->id_new]);

        $this->actingAs($this->admin)->get("/admin/cate_new/destroy/{$cate->uuid}")->assertRedirect();
        $this->assertDatabaseMissing('tp_cate_news', ['id_cate_new' => $cate->id_cate_new]);
    }

    public function test_page_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/page')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/page/create')->assertStatus(200);

        $res = $this->actingAs($this->admin)->post('/admin/page/store', [
            'name_vn' => 'Trang Giới Thiệu Test',
            'content_vn' => '<p>Nội dung trang</p>',
            'keyword_vn' => 'gioi thieu',
            'description_vn' => 'Mo ta gioi thieu',
            'return_list' => '1',
        ]);
        $res->assertRedirect();
        $res->assertSessionHasNoErrors();

        $page = Page::latest('id_page')->first();
        $this->assertNotNull($page);

        $this->actingAs($this->admin)->get("/admin/page/edit/{$page->uuid}/1")->assertStatus(200);

        $resUp = $this->actingAs($this->admin)->post("/admin/page/update/{$page->uuid}", [
            'name_vn' => 'Trang Giới Thiệu Updated',
            'content_vn' => '<p>Nội dung updated</p>',
            'keyword_vn' => 'gioi thieu updated',
            'description_vn' => 'Mo ta updated',
            'currentPage' => 1,
        ]);
        $resUp->assertRedirect();
        $resUp->assertSessionHasNoErrors();

        $this->actingAs($this->admin)->get("/admin/page/destroy/{$page->uuid}")->assertRedirect();
        $this->assertDatabaseMissing('tp_pages', ['id_page' => $page->id_page]);
    }

    public function test_service_and_project_crud(): void
    {
        // CateService & Service
        $this->actingAs($this->admin)->get('/admin/cate-service')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/cate-service/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/cate-service/store', [
            'name_vn' => 'Danh mục DV Test',
            'keyword_vn' => 'dv',
            'description_vn' => 'mo ta dv',
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $cateSvc = CateService::latest('id_cate_service')->first();
        $this->assertNotNull($cateSvc);
        $this->actingAs($this->admin)->get("/admin/cate-service/edit/{$cateSvc->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/cate-service/update/{$cateSvc->uuid}", [
            'name_vn' => 'Danh mục DV Updated',
            'keyword_vn' => 'dv',
            'description_vn' => 'mo ta dv',
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();

        $this->actingAs($this->admin)->get('/admin/service')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/service/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/service/store', [
            'name_vn' => 'Dịch vụ Test',
            'intro_vn' => 'Intro DV',
            'content_vn' => '<p>Content DV</p>',
            'keyword_vn' => 'dv',
            'description_vn' => 'desc dv',
            'category_id' => $cateSvc->id_cate_service,
            'status' => 1,
            'stt' => 1,
            'image_vn' => UploadedFile::fake()->image('svc.jpg'),
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $svc = Service::latest('id_service')->first();
        $this->assertNotNull($svc);
        $this->actingAs($this->admin)->get("/admin/service/edit/{$svc->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/service/update/{$svc->uuid}", [
            'name_vn' => 'Dịch vụ Updated',
            'intro_vn' => 'Intro DV',
            'content_vn' => '<p>Content DV</p>',
            'keyword_vn' => 'dv',
            'description_vn' => 'desc dv',
            'category_id' => $cateSvc->id_cate_service,
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();

        // CateProject & Project
        $this->actingAs($this->admin)->get('/admin/cate-project')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/cate-project/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/cate-project/store', [
            'name_vn' => 'Danh mục DA Test',
            'keyword_vn' => 'da',
            'description_vn' => 'mo ta da',
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $catePrj = CateProject::latest('id_cate_project')->first();
        $this->assertNotNull($catePrj);
        $this->actingAs($this->admin)->get("/admin/cate-project/edit/{$catePrj->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/cate-project/update/{$catePrj->uuid}", [
            'name_vn' => 'Danh mục DA Updated',
            'keyword_vn' => 'da',
            'description_vn' => 'mo ta da',
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();

        $this->actingAs($this->admin)->get('/admin/project')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/project/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/project/store', [
            'name_vn' => 'Dự án Test',
            'intro_vn' => 'Intro DA',
            'content_vn' => '<p>Content DA</p>',
            'keyword_vn' => 'da',
            'description_vn' => 'desc da',
            'category_id' => $catePrj->id_cate_project,
            'status' => 1,
            'stt' => 1,
            'image_vn' => UploadedFile::fake()->image('prj.jpg'),
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $prj = Project::latest('id_project')->first();
        $this->assertNotNull($prj);
        $this->actingAs($this->admin)->get("/admin/project/edit/{$prj->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/project/update/{$prj->uuid}", [
            'name_vn' => 'Dự án Updated',
            'intro_vn' => 'Intro DA',
            'content_vn' => '<p>Content DA</p>',
            'keyword_vn' => 'da',
            'description_vn' => 'desc da',
            'category_id' => $catePrj->id_cate_project,
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
    }

    public function test_brand_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/brand')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/brand/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/brand/store', [
            'name_vn' => 'Brand Test',
            'image_vn' => UploadedFile::fake()->image('brand.jpg'),
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $brand = Brand::latest('id_brand')->first();
        $this->assertNotNull($brand);
        $this->actingAs($this->admin)->get("/admin/brand/edit/{$brand->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/brand/update/{$brand->uuid}", [
            'name_vn' => 'Brand Updated',
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->get("/admin/brand/destroy/{$brand->uuid}")->assertRedirect();
    }

    public function test_partner_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/partner')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/partner/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/partner/store', [
            'name_vn' => 'Partner Test',
            'image_vn' => UploadedFile::fake()->image('partner.jpg'),
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $partner = Partner::latest('id_partner')->first();
        $this->assertNotNull($partner);
        $this->actingAs($this->admin)->get("/admin/partner/edit/{$partner->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/partner/update/{$partner->uuid}", [
            'name_vn' => 'Partner Updated',
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->get("/admin/partner/destroy/{$partner->uuid}")->assertRedirect();
    }

    public function test_slider_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/slider')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/slider/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/slider/store', [
            'name_vn' => 'Slider Test',
            'image_desktop_vn' => UploadedFile::fake()->image('desk.jpg'),
            'image_mobile_vn' => UploadedFile::fake()->image('mob.jpg'),
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $slider = Slider::latest('id_slider')->first();
        $this->assertNotNull($slider);
        $this->actingAs($this->admin)->get("/admin/slider/edit/{$slider->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/slider/update/{$slider->uuid}", [
            'name_vn' => 'Slider Updated',
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->get("/admin/slider/destroy/{$slider->uuid}")->assertRedirect();
    }

    public function test_feature_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/feature')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/feature/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/feature/store', [
            'title_vn' => 'Feature Test',
            'content_vn' => 'Content Feature',
            'image' => UploadedFile::fake()->image('feature.jpg'),
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $feature = Feature::latest('id_feature')->first();
        $this->assertNotNull($feature);
        $this->actingAs($this->admin)->get("/admin/feature/edit/{$feature->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/feature/update/{$feature->uuid}", [
            'title_vn' => 'Feature Updated',
            'content_vn' => 'Content Updated',
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->get("/admin/feature/destroy/{$feature->uuid}")->assertRedirect();
    }

    public function test_feedback_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/feedback')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/feedback/create')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/feedback/store', [
            'name' => 'Khach Hang',
            'position' => 'CEO',
            'message' => 'Rat tot',
            'image' => UploadedFile::fake()->image('fb.jpg'),
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $fb = FeedBack::latest('id_feedback')->first();
        $this->assertNotNull($fb);
        $this->actingAs($this->admin)->get("/admin/feedback/edit/{$fb->uuid}/1")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/feedback/update/{$fb->uuid}", [
            'name' => 'Khach Hang 2',
            'position' => 'CTO',
            'message' => 'Tuyet voi',
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->get("/admin/feedback/destroy/{$fb->uuid}")->assertRedirect();
    }

    public function test_menu_crud(): void
    {
        $this->actingAs($this->admin)->get('/admin/menu')->assertStatus(200);
        $this->actingAs($this->admin)->post('/admin/menu/store', [
            'name_vn' => 'Menu Test',
            'type' => 'link',
            'parent_id' => 0,
            'link' => '/test',
            'status' => 1,
            'stt' => 1,
            'return_list' => '1',
        ])->assertSessionHasNoErrors();
        $menu = Menu::latest('id_menu')->first();
        $this->assertNotNull($menu);
        $this->actingAs($this->admin)->post("/admin/menu/update/{$menu->uuid}", [
            'name_vn' => 'Menu Updated',
            'type' => 'link',
            'parent_id' => 0,
            'link' => '/test-2',
            'status' => 1,
            'stt' => 1,
            'currentPage' => 1,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->get("/admin/menu/destroy/{$menu->uuid}")->assertRedirect();
    }

    public function test_remaining_admin_modules_index_and_crud(): void
    {
        $getRoutes = [
            '/admin/about',
            '/admin/redirect',
            '/admin/gallery',
            '/admin/system',
            '/admin/product-setting',
            '/admin/news-setting',
            '/admin/mail-config',
            '/admin/mail-template',
            '/admin/fonts',
            '/admin/order',
            '/admin/contact',
            '/admin/comment',
            '/admin/user',
            '/admin/user-role',
            '/admin/activity-log',
            '/admin/profile',
        ];

        foreach ($getRoutes as $uri) {
            $res = $this->actingAs($this->admin)->get($uri);
            $this->assertTrue(
                in_array($res->getStatusCode(), [200, 302]),
                "Route {$uri} returned status {$res->getStatusCode()}"
            );
        }

        // Test search filter on all searchable modules (verifies no legacy name_vn SQL errors)
        $searchRoutes = [
            '/admin/cate_product?search=test',
            '/admin/product?search=test',
            '/admin/cate_new?search=test',
            '/admin/news?search=test',
            '/admin/page?search=test',
            '/admin/cate-service?search=test',
            '/admin/service?search=test',
            '/admin/cate-project?search=test',
            '/admin/project?search=test',
            '/admin/brand?search=test',
            '/admin/partner?search=test',
            '/admin/slider?search=test',
            '/admin/gallery?search=test',
            '/admin/about?search=test',
            '/admin/fonts?search=test',
        ];

        foreach ($searchRoutes as $uri) {
            $res = $this->actingAs($this->admin)->get($uri);
            $this->assertTrue(
                in_array($res->getStatusCode(), [200, 302]),
                "Search route {$uri} failed with {$res->getStatusCode()}"
            );
        }
    }

    public function test_order_detail_and_redirect_crud(): void
    {
        $cate = CateProduct::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'Cat Order'],
            'slug' => ['vi' => 'cat-order'],
            'parent_id' => 0,
            'status' => 1,
            'stt' => 1,
        ]);

        $product = Product::create([
            'uuid' => (string) Str::uuid(),
            'name' => ['vi' => 'SP Order'],
            'slug' => ['vi' => 'sp-order'],
            'price' => 150000,
            'quantity' => 10,
            'category_id' => $cate->id_cate_product,
            'status' => 1,
            'stt' => 1,
        ]);

        $shipping = OrderShipping::create([
            'uuid_order_shipping' => (string) Str::uuid(),
            'f_name_order' => 'Tran',
            'l_name_order' => 'B',
            'phone' => '0909999999',
            'email' => 'b@example.com',
            'address' => '456 XYZ',
        ]);

        $order = OrderStatus::create([
            'uuid_order_status' => (string) Str::uuid(),
            'shipping_id' => $shipping->id_order_shipping,
            'total' => 150000,
            'status' => 0,
            'payment_method' => 'cod',
        ]);

        OrderProduct::create([
            'uuid_order_product' => (string) Str::uuid(),
            'order_status_id' => $order->id_order_status,
            'product_id' => $product->id_product,
            'quantity' => 1,
            'price' => 150000,
        ]);

        $this->actingAs($this->admin)
            ->get("/admin/order/edit/{$order->uuid_order_status}")
            ->assertStatus(200)
            ->assertSee('SP Order');
    }
}
