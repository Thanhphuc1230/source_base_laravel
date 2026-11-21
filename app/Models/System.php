<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\BaseLogsActivity;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use AutoImagePathsTrait, BaseLogsActivity, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'description', 'address', 'footer', 'keyword', 'contact_title', 'contact_desc'];

    protected $table = 'tp_systems';

    protected $primaryKey = 'id_system';

    protected $casts = [
        'active_languages' => 'array',
    ];

    public function getActiveLanguagesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '{'))) {
            return json_decode($value, true) ?: array_keys(config('languages.supported', []));
        }
        return array_keys(config('languages.supported', []));
    }

    protected $fillable = [
        'name',
        'description',
        'address',
        'footer',
        'keyword',
        'contact_title',
        'contact_desc',
        'active_languages',
        'email',
        'email_alert',
        'address_vn',
        'address_en',
        'phone',
        'footer_vn',
        'footer_en',
        'facebook',
        'youtube',
        'twitter',
        'instagram',
        'zalo',
        'favicon',
        'logo',
        'share_image',
        'meta_title',
        'name_vn',
        'name_en',
        'description_vn',
        'description_en',
        'keyword_vn',
        'keyword_en',
        'header_js',
        'body_js',
        'footer_js',
        'map',
        'contact_title_vn',
        'contact_desc_vn',
        'contact_title_en',
        'contact_desc_en',
    ];

    public function getMetaNameAttribute()
    {
        return lang($this, 'name');
    }

    public function getMetaKeywordAttribute()
    {
        return lang($this, 'keyword');
    }

    public function getMetaDescriptionAttribute()
    {
        return lang($this, 'description');
    }
}
