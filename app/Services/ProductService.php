<?php

namespace App\Services;

use App\Models\CateProduct;
use App\Models\Product;
use App\Models\ProductSetting;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductService
{
    protected $productRepository;
    protected $commentRepository;

    public function __construct(ProductRepositoryInterface $productRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->productRepository = $productRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get data for product category page
     *
     * @param int $id_cate_product
     * @param Request $request
     * @return array
     */
    public function getCategoryProductData($id_cate_product, Request $request)
    {
        $data = [];

        // Cache category data
        $data['category_detail'] = CateProduct::where('status', 1)->where('id_cate_product', $id_cate_product)->firstOrFail();

        // Cache category list (sidebar) - Load category hierarchy with product counts only (Rule 19)
        $data['category_product'] = CateProduct::select('id_cate_product', 'uuid', 'name', 'slug', 'parent_id', 'status', 'stt')
            ->withCount(['products' => fn ($q) => $q->where('status', 1)])
            ->where('status', 1)
            ->orderBy('stt', 'asc')
            ->get()
            ->groupBy('parent_id');

        // Build category id list (include children recursively)
        $categoryIds = $this->getAllCategoryIds($data['category_detail']->id_cate_product);

        // Base query
        $query = Product::with(['cate:id_cate_product,name,slug'])
            ->select('id_product', 'uuid', 'name', 'slug', 'price', 'price_old', 'image_vn', 'image_en', 'intro', 'category_id', 'status', 'stt', 'created_at')
            ->whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->orderBy('created_at', 'desc');

        // Apply filters
        $query = $this->applyProductFilters($query, $request);

        // Apply sorting
        $query = $this->applyProductSorting($query, $request);

        // Get per-page value
        $perPage = $this->getPerPageValue($request);

        $data['products'] = $query->paginate($perPage)->appends($request->query());

        return $data;
    }

    /**
     * Get data for product detail page
     *
     * @param int $id_product
     * @return array
     */
    public function getDetailProductData($id_product)
    {
        $data = [];

        // Cache product detail with attributes and active variants
        $data['product_detail'] = Product::with([
            'cate:id_cate_product,name,slug',
            'attributes',
            'variants' => fn($q) => $q->where('status', true),
        ])->where('id_product', $id_product)->firstOrFail();

        // Cache related products with preview columns only (Rule 19)
        $previewColumns = [
            'id_product', 'uuid', 'name', 'slug',
            'image_vn', 'image_en', 'price', 'price_old', 'category_id',
            'hot', 'status', 'stt', 'intro', 'created_at',
        ];
        $data['related_product'] = Product::select($previewColumns)
            ->where('category_id', $data['product_detail']->category_id)
            ->where('id_product', '!=', $data['product_detail']->id_product)
            ->where('status', 1)
            ->orderBy('stt', 'asc')
            ->limit(8)
            ->get();

        // Cache approved comments
        $data['comments'] = $this->commentRepository->getApprovedCommentsForItem('product', $data['product_detail']->id_product);

        // Product Commitments & Policies from Setting (Zero-Hardcode)
        $defaultCommitments = [
            ['icon' => 'fa-solid fa-rotate', 'title' => 'Bảo hành 1 đổi 1', 'desc' => 'Đổi mới trong 30 ngày nếu có lỗi từ nhà sản xuất'],
            ['icon' => 'fa-solid fa-headset', 'title' => 'Hỗ trợ kỹ thuật 24/7', 'desc' => 'Tư vấn nhiệt tình, hỗ trợ kỹ thuật trọn đời sản phẩm'],
            ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Cam kết chính hãng 100%', 'desc' => 'Đền gấp 10 lần nếu phát hiện hàng không chính hãng'],
            ['icon' => 'fa-solid fa-truck-fast', 'title' => 'Giao hàng hỏa tốc', 'desc' => 'Miễn phí giao hàng toàn quốc, đồng kiểm khi nhận hàng'],
        ];
        $commitments = ProductSetting::get('products_commitments', $defaultCommitments);
        if (is_string($commitments)) {
            $decoded = json_decode($commitments, true);
            $commitments = is_array($decoded) ? $decoded : $defaultCommitments;
        }
        $data['product_commitments'] = !empty($commitments) ? $commitments : $defaultCommitments;
        $data['commitments_title'] = ProductSetting::get('products_commitments_title', 'Chính sách bán hàng & Cam kết');
        $data['commitments_subtitle'] = ProductSetting::get('products_commitments_subtitle', 'Áp dụng cho tất cả sản phẩm chính hãng tại hệ thống');
        $data['show_commitments'] = (bool) ProductSetting::get('products_show_commitments', true);

        return $data;
    }

    /**
     * Apply filters to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyProductFilters($query, Request $request)
    {
        // Name search filter
        if ($request->filled('name')) {
            $name = $request->get('name');
            $query->where('name', 'like', "%{$name}%");
        }

        // Price filters
        if ($request->filled('price_min')) {
            $priceMin = (float) $request->get('price_min');
            $query->where('price', '>=', $priceMin);
        }

        if ($request->filled('price_max')) {
            $priceMax = (float) $request->get('price_max');
            $query->where('price', '<=', $priceMax);
        }

        // Date filters
        if ($request->filled('date_from')) {
            $dateFrom = $request->get('date_from');
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->get('date_to');
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    /**
     * Apply sorting to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyProductSorting($query, Request $request)
    {
        $sort = $request->get('sort');

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('stt', 'asc');
                break;
        }

        return $query;
    }

    /**
     * Get per-page value with validation
     *
     * @param Request $request
     * @return int
     */
    private function getPerPageValue(Request $request)
    {
        $defaultPerPage = (int) \App\Models\ProductSetting::get('products_pagination', 8);
        $perPage = (int) $request->get('per_page', $defaultPerPage);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = $defaultPerPage;
        }
        return $perPage;
    }

    /**
     * Get all category IDs recursively (parent + all children)
     *
     * @param int $parentId
     * @return array
     */
    private function getAllCategoryIds($parentId)
    {
        $categoryIds = [$parentId];

        $this->getChildCategoryIds($parentId, $categoryIds);

        return $categoryIds;
    }

    /**
     * Recursively get all child category IDs
     *
     * @param int $parentId
     * @param array &$categoryIds
     * @return void
     */
    private function getChildCategoryIds($parentId, &$categoryIds)
    {
        $children = CateProduct::where('parent_id', $parentId)->pluck('id_cate_product')->toArray();

        foreach ($children as $childId) {
            $categoryIds[] = $childId;
            $this->getChildCategoryIds($childId, $categoryIds);
        }
    }

    /**
     * Get data for brand product page
     *
     * @param int $id_brand
     * @param Request|null $request
     * @return array
     */
    public function getBrandProductData($id_brand, Request $request = null)
    {
        if (!$request) {
            $request = request();
        }
        $data = [];

        $data['brand_detail'] = \App\Models\Brand::where('status', 1)->where('id_brand', $id_brand)->firstOrFail();
        
        $data['category_detail'] = (object) [
            'id_cate_product' => 0,
            'name_vn' => $data['brand_detail']->name_vn,
            'name_en' => $data['brand_detail']->name_en ?? $data['brand_detail']->name_vn,
            'name' => $data['brand_detail']->name_vn,
            'slug' => $data['brand_detail']->slug ?? \Illuminate\Support\Str::slug($data['brand_detail']->name_vn),
            'intro_vn' => 'Xe / Sản phẩm thương hiệu ' . $data['brand_detail']->name_vn,
            'description_vn' => 'Danh sách xe thuộc thương hiệu ' . $data['brand_detail']->name_vn,
            'keyword_vn' => $data['brand_detail']->name_vn,
            'image' => $data['brand_detail']->image,
        ];

        $data['category_product'] = CateProduct::with(['products', 'children.products'])
            ->where('status', 1)
            ->orderBy('stt', 'asc')
            ->get()
            ->groupBy('parent_id');

        $query = Product::with(['cate:id_cate_product,name,slug'])
            ->select('id_product', 'uuid', 'name', 'slug', 'price', 'price_old', 'image_vn', 'image_en', 'intro', 'category_id', 'brand_id', 'status', 'stt', 'created_at')
            ->where('brand_id', $id_brand)
            ->where('status', 1)
            ->orderBy('created_at', 'desc');

        $query = $this->applyProductFilters($query, $request);
        $query = $this->applyProductSorting($query, $request);
        $perPage = $this->getPerPageValue($request);

        $data['products'] = $query->paginate($perPage)->appends($request->query());

        return $data;
    }
}