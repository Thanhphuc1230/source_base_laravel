<?php

namespace App\Repositories\Eloquent;

use App\Models\CateProduct;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $slugService;

    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     * @param SlugService $slugService
     */
    public function __construct(Product $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredProducts(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Search filter
        if (!empty($filters['search'])) {
            $searchTerm = trim($filters['search']);
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        // Category filter (support both single category and child categories)
        if (!empty($filters['category']) && $filters['category'] != 0) {
            $categoryId = (int) $filters['category'];
            $childCategoryIds = CateProduct::where('parent_id', $categoryId)->pluck('id_cate_product')->toArray();
            $categoryIds = array_merge([$categoryId], $childCategoryIds);
            $query->whereIn('category_id', $categoryIds);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with(['cate:id_cate_product,name,slug'])
            ->select('uuid', 'name', 'slug', 'status', 'home', 'hot', 'stt', 'created_at', 'category_id', 'image_vn', 'image_en', 'id_product', 'seo_score')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveCategories()
    {
        return CateProduct::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getActiveBrands()
    {
        return \App\Models\Brand::where('status', 1)
            ->orderBy('stt', 'asc')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function syncAttributesAndVariants($product, array $data)
    {
        // 1. Lưu Attributes
        $attributesData = $data['product_attributes'] ?? [];
        $savedAttributeIds = [];

        if (is_array($attributesData)) {
            $stt = 1;
            foreach ($attributesData as $attr) {
                if (empty($attr['name_vn'])) {
                    continue;
                }

                $values = [];
                if (!empty($attr['values'])) {
                    if (is_string($attr['values'])) {
                        $values = array_filter(array_map('trim', explode(',', $attr['values'])));
                    } elseif (is_array($attr['values'])) {
                        $values = array_filter(array_map('trim', $attr['values']));
                    }
                }

                $attribute = $product->attributes()->updateOrCreate(
                    ['id' => $attr['id'] ?? null],
                    [
                        'name_vn' => trim($attr['name_vn']),
                        'name_en' => !empty($attr['name_en']) ? trim($attr['name_en']) : null,
                        'values_json' => array_values($values),
                        'stt' => $stt++,
                    ]
                );

                $savedAttributeIds[] = $attribute->id;
            }
        }

        // Xóa attributes không còn trong request
        $product->attributes()->whereNotIn('id', $savedAttributeIds)->delete();

        // 2. Lưu Variants
        $variantsData = $data['product_variants'] ?? [];
        $savedVariantIds = [];

        if (is_array($variantsData)) {
            $hasDefault = false;
            foreach ($variantsData as $variant) {
                $optionValues = $variant['option_values'] ?? [];
                if (is_string($optionValues)) {
                    $decoded = json_decode($optionValues, true);
                    $optionValues = is_array($decoded) ? $decoded : [];
                }

                if (empty($optionValues)) {
                    continue;
                }

                $isDefault = !empty($variant['is_default']) && !$hasDefault;
                if ($isDefault) {
                    $hasDefault = true;
                }

                $varModel = $product->variants()->updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'sku' => !empty($variant['sku']) ? trim($variant['sku']) : null,
                        'option_values' => $optionValues,
                        'price' => isset($variant['price']) ? (float) $variant['price'] : 0,
                        'price_old' => !empty($variant['price_old']) ? (float) $variant['price_old'] : null,
                        'quantity' => isset($variant['quantity']) ? (int) $variant['quantity'] : 50,
                        'image' => !empty($variant['image']) ? $variant['image'] : null,
                        'is_default' => $isDefault,
                        'status' => isset($variant['status']) ? (bool) $variant['status'] : true,
                    ]
                );

                $savedVariantIds[] = $varModel->id;
            }

            // Đảm bảo ít nhất có 1 variant là default nếu có biến thể
            if (!$hasDefault && !empty($savedVariantIds)) {
                $product->variants()->where('id', $savedVariantIds[0])->update(['is_default' => true]);
            }
        }

        // Xóa variants không còn trong request
        $product->variants()->whereNotIn('id', $savedVariantIds)->delete();
    }

    /**
     * @inheritDoc
     */
    public function getReusableAttributes(): array
    {
        $defaultPresets = [
            [
                'name_vn' => 'Kích thước',
                'name_en' => 'Size',
                'values' => ['1m6 x 2m', '1m8 x 2m', '2m x 2m2'],
            ],
            [
                'name_vn' => 'Màu sắc gỗ',
                'name_en' => 'Wood Finish',
                'values' => ['Gỗ Sồi Nga Tự Nhiên', 'Gỗ Óc Chó Bắc Mỹ', 'Gỗ Gõ Đỏ', 'Gỗ Xoan Đào', 'Trắng Bắc Âu'],
            ],
            [
                'name_vn' => 'Chất liệu bọc',
                'name_en' => 'Upholstery',
                'values' => ['Da Bò Ý Cao Cấp', 'Da Microfiber Chống Xước', 'Vải Nỉ Nhung Hàn Quốc', 'Vải Bố Canvas'],
            ],
            [
                'name_vn' => 'Kích thước Sofa',
                'name_en' => 'Sofa Dimensions',
                'values' => ['Văng 1m8', 'Văng 2m2', 'Góc L 2m6 x 1m6', 'Góc L 3m x 1m8'],
            ],
            [
                'name_vn' => 'Kích thước Bàn Ăn',
                'name_en' => 'Dining Table Size',
                'values' => ['Bàn 4 ghế (1m2)', 'Bàn 6 ghế (1m6)', 'Bàn 8 ghế (1m8)', 'Bàn 10 ghế (2m2)'],
            ],
            [
                'name_vn' => 'Kích thước Tủ Áo',
                'name_en' => 'Wardrobe Size',
                'values' => ['2 cánh (1m2 x 2m)', '3 cánh (1m6 x 2m)', '4 cánh (2m x 2m2)', '5 cánh (2m4 x 2m2)'],
            ],
            [
                'name_vn' => 'Chất liệu mặt bàn',
                'name_en' => 'Tabletop Material',
                'values' => ['Mặt Đá Ceramic Chống Xước', 'Mặt Đá Cẩm Thạch Marble', 'Mặt Gỗ Tự Nhiên Liền Tấm', 'Mặt Kính Cường Lực'],
            ],
            [
                'name_vn' => 'Chất liệu đệm mút',
                'name_en' => 'Cushion Foam',
                'values' => ['Đệm Foam D40 Cao Cấp', 'Đệm Lò Xo Túi Độc Lập', 'Đệm Cao Su Thiên Nhiên'],
            ],
            [
                'name_vn' => 'Chân khung',
                'name_en' => 'Frame Material',
                'values' => ['Khung Gỗ Tự Nhiên', 'Sắt Sơn Tĩnh Điện Anodize', 'Inox 304 Mạ Vàng PVD'],
            ],
            [
                'name_vn' => 'Xuất xứ',
                'name_en' => 'Origin',
                'values' => ['Việt Nam xuất khẩu', 'Nhập khẩu Malaysia', 'Nhập khẩu Đan Mạch', 'Nhập khẩu Ý'],
            ],
            [
                'name_vn' => 'Thời gian bảo hành',
                'name_en' => 'Warranty',
                'values' => ['12 Tháng', '24 Tháng', '36 Tháng', '5 Năm'],
            ],
        ];

        // Lấy thêm các thuộc tính đã từng được lưu từ CSDL để tái sử dụng
        try {
            $dbAttrs = \App\Models\ProductAttribute::query()
                ->select('name_vn', 'values_json')
                ->whereNotNull('name_vn')
                ->get();

            $existingNames = array_map(fn($item) => mb_strtolower($item['name_vn']), $defaultPresets);

            foreach ($dbAttrs as $attr) {
                $name = trim($attr->name_vn);
                if (!empty($name) && !in_array(mb_strtolower($name), $existingNames)) {
                    $vals = is_array($attr->values_json) ? $attr->values_json : (json_decode($attr->values_json, true) ?: []);
                    if (!empty($vals)) {
                        $defaultPresets[] = [
                            'name_vn' => $name,
                            'name_en' => $name,
                            'values' => $vals,
                        ];
                        $existingNames[] = mb_strtolower($name);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Log or ignore database fetch error
        }

        return $defaultPresets;
    }
}
