<?php

namespace Tests\Unit;

use App\Models\ProductSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        ProductSetting::set('products_currency_unit', 'đ');
        parent::tearDown();
    }

    public function test_default_currency_unit_is_vnd_symbol(): void
    {
        $unit = currency_unit();
        $this->assertEquals('đ', $unit);
    }

    public function test_currency_unit_can_be_dynamically_changed(): void
    {
        ProductSetting::set('products_currency_unit', 'USD');
        $this->assertEquals('USD', currency_unit());

        ProductSetting::set('products_currency_unit', '$');
        $this->assertEquals('$', currency_unit());

        ProductSetting::set('products_currency_unit', 'VNĐ');
        $this->assertEquals('VNĐ', currency_unit());
    }

    public function test_format_price_helper_applies_currency_unit(): void
    {
        ProductSetting::set('products_currency_unit', 'USD');
        $this->assertEquals('1.500.000 USD', format_price(1500000));

        $this->assertEquals('1.500.000', format_price(1500000, false));
    }
}
