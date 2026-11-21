<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use App\Traits\HasJsonTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory, HasJsonTranslations;

    public array $translatable = ['name', 'slug'];

    protected $table = 'tp_partners';

    protected $primaryKey = 'id_partner';

    protected $fillable = [
        'name',
        'slug',
        'name_vn',
        'name_en',
        'slug_vn',
        'slug_en',
        'image_vn',
        'image_en',
        'stt',
        'uuid',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getSlugAttribute()
    {
        return $this->getTranslation('slug') ?: \Illuminate\Support\Str::slug($this->name);
    }

    public function getSlugVnAttribute()
    {
        return $this->attributes['slug_vn'] ?? \Illuminate\Support\Str::slug($this->name_vn);
    }
}
