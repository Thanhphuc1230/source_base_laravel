<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tp_home_sections', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type', 50)->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->string('title_vn', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->unsignedInteger('limit')->default(8);
            $table->unsignedInteger('stt')->default(1)->index();
            $table->boolean('status')->default(true)->index();
            $table->timestamps();

            $table->index(['status', 'stt']);
        });

        $now = now();
        $defaults = [
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'hero',
                'reference_id' => null,
                'title_vn' => 'Banner Chính (Hero Slider)',
                'title_en' => 'Hero Slider',
                'limit' => 5,
                'stt' => 1,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'features',
                'reference_id' => null,
                'title_vn' => 'Cam Kết & Dịch Vụ',
                'title_en' => 'Features & Commitments',
                'limit' => 4,
                'stt' => 2,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'icon_categories',
                'reference_id' => null,
                'title_vn' => 'Danh Mục Nổi Bật',
                'title_en' => 'Featured Categories',
                'limit' => 8,
                'stt' => 3,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'flash_sale',
                'reference_id' => null,
                'title_vn' => 'Sản Phẩm Nổi Bật (Hot Sale)',
                'title_en' => 'Hot Sale Products',
                'limit' => 8,
                'stt' => 4,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'category_products',
                'reference_id' => null,
                'title_vn' => 'Sản Phẩm Theo Danh Mục',
                'title_en' => 'Products by Category',
                'limit' => 8,
                'stt' => 5,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'mid_banner',
                'reference_id' => null,
                'title_vn' => 'Banner Quảng Cáo Giữa Trang',
                'title_en' => 'Mid-Page Banners',
                'limit' => 2,
                'stt' => 6,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'brands',
                'reference_id' => null,
                'title_vn' => 'Thương Hiệu Đối Tác',
                'title_en' => 'Partner Brands',
                'limit' => 12,
                'stt' => 7,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'news',
                'reference_id' => null,
                'title_vn' => 'Tin Tức & Cảm Hứng Sống',
                'title_en' => 'News & Editorial Journal',
                'limit' => 3,
                'stt' => 8,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('tp_home_sections')->insert($defaults);
    }

    public function down(): void
    {
        Schema::dropIfExists('tp_home_sections');
    }
};
