<?php

namespace Database\Seeders;

use App\Models\CateProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds for Interior Categories.
     */
    public function run(): void
    {
        // Nếu đã có danh mục sản phẩm trong CSDL thì không tạo lại
        if (CateProduct::query()->exists()) {
            return;
        }

        $cateRepo = app(\App\Repositories\Interfaces\CateProductRepositoryInterface::class);

        $categories = [
            [
                'name_vn' => 'Nội Thất Phòng Khách',
                'name_en' => 'Living Room Furniture',
                'slug_vn' => 'noi-that-phong-khach',
                'keyword_vn' => 'sofa da, ban tra, ke tivi, phong khach hien dai',
                'keyword_en' => 'leather sofa, coffee table, tv stand, living room',
                'description_vn' => 'Bộ sưu tập sofa da cao cấp, bàn trà mặt đá và kệ tivi mang phong cách Modern Luxury.',
                'description_en' => 'Premium Italian leather sofas, marble coffee tables and modern luxury TV consoles.',
                'image_vn' => 'uploads/demo/cat_living.webp',
                'image_en' => 'uploads/demo/cat_living.webp',
                'status' => true,
                'home' => true,
                'stt' => 1,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Nội Thất Phòng Ngủ',
                'name_en' => 'Bedroom Furniture',
                'slug_vn' => 'noi-that-phong-ngu',
                'keyword_vn' => 'giuong ngu go soi, tu quan ao canh kinh, ban trang diem',
                'keyword_en' => 'wooden bed, glass wardrobe, vanity dressing table',
                'description_vn' => 'Không gian phòng ngủ thư thái với giường ngủ gỗ tự nhiên bọc nệm và tủ áo cánh kính.',
                'description_en' => 'Restful bedroom sanctuary with solid wood upholstered beds and luxury glass wardrobes.',
                'image_vn' => 'uploads/demo/cat_bed.webp',
                'image_en' => 'uploads/demo/cat_bed.webp',
                'status' => true,
                'home' => true,
                'stt' => 2,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Phòng Bếp & Bàn Ăn',
                'name_en' => 'Dining & Kitchen',
                'slug_vn' => 'phong-bep-ban-an',
                'keyword_vn' => 'ban an mat da ceramic, ghe an boc da, tu bep an cuong',
                'keyword_en' => 'ceramic dining table, dining chairs, modern kitchen cabinets',
                'description_vn' => 'Bộ bàn ăn mặt đá Ceramic chống trầy 6 - 8 ghế và hệ tủ bếp Acrylic An Cường đẳng cấp.',
                'description_en' => 'Scratch-resistant ceramic dining tables with 6-8 leather chairs and luxury kitchen cabinets.',
                'image_vn' => 'uploads/demo/cat_dining.webp',
                'image_en' => 'uploads/demo/cat_dining.webp',
                'status' => true,
                'home' => true,
                'stt' => 3,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Phòng Làm Việc & Văn Phòng',
                'name_en' => 'Home Office & Study',
                'slug_vn' => 'phong-lam-viec',
                'keyword_vn' => 'ban lam viec go oc cho, ghe giam doc, ke sach',
                'keyword_en' => 'walnut desk, executive chair, modern bookcase',
                'description_vn' => 'Bàn làm việc gỗ Óc Chó Bắc Mỹ và ghế da công thái học kiến tạo không gian làm việc chuyên nghiệp.',
                'description_en' => 'American walnut desks and ergonomic executive leather chairs creating inspiring workspaces.',
                'image_vn' => 'uploads/demo/cat_office.webp',
                'image_en' => 'uploads/demo/cat_office.webp',
                'status' => true,
                'home' => true,
                'stt' => 4,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Đồ Trang Trí & Decor',
                'name_en' => 'Home Decor & Lighting',
                'slug_vn' => 'decor-trang-tri',
                'keyword_vn' => 'den chum pha le, tham trai san, binh hoa decor',
                'keyword_en' => 'crystal chandelier, luxury rug, artistic decor vases',
                'description_vn' => 'Đèn chùm pha lê nghệ thuật, thảm dệt tay và các phụ kiện decor tạo điểm nhấn hoàn mỹ.',
                'description_en' => 'Artistic crystal chandeliers, handwoven rugs and luxury home decor accents.',
                'image_vn' => 'uploads/demo/cat_decor.webp',
                'image_en' => 'uploads/demo/cat_decor.webp',
                'status' => true,
                'home' => true,
                'stt' => 5,
                'parent_id' => 0,
            ],
        ];

        foreach ($categories as $cat) {
            $cateRepo->createWithAutoSlug($cat);
        }
    }
}
