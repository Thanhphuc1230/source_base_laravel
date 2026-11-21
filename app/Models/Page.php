<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\BaseLogsActivity;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use AutoImagePathsTrait, BaseLogsActivity, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug', 'content', 'keyword', 'description'];

    protected $table = 'tp_pages';

    protected $primaryKey = 'id_page';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'content',
        'keyword',
        'description',
        'name_vn',
        'name_en',
        'slug_vn',
        'slug_en',
        'content_vn',
        'content_en',
        'footer',
        'stt',
        'image_vn',
        'image_en',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
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

    public function getContentAttribute()
    {
        return $this->getTranslation('content');
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
        'footer' => 'boolean',
    ];
}
