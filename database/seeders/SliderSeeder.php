<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use Illuminate\Support\Str;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds for Sliders.
     */
    public function run(): void
    {
        // Nếu đã có slider trong CSDL thì không tạo lại
        if (Slider::query()->exists()) {
            return;
        }

        $sliders = [
            [
                'name_vn' => 'Kiệt Tác Không Gian Sống Đẳng Cấp 2026',
                'name_en' => 'Luxury Living Space Masterpieces 2026',
                'link' => '/san-pham',
                'status' => 1,
                'image_desktop_vn' => 'uploads/demo/interior_banner_1.webp',
                'image_desktop_en' => 'uploads/demo/interior_banner_1.webp',
                'image_mobile_vn' => 'uploads/demo/interior_banner_1.webp',
                'image_mobile_en' => 'uploads/demo/interior_banner_1.webp',
                'stt' => 1,
            ],
            [
                'name_vn' => 'Thiết Kế & Thi Công Nội Thất Trọn Gói Chuẩn Châu Âu',
                'name_en' => 'European Standard Turnkey Interior Fit-Out',
                'link' => '/gioi-thieu',
                'status' => 1,
                'image_desktop_vn' => 'uploads/demo/interior_banner_2.webp',
                'image_desktop_en' => 'uploads/demo/interior_banner_2.webp',
                'image_mobile_vn' => 'uploads/demo/interior_banner_2.webp',
                'image_mobile_en' => 'uploads/demo/interior_banner_2.webp',
                'stt' => 2,
            ],
            [
                'name_vn' => 'Bộ Sưu Tập Sofa Da & Bàn Ăn Mặt Đá Ceramic Độc Bản',
                'name_en' => 'Exclusive Italian Leather Sofa & Ceramic Dining Sets',
                'link' => '/san-pham',
                'status' => 1,
                'image_desktop_vn' => 'uploads/demo/interior_banner_3.webp',
                'image_desktop_en' => 'uploads/demo/interior_banner_3.webp',
                'image_mobile_vn' => 'uploads/demo/interior_banner_3.webp',
                'image_mobile_en' => 'uploads/demo/interior_banner_3.webp',
                'stt' => 3,
            ],
        ];

        foreach ($sliders as $slide) {
            Slider::create(array_merge($slide, [
                'uuid' => Str::uuid()->toString()
            ]));
        }
    }
}
