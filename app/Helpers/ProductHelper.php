<?php

use App\Models\Product;
use App\Models\ProductSetting;

function getProduct($product_id)
{
    $data['product'] = Product::where('id_product', $product_id)->first();

    return $data['product'];
}

if (! function_exists('currency_unit')) {
    /**
     * Get the active system-wide currency unit.
     */
    function currency_unit(): string
    {
        return ProductSetting::getCurrencyUnit();
    }
}

if (! function_exists('format_price')) {
    /**
     * Format price with active currency unit.
     */
    function format_price($price, bool $showUnit = true): string
    {
        $formatted = number_format((float) $price, 0, ',', '.');
        return $showUnit ? $formatted . ' ' . currency_unit() : $formatted;
    }
}

