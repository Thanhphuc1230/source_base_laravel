<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Nếu đã có biến thể sản phẩm thì không tạo đè lại
        if (ProductVariant::query()->exists()) {
            return;
        }

        // 1. Giường Ngủ Master
        $bed = Product::where('slug', 'LIKE', '%giuong%')->orWhere('name', 'LIKE', '%Giường%')->first();
        if ($bed) {
            $this->seedBedVariants($bed);
        }

        // 2. Sofa
        $sofa = Product::where('slug', 'LIKE', '%sofa%')->orWhere('name', 'LIKE', '%Sofa%')->first();
        if ($sofa) {
            $this->seedSofaVariants($sofa);
        }

        // 3. Bàn Ăn
        $table = Product::where('slug', 'LIKE', '%ban-an%')->orWhere('name', 'LIKE', '%Bàn%')->first();
        if ($table && $table->id_product !== ($sofa ? $sofa->id_product : null)) {
            $this->seedDiningVariants($table);
        }
    }

    private function seedBedVariants(Product $product): void
    {
        $product->attributes()->delete();
        $product->variants()->delete();

        // Attributes
        $attrSize = ProductAttribute::create([
            'product_id' => $product->id_product,
            'name_vn' => 'Kích thước',
            'name_en' => 'Size',
            'values_json' => ['1m6 x 2m', '1m8 x 2m', '2m x 2m2'],
            'stt' => 1,
        ]);

        $attrColor = ProductAttribute::create([
            'product_id' => $product->id_product,
            'name_vn' => 'Màu sắc gỗ',
            'name_en' => 'Wood Finish',
            'values_json' => ['Gỗ Óc Chó Bắc Mỹ', 'Gỗ Sồi Tự Nhiên', 'Trắng Bắc Âu'],
            'stt' => 2,
        ]);

        // Variants Matrix
        $variants = [
            ['size' => '1m6 x 2m', 'color' => 'Gỗ Óc Chó Bắc Mỹ', 'price' => 35000000, 'price_old' => 40000000, 'sku' => 'GN-1M6-OCCHO', 'default' => false],
            ['size' => '1m8 x 2m', 'color' => 'Gỗ Óc Chó Bắc Mỹ', 'price' => 38000000, 'price_old' => 44000000, 'sku' => 'GN-1M8-OCCHO', 'default' => true],
            ['size' => '2m x 2m2', 'color' => 'Gỗ Óc Chó Bắc Mỹ', 'price' => 42000000, 'price_old' => 48000000, 'sku' => 'GN-2M-OCCHO', 'default' => false],
            ['size' => '1m6 x 2m', 'color' => 'Gỗ Sồi Tự Nhiên', 'price' => 32000000, 'price_old' => 37000000, 'sku' => 'GN-1M6-SOI', 'default' => false],
            ['size' => '1m8 x 2m', 'color' => 'Gỗ Sồi Tự Nhiên', 'price' => 35000000, 'price_old' => 40000000, 'sku' => 'GN-1M8-SOI', 'default' => false],
            ['size' => '2m x 2m2', 'color' => 'Gỗ Sồi Tự Nhiên', 'price' => 39000000, 'price_old' => 44000000, 'sku' => 'GN-2M-SOI', 'default' => false],
            ['size' => '1m8 x 2m', 'color' => 'Trắng Bắc Âu', 'price' => 36500000, 'price_old' => 42000000, 'sku' => 'GN-1M8-TRANG', 'default' => false],
        ];

        foreach ($variants as $v) {
            ProductVariant::create([
                'product_id' => $product->id_product,
                'sku' => $v['sku'],
                'option_values' => [
                    'Kích thước' => $v['size'],
                    'Màu sắc gỗ' => $v['color'],
                ],
                'price' => $v['price'],
                'price_old' => $v['price_old'],
                'quantity' => 25,
                'image' => $product->image,
                'is_default' => $v['default'],
                'status' => true,
            ]);
        }
    }

    private function seedSofaVariants(Product $product): void
    {
        $product->attributes()->delete();
        $product->variants()->delete();

        ProductAttribute::create([
            'product_id' => $product->id_product,
            'name_vn' => 'Chiều dài Sofa',
            'name_en' => 'Length',
            'values_json' => ['2m2 (Văng đôi)', '2m6 (Văng ba)', '3m2 (Góc chữ L)'],
            'stt' => 1,
        ]);

        ProductAttribute::create([
            'product_id' => $product->id_product,
            'name_vn' => 'Chất liệu bọc',
            'name_en' => 'Material',
            'values_json' => ['Da Bò Ý Cao Cấp', 'Vải Nỉ Bỉ Nhập Khẩu'],
            'stt' => 2,
        ]);

        $variants = [
            ['size' => '2m2 (Văng đôi)', 'mat' => 'Da Bò Ý Cao Cấp', 'price' => 32000000, 'price_old' => 36000000, 'sku' => 'SF-2M2-DABO', 'default' => true],
            ['size' => '2m6 (Văng ba)', 'mat' => 'Da Bò Ý Cao Cấp', 'price' => 39000000, 'price_old' => 44000000, 'sku' => 'SF-2M6-DABO', 'default' => false],
            ['size' => '3m2 (Góc chữ L)', 'mat' => 'Da Bò Ý Cao Cấp', 'price' => 48000000, 'price_old' => 54000000, 'sku' => 'SF-3M2-DABO', 'default' => false],
            ['size' => '2m2 (Văng đôi)', 'mat' => 'Vải Nỉ Bỉ Nhập Khẩu', 'price' => 24000000, 'price_old' => 28000000, 'sku' => 'SF-2M2-NI', 'default' => false],
            ['size' => '2m6 (Văng ba)', 'mat' => 'Vải Nỉ Bỉ Nhập Khẩu', 'price' => 29000000, 'price_old' => 33000000, 'sku' => 'SF-2M6-NI', 'default' => false],
        ];

        foreach ($variants as $v) {
            ProductVariant::create([
                'product_id' => $product->id_product,
                'sku' => $v['sku'],
                'option_values' => [
                    'Chiều dài Sofa' => $v['size'],
                    'Chất liệu bọc' => $v['mat'],
                ],
                'price' => $v['price'],
                'price_old' => $v['price_old'],
                'quantity' => 15,
                'image' => $product->image,
                'is_default' => $v['default'],
                'status' => true,
            ]);
        }
    }

    private function seedDiningVariants(Product $product): void
    {
        $product->attributes()->delete();
        $product->variants()->delete();

        ProductAttribute::create([
            'product_id' => $product->id_product,
            'name_vn' => 'Quy cách bàn',
            'name_en' => 'Set Size',
            'values_json' => ['Bàn 6 ghế (1m6)', 'Bàn 8 ghế (1m8)', 'Bàn 10 ghế (2m2)'],
            'stt' => 1,
        ]);

        ProductAttribute::create([
            'product_id' => $product->id_product,
            'name_vn' => 'Mặt đá',
            'name_en' => 'Stone Top',
            'values_json' => ['Đá Trắng Vân Mây Calacatta', 'Đá Đen Tia Chớp Nero'],
            'stt' => 2,
        ]);

        $variants = [
            ['spec' => 'Bàn 6 ghế (1m6)', 'stone' => 'Đá Trắng Vân Mây Calacatta', 'price' => 28000000, 'price_old' => 32000000, 'sku' => 'BA-6G-TRANG', 'default' => true],
            ['spec' => 'Bàn 8 ghế (1m8)', 'stone' => 'Đá Trắng Vân Mây Calacatta', 'price' => 35000000, 'price_old' => 39000000, 'sku' => 'BA-8G-TRANG', 'default' => false],
            ['spec' => 'Bàn 10 ghế (2m2)', 'stone' => 'Đá Trắng Vân Mây Calacatta', 'price' => 45000000, 'price_old' => 50000000, 'sku' => 'BA-10G-TRANG', 'default' => false],
            ['spec' => 'Bàn 6 ghế (1m6)', 'stone' => 'Đá Đen Tia Chớp Nero', 'price' => 29500000, 'price_old' => 33500000, 'sku' => 'BA-6G-DEN', 'default' => false],
            ['spec' => 'Bàn 8 ghế (1m8)', 'stone' => 'Đá Đen Tia Chớp Nero', 'price' => 36500000, 'price_old' => 40500000, 'sku' => 'BA-8G-DEN', 'default' => false],
        ];

        foreach ($variants as $v) {
            ProductVariant::create([
                'product_id' => $product->id_product,
                'sku' => $v['sku'],
                'option_values' => [
                    'Quy cách bàn' => $v['spec'],
                    'Mặt đá' => $v['stone'],
                ],
                'price' => $v['price'],
                'price_old' => $v['price_old'],
                'quantity' => 10,
                'image' => $product->image,
                'is_default' => $v['default'],
                'status' => true,
            ]);
        }
    }
}
