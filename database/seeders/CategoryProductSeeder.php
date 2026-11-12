<?php

namespace Database\Seeders;

use App\Models\CateProduct;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cateRepo = app(\App\Repositories\Interfaces\CateProductRepositoryInterface::class);
        $downloader = app(\App\Services\DemoImageDownloaderService::class);

        $categories = [
            [
                'name_vn' => 'Đồng Hồ Nam',
                'name_en' => "Men's Luxury Watches",
                'slug_vn' => 'dong-ho-nam',
                'keyword_vn' => 'dong ho nam, dong ho nam cao cap',
                'keyword_en' => 'mens watches, luxury watches for men',
                'description_vn' => 'Tuyệt tác đồng hồ nam khẳng định bản lĩnh, vị thế và đẳng cấp phái mạnh.',
                'description_en' => "Masterpieces for gentlemen expressing distinction, status and prestige.",
                'image_keyword' => 'luxury watch men',
                'fallback_image' => 'uploads/demo/watch_men.webp',
                'status' => true,
                'home' => true,
                'stt' => 1,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Đồng Hồ Nữ',
                'name_en' => "Women's Luxury Watches",
                'slug_vn' => 'dong-ho-nu',
                'keyword_vn' => 'dong ho nu, dong ho nu cao cap',
                'keyword_en' => 'womens watches, luxury watches for women',
                'description_vn' => 'Biểu tượng của vẻ đẹp kiêu sa, quyến rũ và thanh lịch thời thượng.',
                'description_en' => 'Embodiment of feminine elegance, glamour and contemporary luxury.',
                'image_keyword' => 'luxury watch women',
                'fallback_image' => 'uploads/demo/watch_women.webp',
                'status' => true,
                'home' => true,
                'stt' => 2,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Đồng Hồ Cơ Automatic',
                'name_en' => 'Automatic Timepieces',
                'slug_vn' => 'dong-ho-co-automatic',
                'keyword_vn' => 'dong ho co, dong ho automatic',
                'keyword_en' => 'automatic watches, mechanical timepieces',
                'description_vn' => 'Cỗ máy thời gian cơ học đỉnh cao từ các nghệ nhân chế tác Thụy Sĩ.',
                'description_en' => 'High horology mechanical movements crafted by Swiss master watchmakers.',
                'image_keyword' => 'luxury watch automatic',
                'fallback_image' => 'uploads/demo/watch_auto.webp',
                'status' => true,
                'home' => true,
                'stt' => 3,
                'parent_id' => 0,
            ],
            [
                'name_vn' => 'Phiên Bản Giới Hạn',
                'name_en' => 'Limited Editions',
                'slug_vn' => 'phien-ban-gioi-han',
                'keyword_vn' => 'dong ho limited, phien ban gioi han',
                'keyword_en' => 'limited edition watches, rare timepieces',
                'description_vn' => 'Những tạo tác thời gian hiếm có, số lượng giới hạn dành cho nhà sưu tầm.',
                'description_en' => 'Rare horological works with numbered editions for prestigious collectors.',
                'image_keyword' => 'luxury watch limited',
                'fallback_image' => 'uploads/demo/watch_limited.webp',
                'status' => true,
                'home' => true,
                'stt' => 4,
                'parent_id' => 0,
            ],
        ];

        foreach ($categories as $cat) {
            if (! CateProduct::where('slug_vn', $cat['slug_vn'])->exists()) {
                $img = $downloader->fetchAndSaveImage($cat['image_keyword'], 600, 600) ?? $cat['fallback_image'];

                $cateRepo->createWithAutoSlug([
                    'name_vn' => $cat['name_vn'],
                    'name_en' => $cat['name_en'],
                    'keyword_vn' => $cat['keyword_vn'],
                    'keyword_en' => $cat['keyword_en'],
                    'description_vn' => $cat['description_vn'],
                    'description_en' => $cat['description_en'],
                    'image_vn' => $img,
                    'image_en' => $img,
                    'status' => $cat['status'],
                    'home' => $cat['home'],
                    'stt' => $cat['stt'],
                    'parent_id' => $cat['parent_id'],
                ]);
            }
        }
    }
}
