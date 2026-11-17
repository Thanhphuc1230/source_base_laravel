<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds for System Settings.
     */
    public function run(): void
    {
        // Nếu đã có cấu hình hệ thống rồi thì không ghi đè dữ liệu cũ
        if (System::query()->exists()) {
            return;
        }

        $data = [
            'email' => 'contact@luxuryhome.vn',
            'active_languages' => '["vi","en"]',
            'address_vn' => 'Showroom: KĐT Sala, TP. Thủ Đức, TP. Hồ Chí Minh | Nhà máy: Cụm Công nghiệp Thạch Thất, Hà Nội',
            'address_en' => 'Showroom: Sala Urban Area, Thu Duc City, HCMC | Factory: Thach That Industrial Cluster, Hanoi',
            'phone' => '0979 248 298',
            'footer_vn' => 'NỘI THẤT & KIẾN TRÚC LUXURY HOME - Đơn vị thiết kế, sản xuất và thi công nội thất cao cấp chuẩn quốc tế. Sở hữu nhà máy sản xuất trực tiếp 5.000m² với công nghệ tiên tiến nhất, mang đến không gian sống hoàn mỹ và giá trị trường tồn.',
            'footer_en' => 'LUXURY HOME BESPOKE INTERIOR & ARCHITECTURE - International standard luxury interior design, manufacturing and build contractor. Powered by a 5,000m² direct manufacturing plant.',
            'email_alert' => 'contact@luxuryhome.vn',
            'facebook' => 'https://facebook.com/luxuryhome.interior',
            'youtube' => 'https://youtube.com/luxuryhome',
            'twitter' => 'https://twitter.com/luxuryhome',
            'instagram' => 'https://instagram.com/luxuryhome',
            'zalo' => 'https://zalo.me/0979248298',
            'favicon' => 'uploads/demo/interior_logo.svg',
            'logo' => 'uploads/demo/interior_logo.svg',
            'name_vn' => 'NỘI THẤT & KIẾN TRÚC LUXURY HOME',
            'name_en' => 'LUXURY HOME INTERIOR & ARCHITECTURE',
            'description_vn' => 'NỘI THẤT & KIẾN TRÚC LUXURY HOME - Thiết Kế & Thi Công Nội Thất Trọn Gói Cao Cấp',
            'description_en' => 'LUXURY HOME INTERIOR & ARCHITECTURE - Premium Bespoke Interior Design & Turnkey Fit-out',
            'keyword_vn' => 'noi that cao cap, thiet ke noi that, thi cong noi that, noi that phong khach, noi that phong ngu, go an cuong',
            'keyword_en' => 'luxury interior, interior design, bespoke furniture, living room, bedroom furniture',
        ];

        System::create($data);
    }
}
