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
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name_en', 'LIKE', "%{$searchTerm}%");
            });
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

        return $query->with(['cate:id_cate_product,name_vn,name_en,slug_vn,slug_en'])
            ->select('uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status', 'home', 'hot', 'stt', 'created_at', 'category_id', 'image_vn', 'image_en', 'id_product')
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
}
