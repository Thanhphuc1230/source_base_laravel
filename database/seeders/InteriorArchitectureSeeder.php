<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\System;
use App\Models\About;
use App\Models\Slider;
use App\Models\Feature;
use App\Models\CateProject;
use App\Models\Project;
use App\Models\CateProduct;
use App\Models\Product;
use App\Models\CateNew;
use App\Models\News;
use App\Models\Menu;

class InteriorArchitectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // =========================================================================
        // 1. CẬP NHẬT HỆ THỐNG (tp_systems) - ZERO HARDCODE BASE BRANDING
        // =========================================================================
        DB::table('tp_systems')->truncate();
        DB::table('tp_systems')->insert([
            'email' => 'contact@base.local',
            'active_languages' => '["vi","en"]',
            'address_vn' => 'Showroom: KĐT Sala, TP. Thủ Đức, TP. Hồ Chí Minh | Nhà máy: Cụm Công nghiệp Thạch Thất, Hà Nội',
            'address_en' => 'Showroom: Sala Urban Area, Thu Duc City, HCMC | Factory: Thach That Industrial Cluster, Hanoi',
            'phone' => '0979 248 298',
            'footer_vn' => 'NỘI THẤT & KIẾN TRÚC BASE - Đơn vị thiết kế, sản xuất và thi công nội thất cao cấp chuẩn quốc tế. Sở hữu nhà máy sản xuất trực tiếp 5.000m² với công nghệ tiên tiến nhất, mang đến không gian sống hoàn mỹ và giá trị trường tồn.',
            'footer_en' => 'BASE BESPOKE INTERIOR & ARCHITECTURE - International standard luxury interior design, manufacturing and build contractor. Powered by a 5,000m² direct manufacturing plant.',
            'email_alert' => 'contact@base.local',
            'facebook' => 'https://facebook.com',
            'youtube' => 'https://youtube.com',
            'twitter' => 'https://twitter.com',
            'instagram' => 'https://instagram.com',
            'zalo' => 'https://zalo.me/0979248298',
            'favicon' => 'uploads/demo/interior_logo.svg',
            'logo' => 'uploads/demo/interior_logo.svg',
            'name_vn' => 'NỘI THẤT & KIẾN TRÚC BASE',
            'name_en' => 'BASE BESPOKE INTERIOR & ARCHITECTURE',
            'description_vn' => 'Nội Thất & Kiến Trúc Base - Thiết Kế & Thi Công Nội Thất Biệt Thự, Villa, Dinh Thự Cao Cấp. Trực tiếp sản xuất tại nhà xưởng quy mô lớn.',
            'description_en' => 'BASE INTERIOR & ARCHITECTURE - Bespoke Living & 5,000sqm Factory',
            'keyword_vn' => 'noi that cao cap, thiet ke thi cong villa, dinh thu, nha xuong noi that, phong cach becki owens, studio mcgee',
            'keyword_en' => 'bespoke interior, luxury architecture, walnut furniture, factory 5000sqm',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // =========================================================================
        // 2. NĂNG LỰC NHÀ MÁY (tp_abouts) - UUID: 8037faa4-c262-41d7-aed5-60a479531b4f
        // =========================================================================
        DB::table('tp_abouts')->truncate();

        About::create([
            'uuid' => '8037faa4-c262-41d7-aed5-60a479531b4f',
            'name_vn' => 'Chủ động chất lượng từ xưởng mộc đến từng chi tiết hoàn thiện.',
            'name_en' => 'Mastering Quality from Wood Workshop to Every Finishing Detail.',
            'intro_vn' => 'NĂNG LỰC SẢN XUẤT',
            'intro_en' => 'MANUFACTURING CAPACITY',
            'content_vn' => '<p>Sở hữu nhà xưởng quy mô lớn cùng trung tâm gia công CNC 5 trục và buồng phun sơn sấy áp lực dương hiện đại, NỘI THẤT & KIẾN TRÚC BASE cam kết đưa ra mức giá gốc không qua trung gian, tiết kiệm tới 30% ngân sách và kiểm soát tiến độ hoàn hảo.</p>',
            'content_en' => '<p>Operating an advanced 5,000m² manufacturing plant equipped with cutting-edge European CNC machinery, we ensure millimeter accuracy and supreme finishing across North American Walnut and natural hardwoods.</p>',
            'image' => 'uploads/demo/factory_facility.webp',
            'link' => '/nha-xuong',
            'status' => 1,
            'stt' => 1,
            'stats' => [
                [
                    'icon' => 'fa-solid fa-microchip',
                    'value' => 'CNC 5 Trục',
                    'name_vn' => 'Dây chuyền CNC 5 trục',
                    'name_en' => '5-Axis CNC Line',
                    'description_vn' => 'Độ chính xác milimet, tạo hình chi tiết uốn cong phức tạp.',
                    'description_en' => 'Millimeter precision for complex curved designs.'
                ],
                [
                    'icon' => 'fa-solid fa-spray-can',
                    'value' => 'Sơn Sấy Chuẩn',
                    'name_vn' => 'Buồng sơn sấy chuẩn',
                    'name_en' => 'Standard Spraying Booth',
                    'description_vn' => 'Bề mặt mịn như lụa, lớp sơn bóng kháng ố và chống trầy xước.',
                    'description_en' => 'Silk-smooth finish, stain-resistant and scratch-resistant coating.'
                ],
                [
                    'icon' => 'fa-solid fa-industry',
                    'value' => '5.000 m²',
                    'name_vn' => 'Diện tích nhà máy quy chuẩn',
                    'name_en' => 'Factory Floor Area'
                ],
                [
                    'icon' => 'fa-solid fa-couch',
                    'value' => 'Gỗ Tự Nhiên & Óc Chó',
                    'name_vn' => 'Nhập khẩu Bắc Mỹ loại 1',
                    'name_en' => 'Grade 1 Natural Woods'
                ],
            ]
        ]);

        // =========================================================================
        // 3. ĐẶC QUYỀN / TÍNH NĂNG (tp_features)
        // =========================================================================
        DB::table('tp_features')->truncate();
        $features = [
            [
                'title_vn' => 'Thiết Kế May Đo Độc Bản',
                'title_en' => 'Bespoke Architectural Design',
                'content_vn' => 'Từng đường nét, tỷ lệ được kiến trúc sư thiết kế cá nhân hóa 100% theo phong cách gia chủ.',
                'content_en' => 'Every proportion and detail tailored exclusively to your personal lifestyle.',
                'image' => 'fa-solid fa-compass-drafting',
                'stt' => 1,
                'status' => 1,
                'uuid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_vn' => 'Nhà Máy 5.000m² Trực Tiếp',
                'title_en' => 'Direct 5,000m² Factory',
                'content_vn' => 'Sản xuất trực tiếp không qua trung gian, bảo đảm tiến độ và kiểm soát chất lượng nghiêm ngặt.',
                'content_en' => 'Direct production with no middlemen, ensuring strict quality control and timeline.',
                'image' => 'fa-solid fa-industry',
                'stt' => 2,
                'status' => 1,
                'uuid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_vn' => 'Gỗ Tự Nhiên & Da Bò Ý Loại 1',
                'title_en' => 'FAS Grade Woods & Italian Leather',
                'content_vn' => 'Gỗ Óc chó Bắc Mỹ tuyển chọn vân nu mắt đẹp, da bò Ý Nappa mềm mại thượng hạng.',
                'content_en' => 'Finest North American Walnut grains paired with supple Italian full-grain leather.',
                'image' => 'fa-solid fa-couch',
                'stt' => 3,
                'status' => 1,
                'uuid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_vn' => 'Bảo Hành 5 Năm & Bảo Trì Trọn Đời',
                'title_en' => '5-Year Warranty & Lifetime Care',
                'content_vn' => 'Cam kết chất lượng vững chắc, đội ngũ bảo hành có mặt xử lý trong 24h tại công trình.',
                'content_en' => 'Long-term commitment with on-site technical maintenance within 24 hours.',
                'image' => 'fa-solid fa-shield-halved',
                'stt' => 4,
                'status' => 1,
                'uuid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('tp_features')->insert($features);

        // =========================================================================
        // 4. DANH MỤC DỰ ÁN & DỰ ÁN (tp_cate_projects & tp_projects)
        // =========================================================================
        DB::table('tp_projects')->truncate();
        DB::table('tp_cate_projects')->truncate();

        $projectCates = [
            [
                'name_vn' => 'Villa',
                'name_en' => 'Villa',
                'slug_vn' => 'villa',
                'slug_en' => 'villa',
                'description_vn' => 'Các công trình biệt thự đơn lập, song lập phong cách Modern Organic & Soft Luxury.',
                'stt' => 1,
            ],
            [
                'name_vn' => 'Dinh thự',
                'name_en' => 'Mansions',
                'slug_vn' => 'dinh-thu',
                'slug_en' => 'dinh-thu',
                'description_vn' => 'Không gian dinh thự thượng lưu với nội thất may đo độc bản và nghệ thuật chạm khắc mộc thủ công.',
                'stt' => 2,
            ],
            [
                'name_vn' => 'Khách sạn',
                'name_en' => 'Hotel & Resort',
                'slug_vn' => 'khach-san',
                'slug_en' => 'khach-san',
                'description_vn' => 'Thiết kế và hoàn thiện nội thất khách sạn boutique, khu nghỉ dưỡng cao cấp.',
                'stt' => 3,
            ],
            [
                'name_vn' => 'Showroom',
                'name_en' => 'Showroom',
                'slug_vn' => 'showroom',
                'slug_en' => 'showroom',
                'description_vn' => 'Showroom trưng bày và không gian bán lẻ sang trọng, chuẩn nhận diện thương hiệu.',
                'stt' => 4,
            ],
        ];

        $insertedCateProjects = [];
        foreach ($projectCates as $pc) {
            $catId = DB::table('tp_cate_projects')->insertGetId([
                'uuid' => Str::uuid()->toString(),
                'name_vn' => $pc['name_vn'],
                'name_en' => $pc['name_en'],
                'slug_vn' => $pc['slug_vn'],
                'slug_en' => $pc['slug_en'],
                'description_vn' => $pc['description_vn'],
                'description_en' => $pc['description_vn'],
                'status' => true,
                'home' => true,
                'stt' => $pc['stt'],
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedCateProjects[$pc['slug_vn']] = $catId;
        }

        $projectList = [
            [
                'name_vn' => 'The Horizon Oasis Villa - Thảo Điền',
                'name_en' => 'The Horizon Oasis Villa - Thao Dien',
                'slug_vn' => 'the-horizon-oasis-villa-thao-dien',
                'slug_en' => 'the-horizon-oasis-villa-thao-dien',
                'cate_slug' => 'villa',
                'intro_vn' => 'Diện tích: 680m² | Phong cách: Modern Organic Luxury. Không gian mở với gỗ sồi tự nhiên, đá cẩm thạch trắng và ánh sáng chan hòa.',
                'image' => 'uploads/demo/project_the_horizon_oasis.webp',
                'stt' => 1,
            ],
            [
                'name_vn' => 'Grand Heritage Manor - Ciputra Tây Hồ',
                'name_en' => 'Grand Heritage Manor - Ciputra Tay Ho',
                'slug_vn' => 'grand-heritage-manor-ciputra',
                'slug_en' => 'grand-heritage-manor-ciputra',
                'cate_slug' => 'dinh-thu',
                'intro_vn' => 'Diện tích: 1.250m² | Phong cách: Soft Luxury & Transitional. Tinh hoa chạm khắc thủ công kết hợp tiện nghi đương đại.',
                'image' => 'uploads/demo/project_grand_heritage_manor.webp',
                'stt' => 2,
            ],
            [
                'name_vn' => 'Amara Pine Boutique Hotel - Đà Lạt',
                'name_en' => 'Amara Pine Boutique Hotel - Da Lat',
                'slug_vn' => 'amara-pine-boutique-hotel-da-lat',
                'slug_en' => 'amara-pine-boutique-hotel-da-lat',
                'cate_slug' => 'khach-san',
                'intro_vn' => 'Diện tích: 2.800m² | Phong cách: Warm Minimalist & Alpine Luxe. Không gian nghỉ dưỡng ấm áp giữa rặng thông reo.',
                'image' => 'uploads/demo/project_amara_pine_hotel.webp',
                'stt' => 3,
            ],
            [
                'name_vn' => 'Lumière Concept Flagship Showroom - Sala',
                'name_en' => 'Lumière Concept Flagship Showroom - Sala',
                'slug_vn' => 'lumiere-concept-flagship-showroom',
                'slug_en' => 'lumiere-concept-flagship-showroom',
                'cate_slug' => 'showroom',
                'intro_vn' => 'Diện tích: 520m² | Phong cách: Contemporary Gallery. Nghệ thuật trưng bày tôn vinh từng đường nét kiến trúc.',
                'image' => 'uploads/demo/project_lumiere_concept_showroom.webp',
                'stt' => 4,
            ],
            [
                'name_vn' => 'Hillside Sanctuary Villa - Tam Đảo',
                'name_en' => 'Hillside Sanctuary Villa - Tam Dao',
                'slug_vn' => 'hillside-sanctuary-villa-tam-dao',
                'slug_en' => 'hillside-sanctuary-villa-tam-dao',
                'cate_slug' => 'villa',
                'intro_vn' => 'Diện tích: 850m² | Phong cách: Wabi Sabi & Modern Organic. Mộc mạc, yên bình nhưng vô cùng đắt giá.',
                'image' => 'uploads/demo/project_hillside_sanctuary.webp',
                'stt' => 5,
            ],
            [
                'name_vn' => 'Oceanfront Royal Residence - Nha Trang',
                'name_en' => 'Oceanfront Royal Residence - Nha Trang',
                'slug_vn' => 'oceanfront-royal-residence-nha-trang',
                'slug_en' => 'oceanfront-royal-residence-nha-trang',
                'cate_slug' => 'dinh-thu',
                'intro_vn' => 'Diện tích: 1.100m² | Phong cách: Coastal Mediterranean Luxury. Tầm nhìn panorama vô cực ôm trọn biển xanh.',
                'image' => 'uploads/demo/project_oceanfront_royal_residence.webp',
                'stt' => 6,
            ],
        ];

        foreach ($projectList as $p) {
            DB::table('tp_projects')->insert([
                'uuid' => Str::uuid()->toString(),
                'name_vn' => $p['name_vn'],
                'name_en' => $p['name_en'],
                'slug_vn' => $p['slug_vn'],
                'slug_en' => $p['slug_en'],
                'category_id' => $insertedCateProjects[$p['cate_slug']] ?? 1,
                'intro_vn' => $p['intro_vn'],
                'intro_en' => $p['intro_vn'],
                'content_vn' => '<p>' . $p['intro_vn'] . '</p><p>Công trình được đội ngũ kiến trúc sư và nghệ nhân thợ mộc của xưởng 5.000m² hoàn thiện tỉ mỉ từng chi tiết, đảm bảo sự đồng bộ từ bản vẽ 3D đến thực tế bàn giao.</p>',
                'content_en' => '<p>' . $p['intro_vn'] . '</p><p>Handcrafted by our master craftsmen and installed to perfection.</p>',
                'image_vn' => $p['image'],
                'image_en' => $p['image'],
                'status' => true,
                'home' => true,
                'stt' => $p['stt'],
                'views' => rand(150, 950),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================================================================
        // 5. BỘ SƯU TẬP SẢN PHẨM NỘI THẤT (tp_cate_products & tp_products)
        // =========================================================================
        DB::table('tp_products')->truncate();
        DB::table('tp_cate_products')->truncate();

        $productCates = [
            [
                'name_vn' => 'Sofa & Ghế Thư Giãn',
                'name_en' => 'Sofas & Armchairs',
                'slug_vn' => 'sofa-ghe-thu-gian',
                'slug_en' => 'sofas-and-armchairs',
                'stt' => 1,
            ],
            [
                'name_vn' => 'Bàn Ăn & Ghế Ăn',
                'name_en' => 'Dining & Kitchen',
                'slug_vn' => 'ban-an-ghe-an',
                'slug_en' => 'dining-tables-chairs',
                'stt' => 2,
            ],
            [
                'name_vn' => 'Tủ Kệ & Decor Cao Cấp',
                'name_en' => 'Cabinetry & Decor',
                'slug_vn' => 'tu-ke-decor-cao-cap',
                'slug_en' => 'cabinets-decor',
                'stt' => 3,
            ],
            [
                'name_vn' => 'Phòng Ngủ Master',
                'name_en' => 'Master Bedroom',
                'slug_vn' => 'phong-ngu-master',
                'slug_en' => 'master-bedroom',
                'stt' => 4,
            ],
        ];

        $insertedCateProducts = [];
        foreach ($productCates as $c) {
            $cId = DB::table('tp_cate_products')->insertGetId([
                'uuid' => Str::uuid()->toString(),
                'name_vn' => $c['name_vn'],
                'name_en' => $c['name_en'],
                'slug_vn' => $c['slug_vn'],
                'slug_en' => $c['slug_en'],
                'image_vn' => 'uploads/demo/product_sofa_modular_monterey.webp',
                'image_en' => 'uploads/demo/product_sofa_modular_monterey.webp',
                'status' => true,
                'home' => true,
                'stt' => $c['stt'],
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedCateProducts[$c['slug_vn']] = $cId;
        }

        $productList = [
            [
                'name_vn' => 'Sofa Modular Monterey Boucle',
                'name_en' => 'Sofa Modular Monterey Boucle',
                'slug_vn' => 'sofa-modular-monterey-boucle',
                'slug_en' => 'sofa-modular-monterey-boucle',
                'cate' => 'sofa-ghe-thu-gian',
                'price' => 48500000,
                'price_old' => 55000000,
                'intro_vn' => 'Vải nỉ Boucle màu kem nhập khẩu Bỉ, khung gỗ sồi Nga chống ẩm, đệm mút lông vũ siêu êm ái.',
                'image' => 'uploads/demo/product_sofa_modular_monterey.webp',
                'hot' => true,
                'stt' => 1,
            ],
            [
                'name_vn' => 'Bàn Ăn Gỗ Sồi Warm Oak Oakhaven',
                'name_en' => 'Warm Oak Oakhaven Dining Table',
                'slug_vn' => 'ban-an-go-soi-warm-oak-oakhaven',
                'slug_en' => 'ban-an-go-soi-warm-oak-oakhaven',
                'cate' => 'ban-an-ghe-an',
                'price' => 32000000,
                'price_old' => 38000000,
                'intro_vn' => 'Kích thước 2400x1000x750mm. Gỗ sồi tự nhiên Mỹ, cạnh vát cong nhẹ nhàng, sơn mờ bảo vệ vân gỗ.',
                'image' => 'uploads/demo/product_ban_an_go_soi_warm_oak.webp',
                'hot' => true,
                'stt' => 2,
            ],
            [
                'name_vn' => 'Ghế Thư Giãn Solstice Linen Armchair',
                'name_en' => 'Solstice Linen Armchair',
                'slug_vn' => 'ghe-thu-gian-solstice-linen-armchair',
                'slug_en' => 'ghe-thu-gian-solstice-linen-armchair',
                'cate' => 'sofa-ghe-thu-gian',
                'price' => 16500000,
                'price_old' => 19500000,
                'intro_vn' => 'Chân gỗ óc chó tự nhiên, bọc vải đay thô dệt thủ công màu be cát, lưng tựa ôm trọn vóc dáng.',
                'image' => 'uploads/demo/product_ghe_thu_gian_solstice.webp',
                'hot' => true,
                'stt' => 3,
            ],
            [
                'name_vn' => 'Tủ Console Gỗ Óc Chó Burl Walnut',
                'name_en' => 'Burl Walnut Console Cabinet',
                'slug_vn' => 'tu-console-go-oc-cho-burl-walnut',
                'slug_en' => 'tu-console-go-oc-cho-burl-walnut',
                'cate' => 'tu-ke-decor-cao-cap',
                'price' => 28500000,
                'price_old' => 33000000,
                'intro_vn' => 'Vân gỗ nu óc chó Bắc Mỹ tuyển chọn, phụ kiện ray giảm chấn Blum Áo, tay nắm đồng mờ tinh xảo.',
                'image' => 'uploads/demo/product_tu_console_go_oc_cho.webp',
                'hot' => true,
                'stt' => 4,
            ],
            [
                'name_vn' => 'Giường Ngủ Master Bọc Da Nappa Khung Gỗ Tự Nhiên',
                'name_en' => 'Master Bed Italian Nappa Leather Upholstered',
                'slug_vn' => 'giuong-ngu-master-boc-da-nappa',
                'slug_en' => 'master-bed-italian-nappa-leather',
                'cate' => 'phong-ngu-master',
                'price' => 38000000,
                'price_old' => 44000000,
                'intro_vn' => 'Đầu giường bọc da Nappa chần bông êm ái, khung dát nan gỗ Plywood chịu lực 800kg.',
                'image' => 'uploads/demo/product_giuong_ngu_master.webp',
                'hot' => true,
                'stt' => 5,
            ],
            [
                'name_vn' => 'Tủ Rượu Cánh Kính Khung Nhôm Slimline Đèn Led Cảm Ứng',
                'name_en' => 'Slimline Glass Wine Cabinet with Sensor LEDs',
                'slug_vn' => 'tu-ruou-canh-kinh-slimline-led',
                'slug_en' => 'slimline-glass-wine-cabinet-sensor-led',
                'cate' => 'tu-ke-decor-cao-cap',
                'price' => 58000000,
                'price_old' => 65000000,
                'intro_vn' => 'Khung nhôm định hình siêu mỏng, kính màu trà sang trọng và hệ thống chiếu sáng led thanh lịch.',
                'image' => 'uploads/demo/product_tu_ruou_canh_kinh.webp',
                'hot' => true,
                'stt' => 6,
            ],
            [
                'name_vn' => 'Bàn Trà Đôi Mặt Đá Marble Calacatta Tự Nhiên',
                'name_en' => 'Calacatta Marble Dual Coffee Table',
                'slug_vn' => 'ban-tra-doi-mat-da-marble-calacatta',
                'slug_en' => 'calacatta-marble-dual-coffee-table',
                'cate' => 'sofa-ghe-thu-gian',
                'price' => 22000000,
                'price_old' => 26000000,
                'intro_vn' => 'Mặt đá Marble Calacatta vân mây tự nhiên, chân đế kim loại mạ PVD màu đồng Champagne chống xước.',
                'image' => 'uploads/demo/product_ban_tra_doi_marble.webp',
                'hot' => true,
                'stt' => 7,
            ],
            [
                'name_vn' => 'Bộ Bàn Trang Điểm Hiện Đại Kèm Gương Led Cảm Ứng',
                'name_en' => 'Modern Dressing Table Set with LED Mirror',
                'slug_vn' => 'bo-ban-trang-diem-hien-dai-kem-guong-led',
                'slug_en' => 'modern-dressing-table-set-led-mirror',
                'cate' => 'phong-ngu-master',
                'price' => 19500000,
                'price_old' => 23000000,
                'intro_vn' => 'Thiết kế tinh tế với ngăn kéo bọc nỉ phân chia trang sức thông minh và tiện nghi.',
                'image' => 'uploads/demo/product_ban_trang_diem_led.webp',
                'hot' => true,
                'stt' => 8,
            ],
        ];

        foreach ($productList as $prod) {
            DB::table('tp_products')->insert([
                'uuid' => Str::uuid()->toString(),
                'name_vn' => $prod['name_vn'],
                'name_en' => $prod['name_en'],
                'slug_vn' => $prod['slug_vn'],
                'slug_en' => $prod['slug_en'],
                'category_id' => $insertedCateProducts[$prod['cate']] ?? 1,
                'price' => $prod['price'],
                'price_old' => $prod['price_old'],
                'intro_vn' => $prod['intro_vn'],
                'intro_en' => $prod['intro_vn'],
                'content_vn' => '<p>' . $prod['intro_vn'] . '</p><p>Sản phẩm được sản xuất trực tiếp tại nhà máy quy chuẩn 5.000m² với tiêu chuẩn kỹ thuật nghiêm ngặt. Bảo hành 5 năm kết cấu và hỗ trợ bảo dưỡng định kỳ trọn đời.</p>',
                'content_en' => '<p>' . $prod['intro_vn'] . '</p><p>Crafted directly at our 5,000m² factory with a 5-year structural warranty.</p>',
                'image_vn' => $prod['image'],
                'image_en' => $prod['image'],
                'status' => true,
                'hot' => $prod['hot'],
                'stt' => $prod['stt'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================================================================
        // 6. HERO SLIDER BANNER (tp_sliders)
        // =========================================================================
        DB::table('tp_sliders')->truncate();

        $sliders = [
            [
                'name_vn' => 'Kiến Tạo Không Gian Sống Đỉnh Cao',
                'name_en' => 'Crafting Supreme Living Spaces',
                'link' => '/thiet-ke-thi-cong',
                'status' => 1,
                'image_desktop_vn' => 'uploads/demo/hero_slider_1.webp',
                'image_desktop_en' => 'uploads/demo/hero_slider_1.webp',
                'image_mobile_vn' => 'uploads/demo/hero_slider_1.webp',
                'image_mobile_en' => 'uploads/demo/hero_slider_1.webp',
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Nghệ Thuật May Đo Nội Thất Trực Tiếp',
                'name_en' => 'The Art of Direct Bespoke Furniture',
                'link' => '/nha-xuong',
                'status' => 1,
                'image_desktop_vn' => 'uploads/demo/hero_slider_2.webp',
                'image_desktop_en' => 'uploads/demo/hero_slider_2.webp',
                'image_mobile_vn' => 'uploads/demo/hero_slider_2.webp',
                'image_mobile_en' => 'uploads/demo/hero_slider_2.webp',
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        foreach ($sliders as $s) {
            Slider::create($s);
        }

        // =========================================================================
        // 7. TẠP CHÍ KIẾN TRÚC & TIN TỨC (tp_cate_news & tp_news)
        // =========================================================================
        DB::table('tp_news')->truncate();
        DB::table('tp_cate_news')->truncate();

        $newsCateId = DB::table('tp_cate_news')->insertGetId([
            'uuid' => Str::uuid()->toString(),
            'name_vn' => 'Tin tức',
            'name_en' => 'News & Articles',
            'slug_vn' => 'tin-tuc',
            'slug_en' => 'tin-tuc',
            'description_vn' => 'Cập nhật các xu hướng thiết kế, vật liệu cao cấp và cảm hứng không gian sống đương đại.',
            'status' => true,
            'home' => true,
            'stt' => 1,
            'parent_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $newsList = [
            [
                'name_vn' => 'Xu hướng Modern Organic: Khi chất cảm mộc mạc định nghĩa sự sang trọng',
                'name_en' => 'Modern Organic Trends: When Tactile Simplicity Defines Luxury',
                'slug_vn' => 'xu-huong-modern-organic-noi-that-cao-cap',
                'slug_en' => 'xu-huong-modern-organic-noi-that-cao-cap',
                'intro_vn' => 'Khám phá triết lý thiết kế kết hợp hoàn hảo giữa vật liệu tự nhiên, ánh sáng chan hòa và đường nét tối giản thanh lịch.',
                'image' => 'uploads/demo/news_xu_huong_modern_organic.webp',
            ],
            [
                'name_vn' => 'Lợi ích vượt trội khi đặt sản xuất nội thất may đo trực tiếp tại xưởng',
                'name_en' => 'Superior Benefits of Ordering Bespoke Furniture Directly from Factory',
                'slug_vn' => 'loi-ich-dat-san-xuat-noi-that-truc-tiep-tai-xuong',
                'slug_en' => 'loi-ich-dat-san-xuat-noi-that-truc-tiep-tai-xuong',
                'intro_vn' => 'Tại sao các gia chủ thông thái luôn ưu tiên lựa chọn đơn vị có nhà máy sản xuất trực tiếp thay vì các đơn vị thương mại trung gian.',
                'image' => 'uploads/demo/news_loi_ich_san_xuat_truc_tiep.webp',
            ],
            [
                'name_vn' => 'Nghệ thuật phối màu trung tính Studio McGee trong không gian biệt thự',
                'name_en' => 'The Art of Studio McGee Neutral Palette in Villa Spaces',
                'slug_vn' => 'nghe-thuat-phoi-mau-trung-tinh-studio-mcgee',
                'slug_en' => 'nghe-thuat-phoi-mau-trung-tinh-studio-mcgee',
                'intro_vn' => 'Bí quyết xếp lớp (layering) các sắc độ kem, be ấm, gỗ sồi và điểm xuyết màu than đen mờ tạo chiều sâu cuốn hút.',
                'image' => 'uploads/demo/news_nghe_thuat_phoi_mau_mcgee.webp',
            ],
        ];

        foreach ($newsList as $k => $n) {
            DB::table('tp_news')->insert([
                'uuid' => Str::uuid()->toString(),
                'name_vn' => $n['name_vn'],
                'name_en' => $n['name_en'],
                'slug_vn' => $n['slug_vn'],
                'slug_en' => $n['slug_en'],
                'category_id' => $newsCateId,
                'intro_vn' => $n['intro_vn'],
                'intro_en' => $n['intro_vn'],
                'content_vn' => '<p>' . $n['intro_vn'] . '</p><p>Không gian sống hoàn mỹ là sự hội tụ giữa bản vẽ thiết kế sáng tạo và tay nghề mộc điêu luyện. Hãy để chúng tôi đồng hành cùng bạn trên hành trình kiến tạo tổ ấm trọn vẹn.</p>',
                'content_en' => '<p>' . $n['intro_vn'] . '</p>',
                'image_vn' => $n['image'],
                'image_en' => $n['image'],
                'status' => true,
                'stt' => $k + 1,
                'views' => rand(100, 500),
                'created_at' => now()->subDays($k * 2),
                'updated_at' => now(),
            ]);
        }

        // =========================================================================
        // 8. MENU ĐIỀU HƯỚNG ĐA CẤP (tp_menus)
        // =========================================================================
        DB::table('tp_menus')->truncate();

        // Menu 1: Trang Chủ
        DB::table('tp_menus')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Trang chủ',
            'name_en' => 'Home',
            'link' => '/',
            'slug' => 'trang-chu',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 1,
        ]);

        // Menu 2: Giới thiệu
        DB::table('tp_menus')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Giới thiệu',
            'name_en' => 'About Us',
            'link' => '/gioi-thieu',
            'slug' => 'gioi-thieu',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 2,
        ]);

        // Menu 3: Sản phẩm
        DB::table('tp_menus')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Sản phẩm',
            'name_en' => 'Products',
            'link' => '/san-pham',
            'slug' => 'san-pham',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 3,
        ]);

        // Menu 4: Thiết kế & Thi công (Cha có dropdown)
        $parentProjectMenuId = DB::table('tp_menus')->insertGetId([
            'uuid' => Str::uuid(),
            'name_vn' => 'Thiết kế & Thi công',
            'name_en' => 'Design & Build',
            'link' => '/thiet-ke-thi-cong',
            'slug' => 'thiet-ke-thi-cong',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 4,
        ]);

        // Submenus: Villa, Dinh thự, Khách sạn, Showroom
        DB::table('tp_menus')->insert([
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Villa',
                'name_en' => 'Villa',
                'link' => '/villa',
                'slug' => 'villa',
                'type' => 'link',
                'parent_id' => $parentProjectMenuId,
                'object_id' => 0,
                'stt' => 1,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Dinh thự',
                'name_en' => 'Mansions',
                'link' => '/dinh-thu',
                'slug' => 'dinh-thu',
                'type' => 'link',
                'parent_id' => $parentProjectMenuId,
                'object_id' => 0,
                'stt' => 2,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Khách sạn',
                'name_en' => 'Hotel',
                'link' => '/khach-san',
                'slug' => 'khach-san',
                'type' => 'link',
                'parent_id' => $parentProjectMenuId,
                'object_id' => 0,
                'stt' => 3,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Showroom',
                'name_en' => 'Showroom',
                'link' => '/showroom',
                'slug' => 'showroom',
                'type' => 'link',
                'parent_id' => $parentProjectMenuId,
                'object_id' => 0,
                'stt' => 4,
            ],
        ]);

        // Menu 5: Nhà xưởng
        DB::table('tp_menus')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Nhà xưởng',
            'name_en' => 'Factory',
            'link' => '/nha-xuong',
            'slug' => 'nha-xuong',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 5,
        ]);

        // Menu 6: Tin tức
        DB::table('tp_menus')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Tin tức',
            'name_en' => 'News',
            'link' => '/tin-tuc',
            'slug' => 'tin-tuc',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 6,
        ]);

        // Menu 7: Liên hệ
        DB::table('tp_menus')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Liên hệ',
            'name_en' => 'Contact',
            'link' => '/lien-he',
            'slug' => 'lien-he',
            'type' => 'link',
            'parent_id' => 0,
            'object_id' => 0,
            'stt' => 7,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
