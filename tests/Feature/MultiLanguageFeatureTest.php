<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\System;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiLanguageFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_switch_route_sets_locale_in_session(): void
    {
        // Test switching to all 6 supported languages
        $locales = ['vi', 'en', 'de', 'ja', 'ko', 'zh'];

        foreach ($locales as $loc) {
            $response = $this->get(route('lang', ['locale' => $loc]));
            $response->assertRedirect();
            $this->assertEquals($loc, session('locale'));
        }

        // Test invalid locale returns 404
        $invalidResponse = $this->get(route('lang', ['locale' => 'xx']));
        $invalidResponse->assertStatus(404);
    }

    public function test_active_languages_toggle_in_system_settings(): void
    {
        $system = System::first();
        if (!$system) {
            $system = new System();
            $system->email = 'admin@example.com';
        }

        $system->active_languages = ['vi', 'en', 'ja'];
        $system->save();

        $this->assertEquals(['vi', 'en', 'ja'], $system->fresh()->active_languages);

        // Attempting to switch to an inactive language should 404
        $response = $this->get(route('lang', ['locale' => 'de']));
        $response->assertStatus(404);

        // Attempting to switch to an active language should succeed
        $response = $this->get(route('lang', ['locale' => 'ja']));
        $response->assertRedirect();
        $this->assertEquals('ja', session('locale'));
    }

    public function test_dynamic_product_json_translations_in_frontend(): void
    {
        $cate = new \App\Models\CateProduct();
        $cate->uuid = (string) \Illuminate\Support\Str::uuid();
        $cate->name = ['vi' => 'Bàn ăn', 'en' => 'Dining Table'];
        $cate->slug = ['vi' => 'ban-an', 'en' => 'dining-table'];
        $cate->save();

        $product = new Product();
        $product->uuid = (string) \Illuminate\Support\Str::uuid();
        $product->name = [
            'vi' => 'Bàn Ăn Gỗ Sồi Oakhaven',
            'en' => 'Oakhaven Oak Dining Table',
            'ja' => 'オークヘイブン オーク ダイニングテーブル',
            'de' => 'Oakhaven Eichen Esstisch',
            'ko' => '오크헤이븐 참나무 식탁',
            'zh' => '橡木餐桌',
        ];
        $product->slug = [
            'vi' => 'ban-an-go-soi-oakhaven',
            'en' => 'oakhaven-oak-dining-table',
            'ja' => 'oakhaven-oak-dining-table-ja',
            'de' => 'oakhaven-eichen-esstisch',
            'ko' => 'oakhaven-table-ko',
            'zh' => 'oakhaven-table-zh',
        ];
        $product->intro = [
            'vi' => 'Mô tả ngắn bàn ăn gỗ sồi',
            'en' => 'Short description for oak dining table',
        ];
        $product->content = [
            'vi' => '<p>Chi tiết bàn ăn gỗ sồi cao cấp.</p>',
            'en' => '<p>Detailed content for oak dining table.</p>',
        ];
        $product->category_id = $cate->id_cate_product;
        $product->price = 10000000;
        $product->status = 1;
        $product->save();

        // 1. Vietnamese resolution
        app()->setLocale('vi');
        $this->assertEquals('Bàn Ăn Gỗ Sồi Oakhaven', $product->name);

        // 2. Japanese resolution
        app()->setLocale('ja');
        $this->assertEquals('オークヘイブン オーク ダイニングテーブル', $product->name);

        // 3. German resolution
        app()->setLocale('de');
        $this->assertEquals('Oakhaven Eichen Esstisch', $product->name);

        // 4. Slug resolution via SlugResolutionService
        $service = app(\App\Services\SlugResolutionService::class);
        $found = $service->findContentBySlug('oakhaven-oak-dining-table');
        $this->assertNotNull($found);
        $this->assertEquals('product', $found['type']);
    }
}
