<?php

namespace Database\Seeders;

use App\Models\CateProduct;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds for Furniture Products.
     */
    public function run(): void
    {
        // Nếu đã có sản phẩm thì không ghi đè dữ liệu cũ
        if (Product::query()->exists()) {
            return;
        }

        $productRepo = app(\App\Repositories\Interfaces\ProductRepositoryInterface::class);

        $catLiving = CateProduct::where('slug', 'LIKE', '%phong-khach%')->value('id_cate_product') ?? 1;
        $catBed = CateProduct::where('slug', 'LIKE', '%phong-ngu%')->value('id_cate_product') ?? 2;
        $catDining = CateProduct::where('slug', 'LIKE', '%bep%')->orWhere('slug', 'LIKE', '%ban-an%')->value('id_cate_product') ?? 3;
        $catOffice = CateProduct::where('slug', 'LIKE', '%lam-viec%')->value('id_cate_product') ?? 4;
        $catDecor = CateProduct::where('slug', 'LIKE', '%decor%')->value('id_cate_product') ?? 5;

        $brand1 = Brand::value('id_brand') ?? 1;
        $brand2 = Brand::skip(1)->value('id_brand') ?? $brand1;
        $brand3 = Brand::skip(2)->value('id_brand') ?? $brand1;

        $products = [
            // 1. Sofa Văng Da Bò Ý
            [
                'name_vn' => 'Sofa Văng Da Bò Ý Cao Cấp Monaco',
                'name_en' => 'Monaco Luxury Italian Leather Sofa',
                'slug_vn' => 'sofa-vang-da-bo-y-cao-cap-monaco',
                'intro_vn' => 'Thiết kế chuẩn phong cách Bắc Âu hiện đại, bọc da bò thật nhập khẩu Ý mềm mịn và đệm mút D40 êm ái.',
                'intro_en' => 'Modern Scandinavian elegance upholstered in genuine Italian leather with plush D40 high-density foam.',
                'price' => 28500000,
                'price_old' => 32000000,
                'quantity' => 20,
                'content_vn' => '<p>Sofa Văng Da Bò Monaco là sự kết tinh giữa tay nghề thủ công điêu luyện và chất liệu da bò tự nhiên cao cấp từ Ý. Khung gỗ sồi đã qua tẩm sấy chống mối mọt cong vênh, hệ lò xo túi độc lập mang lại độ đàn hồi tối đa và sự thư giãn tuyệt đối cho gia chủ.</p>',
                'content_en' => '<p>Monaco Leather Sofa exemplifies masterful craftsmanship paired with authentic Italian full-grain leather. Built on kiln-dried solid oak frame with pocket coil suspension for ultimate comfort.</p>',
                'keyword_vn' => 'sofa da y, sofa vang phong khach, sofa cao cap',
                'keyword_en' => 'italian leather sofa, modern sofa, living room furniture',
                'category_id' => $catLiving,
                'brand_id' => $brand1,
                'image_vn' => 'uploads/demo/sofa_monaco.webp',
                'image_en' => 'uploads/demo/sofa_monaco.webp',
                'hot' => true,
                'home' => true,
                'status' => true,
                'stt' => 1,
            ],
            // 2. Giường Ngủ Master Gỗ Sồi
            [
                'name_vn' => 'Giường Ngủ Master Gỗ Sồi Nga Tự Nhiên Scandinavia',
                'name_en' => 'Scandinavia Master Solid Oak Bed',
                'slug_vn' => 'giuong-ngu-master-go-soi-nga-scandinavia',
                'intro_vn' => 'Gỗ sồi Nga nguyên khối tuyển chọn vân tự nhiên tuyệt đẹp, đầu giường bọc da Microfiber thanh lịch.',
                'intro_en' => 'Handcrafted from solid Russian white oak featuring natural grain patterns and upholstered headboard.',
                'price' => 22000000,
                'price_old' => 25500000,
                'quantity' => 15,
                'content_vn' => '<p>Giường ngủ Master Scandinavia được gia công 100% từ gỗ sồi Nga tự nhiên loại 1. Kết cấu mộng âm dương truyền thống chịu tải trọng tới 800kg. Đầu giường bọc đệm da cao cấp giúp tựa lưng đọc sách thoải mái mỗi tối.</p>',
                'content_en' => '<p>Scandinavia Master Bed is crafted from 100% natural grade-A Russian white oak. Mortise and tenon joinery supports up to 800kg, paired with a luxurious padded headboard for reading comfort.</p>',
                'keyword_vn' => 'giuong ngu go soi, giuong master, giuong ngu hien dai',
                'keyword_en' => 'solid oak bed, master bedroom bed, modern wooden bed',
                'category_id' => $catBed,
                'brand_id' => $brand2,
                'image_vn' => 'uploads/demo/bed_scandinavia.webp',
                'image_en' => 'uploads/demo/bed_scandinavia.webp',
                'hot' => true,
                'home' => true,
                'status' => true,
                'stt' => 2,
            ],
            // 3. Bộ Bàn Ăn 6 Ghế Ceramic
            [
                'name_vn' => 'Bộ Bàn Ăn 6 Ghế Mặt Đá Ceramic Chống Xước Milano',
                'name_en' => 'Milano 6-Seater Ceramic Dining Table Set',
                'slug_vn' => 'bo-ban-an-6-ghe-mat-da-ceramic-milano',
                'intro_vn' => 'Mặt đá phiến Ceramic nhiệt độ cao chống trầy xước, chịu nhiệt 1200 độ C cùng chân sắt sơn tĩnh điện vuốt côn.',
                'intro_en' => 'Heat and scratch-resistant ceramic stone tabletop with elegant tapered black powder-coated steel legs.',
                'price' => 18500000,
                'price_old' => 21000000,
                'quantity' => 25,
                'content_vn' => '<p>Bộ bàn ăn Milano gồm 1 bàn mặt đá Ceramic kích thước 1m6 và 6 ghế ăn bọc da PU cao cấp. Mặt đá dày 12mm chịu lực va đập vượt trội, không thấm ố dầu mỡ, dễ dàng lau chùi chỉ với khăn ẩm.</p>',
                'content_en' => '<p>Milano dining set includes a 1.6m ceramic tabletop table and 6 upholstered PU leather chairs. 12mm thick stone surface resists heat, scratches and oil stains effortlessly.</p>',
                'keyword_vn' => 'ban an mat da, bo ban an 6 ghe, ban an ceramic',
                'keyword_en' => 'ceramic dining table, 6 chair dining set, modern dining table',
                'category_id' => $catDining,
                'brand_id' => $brand3,
                'hot' => true,
                'home' => true,
                'status' => true,
                'stt' => 3,
            ],
            // 4. Tủ Quần Áo Cánh Kính
            [
                'name_vn' => 'Tủ Quần Áo Cánh Kính Cao Cấp Hiện Đại Grand View',
                'name_en' => 'Grand View Luxury Glass Door Wardrobe',
                'slug_vn' => 'tu-quan-ao-canh-kinh-hien-dai-grand-view',
                'intro_vn' => 'Khung nhôm định hình mạ Anode xám khói, kính cường lực màu trà sang trọng tích hợp đèn LED cảm ứng.',
                'intro_en' => 'Smoked anodized aluminum frames with tempered bronze glass and integrated smart sensor LED lighting.',
                'price' => 35000000,
                'price_old' => 39500000,
                'quantity' => 10,
                'content_vn' => '<p>Tủ áo cánh kính Grand View là điểm nhấn xa hoa cho phòng ngủ Master. Thùng tủ gỗ MDF chống ẩm An Cường phủ Melamine, hệ cánh kính cường lực 5mm viền nhôm siêu mảnh cùng ray trượt giảm chấn êm ái.</p>',
                'content_en' => '<p>Grand View glass wardrobe brings boutique hotel luxury home. Moisture-resistant An Cuong MDF carcass, 5mm bronze tempered glass with ultra-slim aluminum profiles and soft-close hardware.</p>',
                'keyword_vn' => 'tu quan ao canh kinh, tu ao led, tu ao hien dai',
                'keyword_en' => 'glass wardrobe, luxury closet, modern wardrobe',
                'category_id' => $catBed,
                'brand_id' => $brand1,
                'image_vn' => 'uploads/demo/wardrobe_glass.webp',
                'image_en' => 'uploads/demo/wardrobe_glass.webp',
                'hot' => false,
                'home' => true,
                'status' => true,
                'stt' => 4,
            ],
            // 5. Bàn Trà Đôi Mặt Đá Marble
            [
                'name_vn' => 'Bàn Trà Đôi Mặt Đá Marble Cẩm Thạch Khung Inox PVD',
                'name_en' => 'Marble Dual Nesting Coffee Table with Gold PVD Frame',
                'slug_vn' => 'ban-tra-doi-mat-da-marble-khung-inox-pvd',
                'intro_vn' => 'Thiết kế lồng đôi thông minh tiết kiệm diện tích, mặt đá Marble vân mây tự nhiên và khung inox mạ vàng PVD.',
                'intro_en' => 'Smart nesting double table with natural cloud-vein marble top and luxury gold PVD stainless steel frame.',
                'price' => 8500000,
                'price_old' => 9800000,
                'quantity' => 30,
                'content_vn' => '<p>Bàn trà đôi gồm 1 bàn lớn đường kính 70cm và 1 bàn nhỏ 50cm có thể lồng gọn gàng khi cần không gian sinh hoạt. Khung inox 304 xi mạ chân không PVD chống rỉ sét, bền màu trọn đời.</p>',
                'content_en' => '<p>Includes 70cm large table and 50cm smaller nesting table. Heavy-duty 304 stainless steel with vacuum PVD titanium coating that never tarnishes or fades.</p>',
                'keyword_vn' => 'ban tra doi, ban tra mat da, ban tra inox pvd',
                'keyword_en' => 'nesting coffee table, marble coffee table, gold coffee table',
                'category_id' => $catLiving,
                'brand_id' => $brand2,
                'image_vn' => 'uploads/demo/table_marble.webp',
                'image_en' => 'uploads/demo/table_marble.webp',
                'hot' => false,
                'home' => true,
                'status' => true,
                'stt' => 5,
            ],
            // 6. Bàn Làm Việc Giám Đốc Gỗ Óc Chó
            [
                'name_vn' => 'Bàn Làm Việc Giám Đốc Gỗ Óc Chó Bắc Mỹ President',
                'name_en' => 'President American Walnut Executive Desk',
                'slug_vn' => 'ban-lam-viec-giam-doc-go-oc-cho-president',
                'intro_vn' => 'Đẳng cấp lãnh đạo với gỗ óc chó Bắc Mỹ vân cuộn sóng tự nhiên, hộc tủ đa năng tích hợp khóa số thông minh.',
                'intro_en' => 'Prestige executive desk in American black walnut with natural swirl grain and smart digital security drawers.',
                'price' => 38000000,
                'price_old' => 42000000,
                'quantity' => 8,
                'content_vn' => '<p>Bàn làm việc President dài 2m2 được chế tác công phu từ gỗ óc chó Bắc Mỹ nhập khẩu. Tích hợp sạc không dây, cổng kết nối ẩn và hệ ray trượt Hafele cao cấp.</p>',
                'content_en' => '<p>Crafted in 2.2m length from imported North American walnut. Features integrated wireless charging pads and Hafele German soft-close runner systems.</p>',
                'keyword_vn' => 'ban lam viec go oc cho, ban giam doc cao cap',
                'keyword_en' => 'walnut executive desk, luxury office desk',
                'category_id' => $catOffice,
                'brand_id' => $brand3,
                'image_vn' => 'uploads/demo/desk_walnut.webp',
                'image_en' => 'uploads/demo/desk_walnut.webp',
                'hot' => false,
                'home' => false,
                'status' => true,
                'stt' => 6,
            ],
        ];

        foreach ($products as $prod) {
            if (Product::where('slug', 'LIKE', '%' . ($prod['slug_vn'] ?? '') . '%')->exists()) {
                continue;
            }
            $productRepo->createWithAutoSlug($prod);
        }
    }
}
