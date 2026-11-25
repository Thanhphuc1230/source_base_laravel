<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\BaseLogsActivity;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use AutoImagePathsTrait, BaseLogsActivity, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug', 'intro', 'content', 'keyword', 'description'];

    protected $table = 'tp_projects';

    protected $primaryKey = 'id_project';

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
        'intro_vn',
        'intro_en',
        'content_vn',
        'content_en',
        'category_id',
        'stt',
        'slug_vn',
        'slug_en',
        'image_vn',
        'image_en',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
        'status',
        'home',
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
        'views' => 'integer',
    ];

    public function cate()
    {
        return $this->belongsTo(CateProject::class, 'category_id', 'id_cate_project');
    }
}
