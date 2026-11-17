<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds for Strategic Partners / Suppliers.
     */
    public function run(): void
    {
        if (Partner::query()->exists()) {
            return;
        }

        $partners = [
            [
                'name_vn' => 'Gỗ An Cường',
                'name_en' => 'An Cuong Wood',
                'slug_vn' => 'go-an-cuong',
                'slug_en' => 'an-cuong-wood',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 1,
            ],
            [
                'name_vn' => 'Gỗ Minh Long',
                'name_en' => 'Minh Long Wood',
                'slug_vn' => 'go-minh-long',
                'slug_en' => 'minh-long-wood',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 2,
            ],
            [
                'name_vn' => 'Đá Thạch Anh Vicostone',
                'name_en' => 'Vicostone Quartz',
                'slug_vn' => 'vicostone',
                'slug_en' => 'vicostone-quartz',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 3,
            ],
            [
                'name_vn' => 'Phụ Kiện Blum Áo',
                'name_en' => 'Blum Austria',
                'slug_vn' => 'blum',
                'slug_en' => 'blum-austria',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 4,
            ],
            [
                'name_vn' => 'Phụ Kiện & Thiết Bị Hafele Đức',
                'name_en' => 'Hafele Germany',
                'slug_vn' => 'hafele',
                'slug_en' => 'hafele-germany',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 5,
            ],
            [
                'name_vn' => 'Thiết Bị Điện Schneider Electric',
                'name_en' => 'Schneider Electric France',
                'slug_vn' => 'schneider-electric',
                'slug_en' => 'schneider-electric-france',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 6,
            ],
            [
                'name_vn' => 'Sơn Dulux Professional',
                'name_en' => 'Dulux Professional',
                'slug_vn' => 'dulux',
                'slug_en' => 'dulux-professional',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 7,
            ],
            [
                'name_vn' => 'Thiết Bị Vệ Sinh TOTO Nhật Bản',
                'name_en' => 'TOTO Japan',
                'slug_vn' => 'toto',
                'slug_en' => 'toto-japan',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 8,
            ],
            [
                'name_vn' => 'Gạch Men Eurotile',
                'name_en' => 'Eurotile Ceramics',
                'slug_vn' => 'eurotile',
                'slug_en' => 'eurotile-ceramics',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 9,
            ],
            [
                'name_vn' => 'Gạch Đồng Tâm',
                'name_en' => 'Dong Tam Group',
                'slug_vn' => 'dong-tam',
                'slug_en' => 'dong-tam-group',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 10,
            ],
        ];

        Partner::query()->delete();

        foreach ($partners as $partner) {
            Partner::create(array_merge($partner, ['uuid' => Str::uuid()->toString()]));
        }
    }
}
