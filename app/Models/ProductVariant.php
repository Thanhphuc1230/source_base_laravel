<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $table = 'tp_product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'option_values',
        'price',
        'price_old',
        'quantity',
        'image',
        'is_default',
        'status',
    ];

    protected $casts = [
        'option_values' => 'array',
        'price' => 'decimal:2',
        'price_old' => 'decimal:2',
        'quantity' => 'integer',
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    /**
     * Tên mô tả biến thể (vd: 1m8 x 2m / Gỗ Óc Chó)
     */
    public function getVariantNameAttribute(): string
    {
        if (is_array($this->option_values)) {
            return implode(' / ', array_values($this->option_values));
        }
        return '';
    }
}
