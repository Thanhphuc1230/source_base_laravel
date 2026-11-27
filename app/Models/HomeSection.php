<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    public const TYPES = [
        'hero' => 'Banner Chính (Hero Slider)',
        'features' => 'Cam Kết & Dịch Vụ (Features)',
        'icon_categories' => 'Icon Danh Mục Nổi Bật',
        'flash_sale' => 'Sản Phẩm Nổi Bật (Hot Sale)',
        'category_products' => 'Khối Danh Mục Sản Phẩm',
        'mid_banner' => 'Banner Đôi Giữa Trang',
        'brands' => 'Thương Hiệu Đối Tác',
        'news' => 'Khối Tin Tức',
    ];

    protected $table = 'tp_home_sections';

    protected $fillable = [
        'uuid',
        'type',
        'reference_id',
        'title_vn',
        'title_en',
        'limit',
        'stt',
        'status',
    ];

    protected $casts = [
        'reference_id' => 'integer',
        'limit' => 'integer',
        'stt' => 'integer',
        'status' => 'boolean',
    ];

    public function categoryProduct()
    {
        return $this->belongsTo(CateProduct::class, 'reference_id', 'id_cate_product');
    }

    public function categoryNews()
    {
        return $this->belongsTo(CateNew::class, 'reference_id', 'id_cate_new');
    }

    public function getLocalizedTitleAttribute(): string
    {
        if (app()->getLocale() === 'en') {
            return $this->title_en ?: ($this->title_vn ?: '');
        }

        return $this->title_vn ?: ($this->title_en ?: '');
    }
}
