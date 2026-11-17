<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About;
use Illuminate\Support\Str;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds for About page.
     */
    public function run(): void
    {
        // Nếu đã có thông tin giới thiệu thì không tạo lại
        if (About::query()->exists()) {
            return;
        }

        About::create([
            'uuid' => Str::uuid()->toString(),
            'name_vn' => 'Về Thương Hiệu Nội Thất Luxury Home',
            'name_en' => 'About Luxury Home Interior',
            'intro_vn' => 'Kiến tạo không gian sống đỉnh cao - Tinh tế trong từng đường nét, bền vững với thời gian.',
            'intro_en' => 'Creating extraordinary living spaces with timeless elegance and uncompromising craftsmanship.',
            'content_vn' => '<p>Luxury Home là tổng thầu thiết kế và sản xuất thi công nội thất cao cấp hàng đầu. Với nhà máy sản xuất quy mô 5.000m² tại Thạch Thất (Hà Nội) trang bị hệ thống máy móc tự động hóa từ Đức và Ý, chúng tôi cam kết mang lại sản phẩm may đo hoàn mỹ, chuẩn xác tới từng milimet.</p>',
            'content_en' => '<p>Luxury Home is a premier design, manufacturing and turnkey fit-out contractor. Operating a 5,000m² manufacturing facility equipped with advanced German and Italian machinery, delivering precision-crafted bespoke furniture.</p>',
            'image' => 'uploads/demo/about_factory.webp',
            'link' => '/lien-he',
            'status' => 1,
            'stt' => 1,
            'stats' => [
                ['icon' => 'fa-solid fa-couch', 'value' => '100%', 'name_vn' => 'Gỗ & Da Nhập Khẩu', 'name_en' => 'Imported Materials'],
                ['icon' => 'fa-solid fa-crown', 'value' => '15+', 'name_vn' => 'Năm Kinh Nghiệm', 'name_en' => 'Years of Excellence'],
                ['icon' => 'fa-solid fa-house-chimney', 'value' => '3.500+', 'name_vn' => 'Dự Án Hoàn Thành', 'name_en' => 'Projects Completed'],
                ['icon' => 'fa-solid fa-shield-halved', 'value' => '5 Năm', 'name_vn' => 'Bảo Hành Chính Hãng', 'name_en' => 'Official Warranty'],
            ]
        ]);
    }
}
