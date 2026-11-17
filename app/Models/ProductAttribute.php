<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_product_attributes';

    protected $fillable = [
        'product_id',
        'name_vn',
        'name_en',
        'values_json',
        'stt',
    ];

    protected $casts = [
        'values_json' => 'array',
        'stt' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'name_' . $locale} ?: ($this->name_vn ?: $this->name_en);
    }
}
