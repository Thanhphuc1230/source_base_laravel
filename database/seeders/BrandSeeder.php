<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds for Interior / Furniture Brands.
     */
    public function run(): void
    {
        $brands = [
            [
                'name_vn' => 'Nội Thất Nhà Xinh',
                'name_en' => 'Nha Xinh Furniture',
                'slug_vn' => 'nha-xinh',
                'slug_en' => 'nha-xinh-furniture',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 1,
            ],
            [
                'name_vn' => 'Gỗ An Cường',
                'name_en' => 'An Cuong Wood',
                'slug_vn' => 'an-cuong',
                'slug_en' => 'an-cuong-wood',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 2,
            ],
            [
                'name_vn' => 'BoConcept',
                'name_en' => 'BoConcept Denmark',
                'slug_vn' => 'boconcept',
                'slug_en' => 'boconcept-denmark',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 3,
            ],
            [
                'name_vn' => 'Ashley Furniture',
                'name_en' => 'Ashley Furniture HomeStore',
                'slug_vn' => 'ashley-furniture',
                'slug_en' => 'ashley-furniture-homestore',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 4,
            ],
            [
                'name_vn' => 'Nội Thất Hòa Phát',
                'name_en' => 'Hoa Phat Furniture',
                'slug_vn' => 'hoa-phat',
                'slug_en' => 'hoa-phat-furniture',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 5,
            ],
            [
                'name_vn' => 'Phố Xinh',
                'name_en' => 'Pho Xinh Living',
                'slug_vn' => 'pho-xinh',
                'slug_en' => 'pho-xinh-living',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 6,
            ],
            [
                'name_vn' => 'Kims Full House',
                'name_en' => 'Kims Full House Korea',
                'slug_vn' => 'kims-full-house',
                'slug_en' => 'kims-full-house-korea',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 7,
            ],
            [
                'name_vn' => 'Gỗ Minh Long',
                'name_en' => 'Minh Long Wood',
                'slug_vn' => 'minh-long',
                'slug_en' => 'minh-long-wood',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 8,
            ],
            [
                'name_vn' => 'JYSK',
                'name_en' => 'JYSK Scandinavian Living',
                'slug_vn' => 'jysk',
                'slug_en' => 'jysk-scandinavian-living',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 9,
            ],
            [
                'name_vn' => 'AConcept',
                'name_en' => 'AConcept Urban Living',
                'slug_vn' => 'aconcept',
                'slug_en' => 'aconcept-urban-living',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 10,
            ],
        ];

        // Xóa các thương hiệu cũ (đồng hồ) không phù hợp
        Brand::query()->delete();

        $createdBrands = [];
        foreach ($brands as $brand) {
            $created = Brand::create(array_merge($brand, ['uuid' => Str::uuid()->toString()]));
            $createdBrands[] = $created;
        }

        // Cập nhật lại brand_id cho các sản phẩm hiện có nếu cần
        if (!empty($createdBrands)) {
            $brandIds = array_column($createdBrands, 'id_brand');
            $products = Product::all();
            foreach ($products as $index => $prod) {
                $assignedBrandId = $brandIds[$index % count($brandIds)];
                $prod->update(['brand_id' => $assignedBrandId]);
            }
        }
    }
}
