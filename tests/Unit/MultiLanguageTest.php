<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class MultiLanguageTest extends TestCase
{
    public function test_product_model_supports_json_translations_and_fallbacks(): void
    {
        $product = new Product();
        $product->name = [
            'vi' => 'Sofa Phòng Khách',
            'en' => 'Living Room Sofa',
            'de' => 'Wohnzimmer Sofa',
            'ja' => 'リビングルームのソファ',
            'ko' => '거실 소파',
            'zh' => '客厅沙发',
        ];

        app()->setLocale('vi');
        $this->assertEquals('Sofa Phòng Khách', $product->name);
        $this->assertEquals('Sofa Phòng Khách', $product->name_vn);
        $this->assertEquals('Sofa Phòng Khách', $product->name_vi);

        app()->setLocale('en');
        $this->assertEquals('Living Room Sofa', $product->name);
        $this->assertEquals('Living Room Sofa', $product->name_en);

        app()->setLocale('de');
        $this->assertEquals('Wohnzimmer Sofa', $product->name);
        $this->assertEquals('Wohnzimmer Sofa', $product->name_de);

        app()->setLocale('ja');
        $this->assertEquals('リビングルームのソファ', $product->name);
        $this->assertEquals('リビングルームのソファ', $product->name_ja);

        app()->setLocale('ko');
        $this->assertEquals('거실 소파', $product->name);
        $this->assertEquals('거실 소파', $product->name_ko);

        app()->setLocale('zh');
        $this->assertEquals('客厅沙发', $product->name);
        $this->assertEquals('客厅沙发', $product->name_zh);

        // Test fallback to default (vi) when translation for locale is missing
        $partial = new Product();
        $partial->name = [
            'vi' => 'Bàn Gỗ Sồi',
        ];
        app()->setLocale('de');
        $this->assertEquals('Bàn Gỗ Sồi', $partial->name);
    }
}
