<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\BaseLogsActivity;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateProduct extends Model
{
    use AutoImagePathsTrait, BaseLogsActivity, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug', 'intro', 'content', 'keyword', 'description'];

    protected $primaryKey = 'id_cate_product';

    protected $table = 'tp_cate_products';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'intro',
        'content',
        'keyword',
        'description',
        'name_vn',
        'name_en',
        'slug_vn',
        'slug_en',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
        'seo_score',
        'image_vn',
        'image_en',
        'stt',
        'parent_id',
    ];

    public function getSlugAttribute()
    {
        return $this->getTranslation('slug');
    }

    public function getNameAttribute()
    {
        return $this->getTranslation('name');
    }

    public function getImageAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'image_' . $locale} ?: ($this->image_vn ?: $this->image_en);
    }

    public function getKeywordAttribute()
    {
        return $this->getTranslation('keyword');
    }

    public function getKeywordsAttribute()
    {
        return $this->getKeywordAttribute();
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslation('description');
    }

    protected $casts = [
        'status' => 'boolean',
        'seo_score' => 'integer',
    ];

    public function children()
    {
        return $this->hasMany(CateProduct::class, 'parent_id', 'id_cate_product');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id_cate_product')->where('status', 1)
        ->select('id_product', 'uuid', 'name', 'slug', 'price', 'image_vn', 'image_en', 'intro', 'category_id', 'status', 'hot', 'stt', 'created_at')
        ->orderBy('stt', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(CateProduct::class, 'parent_id', 'id_cate_product');
    }
}
