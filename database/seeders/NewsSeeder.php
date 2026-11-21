<?php

namespace Database\Seeders;

use App\Models\CateNew;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds for Interior News & Articles.
     */
    public function run(): void
    {
        // Nếu đã có bài viết tin tức thì không tạo lại
        if (News::query()->exists()) {
            return;
        }

        $newsRepo = app(\App\Repositories\Interfaces\NewsRepositoryInterface::class);
        $categoryId = CateNew::value('id_cate_new') ?? 1;

        $articles = [
            [
                'name_vn' => 'Xu Hướng Thiết Kế Nội Thất Hiện Đại Sang Trọng (Modern Luxury) Năm 2026',
                'name_en' => 'Modern Luxury Interior Design Trends for 2026',
                'slug_vn' => 'xu-huong-thiet-ke-noi-that-hien-dai-sang-trong-2026',
                'intro_vn' => 'Khám phá sự giao thoa tinh tế giữa vật liệu đá tự nhiên, kim loại mạ PVD và gỗ óc chó kiến tạo không gian sống đỉnh cao.',
                'intro_en' => 'Explore the sophisticated harmony of natural stone, PVD metallic accents and rich walnut wood.',
                'content_vn' => '<p>Phong cách Modern Luxury năm 2026 tập trung vào trải nghiệm cảm xúc và sự tối giản tinh tế. Không gian rộng mở, trần cao kết hợp ánh sáng hắt gián tiếp và những món đồ nội thất may đo theo kích thước riêng tạo nên đẳng cấp độc bản cho gia chủ.</p>',
                'content_en' => '<p>Modern Luxury emphasizes emotional wellbeing and refined minimalism. Open layouts, high ceilings paired with indirect ambient lighting create unmatched elegance.</p>',
                'keyword_vn' => 'xu huong noi that 2026, modern luxury, thiet ke noi that cao cap',
                'keyword_en' => 'interior trends 2026, modern luxury, luxury interior design',
                'image_vn' => 'uploads/demo/news_interior_1.webp',
                'image_en' => 'uploads/demo/news_interior_1.webp',
                'category_id' => $categoryId,
                'status' => true,
                'home' => true,
                'stt' => 1,
            ],
            [
                'name_vn' => 'Kinh Nghiệm Chọn Gỗ Tự Nhiên & Gỗ Công Nghiệp An Cường Chuẩn Bền Đẹp',
                'name_en' => 'Essential Guide to Choosing Solid Wood vs An Cuong Engineered Wood',
                'slug_vn' => 'kinh-nghiem-chon-go-tu-nhien-va-go-an-cuong',
                'intro_vn' => 'So sánh chi tiết ưu nhược điểm, độ bền và mức chi phí giữa gỗ sồi, gỗ óc chó và gỗ MDF lõi xanh chống ẩm.',
                'intro_en' => 'Detailed comparison on durability, aesthetics and cost efficiency between solid oak, walnut and MDF.',
                'content_vn' => '<p>Gỗ tự nhiên mang vẻ đẹp độc bản của các đường vân và độ bền hàng chục năm, rất thích hợp cho sofa, bàn ăn và giường ngủ. Trong khi đó, gỗ công nghiệp MDF chống ẩm An Cường phủ Melamine hoặc Acrylic lại là lựa chọn số 1 cho hệ tủ bếp, tủ quần áo phẳng hiện đại với chi phí tối ưu.</p>',
                'content_en' => '<p>Solid wood provides organic beauty and generational longevity ideal for dining sets and beds. Moisture-resistant MDF with Melamine is the premier choice for modern built-in cabinetry.</p>',
                'keyword_vn' => 'go an cuong, go soi nga, go oc cho, kinh nghiem lam noi that',
                'keyword_en' => 'an cuong wood, solid oak, walnut wood, furniture materials',
                'image_vn' => 'uploads/demo/news_interior_2.webp',
                'image_en' => 'uploads/demo/news_interior_2.webp',
                'category_id' => $categoryId,
                'status' => true,
                'home' => true,
                'stt' => 2,
            ],
            [
                'name_vn' => 'Bí Quyết Phối Màu & Bố Trí Ánh Sáng Cho Phòng Khách Rộng Rãi, Sang Trọng',
                'name_en' => 'Secrets to Color Palette & Architectural Lighting for Living Rooms',
                'slug_vn' => 'bi-quyet-phoi-mau-va-anh-sang-phong-khach',
                'intro_vn' => 'Áp dụng quy tắc màu 60-30-10 và hệ thống chiếu sáng 3 lớp giúp không gian phòng khách bừng sáng và ấm cúng.',
                'intro_en' => 'Applying the 60-30-10 color rule and 3-tier lighting architecture to elevate living rooms.',
                'content_vn' => '<p>Ánh sáng là linh hồn của kiến trúc nội thất. Kết hợp hài hòa giữa ánh sáng tổng thể (ambient), ánh sáng chức năng (task) và ánh sáng điểm nhấn (accent) sẽ làm nổi bật kết cấu bề mặt của đá marble, da thật và từng đường vân gỗ.</p>',
                'content_en' => '<p>Lighting is the soul of interior architecture. A balanced blend of ambient, task and accent lighting accentuates textures of marble, leather and fine wood grains.</p>',
                'keyword_vn' => 'chieu sang noi that, phoi mau phong khach, anh sang noi that',
                'keyword_en' => 'living room lighting, interior color palette, architecture light',
                'image_vn' => 'uploads/demo/news_interior_3.webp',
                'image_en' => 'uploads/demo/news_interior_3.webp',
                'category_id' => $categoryId,
                'status' => true,
                'home' => false,
                'stt' => 3,
            ],
        ];

        foreach ($articles as $art) {
            if (News::where('slug_vn', $art['slug_vn'])->exists()) {
                continue;
            }
            $newsRepo->createWithAutoSlug($art);
        }
    }
}
