<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\BaseLogsActivity;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use AutoImagePathsTrait, BaseLogsActivity, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug', 'intro', 'content', 'keyword', 'description'];

    protected $table = 'tp_products';

    protected $primaryKey = 'id_product';

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
        'intro_vn',
        'intro_en',
        'price',
        'price_old',
        'content_vn',
        'content_en',
        'image_vn',
        'image_en',
        'image_detail',
        'video',
        'stt',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
        'seo_score',
        'category_id',
        'brand_id',
        'quantity',
        'status',
        'hot',
        'home',
        'sale',
    ];

    public function getSlugAttribute()
    {
        return $this->getTranslation('slug');
    }

    public function getNameAttribute()
    {
        return $this->getTranslation('name');
    }

    public function getIntroAttribute()
    {
        return $this->getTranslation('intro');
    }

    public function getContentAttribute()
    {
        return $this->getTranslation('content');
    }

    public function getImageAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'image_' . $locale} ?: ($this->image_vn ?: $this->image_en);
    }

    public function getVideoUrlAttribute()
    {
        if (empty($this->video)) {
            return null;
        }
        if (filter_var($this->video, FILTER_VALIDATE_URL)) {
            return $this->video;
        }
        return asset($this->video);
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
        'hot' => 'boolean',
        'home' => 'boolean',
        'sale' => 'boolean',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'price_old' => 'decimal:2',
        'seo_score' => 'integer',
    ];

    /**
     * Scope query for low stock products.
     */
    public function scopeLowStock($query, int $threshold = 5)
    {
        return $query->where('status', true)->where('quantity', '<=', $threshold);
    }

    /**
     * Scope query for out of stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', true)->where('quantity', '<=', 0);
    }

    public function cate()
    {
        return $this->belongsTo(CateProduct::class, 'category_id', 'id_cate_product');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id_brand');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id_product')->orderBy('stt', 'asc');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id_product');
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id_product')->where('status', true);
    }
}
