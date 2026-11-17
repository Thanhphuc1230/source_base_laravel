<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds for Service Features.
     */
    public function run(): void
    {
        // Nếu đã có đặc điểm nổi bật thì không tạo lại
        if (Feature::query()->exists()) {
            return;
        }

        $features = [
            [
                'title_vn' => 'Thiết Kế May Đo Độc Bản',
                'title_en' => 'Bespoke Customized Design',
                'content_vn' => 'Đội ngũ kiến trúc sư giàu kinh nghiệm thiết kế chuẩn phong thủy và phong cách cá nhân.',
                'content_en' => 'Experienced architects tailoring spaces to your lifestyle and aesthetic preferences.',
                'image' => 'fa-solid fa-compass-drafting',
                'status' => 1,
                'stt' => 1,
            ],
            [
                'title_vn' => 'Gỗ & Da Nhập Khẩu 100%',
                'title_en' => '100% Certified Materials',
                'content_vn' => 'Cam kết vật liệu chuẩn An Cường, da bò Ý tự nhiên, phụ kiện Hafele & Blum chính hãng.',
                'content_en' => 'Guaranteed genuine An Cuong boards, Italian leather, and Hafele hardware.',
                'image' => 'fa-solid fa-award',
                'status' => 1,
                'stt' => 2,
            ],
            [
                'title_vn' => 'Nhà Máy Sản Xuất 5.000m²',
                'title_en' => 'Direct Factory Manufacturing',
                'content_vn' => 'Sản xuất trực tiếp tại xưởng với máy móc CNC tự động hóa, tiết kiệm 20% chi phí trung gian.',
                'content_en' => 'Direct production at our 5,000m² facility saves 20% compared to retail intermediaries.',
                'image' => 'fa-solid fa-industry',
                'status' => 1,
                'stt' => 3,
            ],
            [
                'title_vn' => 'Bảo Hành 5 Năm Uy Tín',
                'title_en' => '5-Year Warranty & Support',
                'content_vn' => 'Bảo hành kết cấu 5 năm, hỗ trợ bảo dưỡng và bảo trì định kỳ trọn đời.',
                'content_en' => '5-year structural warranty with lifetime maintenance support.',
                'image' => 'fa-solid fa-shield-halved',
                'status' => 1,
                'stt' => 4,
            ],
        ];

        foreach ($features as $f) {
            Feature::create(array_merge($f, [
                'uuid' => Str::uuid()->toString()
            ]));
        }
    }
}
