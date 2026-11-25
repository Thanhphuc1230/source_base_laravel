<?php

namespace App\Models;

use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name'];

    protected $table = 'tp_product_attributes';

    protected $fillable = [
        'product_id',
        'name',
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
        return $this->getTranslation('name');
    }
}
