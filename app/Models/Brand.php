<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug'];

    protected $table = 'tp_brands';

    protected $primaryKey = 'id_brand';

    protected $fillable = ['name', 'slug', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'stt', 'uuid', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getNameAttribute()
    {
        return $this->getTranslation('name');
    }

    public function getSlugAttribute()
    {
        return $this->getTranslation('slug') ?: ($this->attributes['slug_vn'] ?? \Illuminate\Support\Str::slug($this->name));
    }

    public function getSlugVnAttribute()
    {
        return $this->attributes['slug_vn'] ?? \Illuminate\Support\Str::slug($this->name_vn);
    }
}
