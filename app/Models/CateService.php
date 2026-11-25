<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateService extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug', 'intro', 'content', 'keyword', 'description'];

    protected $table = 'tp_cate_services';

    protected $primaryKey = 'id_cate_service';

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
        'home' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(CateService::class, 'parent_id', 'id_cate_service');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'id_cate_service')->where('status', 1)->orderBy('stt', 'asc');
    }
}
