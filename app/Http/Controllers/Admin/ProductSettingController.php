<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProductSettingController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;

    public function __construct()
    {
        $this->module = 'product-setting';
        $this->model = new ProductSetting;
        $this->nameItem = 'Cấu hình sản phẩm';

        parent::__construct($this->module, null);

        View::share('nameClass', $this->module);
    }

    /**
     * Hiển thị form cấu hình chi tiết (Vào thẳng chi tiết)
     */
    public function index()
    {
        $data = [
            'title' => 'Cấu hình sản phẩm',
            'module' => $this->module,
            'nameClass' => $this->module,
            'settings' => [
                'currency_unit' => ProductSetting::getCurrencyUnit(),
                'pagination' => ProductSetting::get('products_pagination', 8),
                'font_size' => ProductSetting::get('products_font_size', '16px'),
                'show_intro' => ProductSetting::get('products_show_intro', true),
                'click_image_detail' => ProductSetting::get('products_click_image_detail', true),
                'title_color' => ProductSetting::get('products_title_color', '#064e3b'),
                'category_color' => ProductSetting::get('products_category_color', '#b45309'),
                'banner_category' => ProductSetting::get('products_banner_category', ''),
                'banner_detail' => ProductSetting::get('products_banner_detail', ''),
                'show_commitments' => ProductSetting::get('products_show_commitments', true),
                'commitments_title' => ProductSetting::get('products_commitments_title', 'Chính sách bán hàng & Cam kết'),
                'commitments_subtitle' => ProductSetting::get('products_commitments_subtitle', 'Áp dụng cho tất cả sản phẩm chính hãng tại hệ thống'),
                'commitments' => ProductSetting::get('products_commitments', [
                    ['icon' => 'fa-solid fa-rotate', 'title' => 'Bảo hành 1 đổi 1', 'desc' => 'Đổi mới trong 30 ngày nếu có lỗi từ nhà sản xuất'],
                    ['icon' => 'fa-solid fa-headset', 'title' => 'Hỗ trợ kỹ thuật 24/7', 'desc' => 'Tư vấn nhiệt tình, hỗ trợ kỹ thuật trọn đời sản phẩm'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Cam kết chính hãng 100%', 'desc' => 'Đền gấp 10 lần nếu phát hiện hàng không chính hãng'],
                    ['icon' => 'fa-solid fa-truck-fast', 'title' => 'Giao hàng hỏa tốc', 'desc' => 'Miễn phí giao hàng toàn quốc, đồng kiểm khi nhận hàng'],
                ]),
            ]
        ];

        return $this->view_admin('index', $data);
    }

    /**
     * Cập nhật tất cả các cấu hình sản phẩm từ form
     */
    public function updateSettings(Request $request)
    {
        // 1. Lưu các giá trị cấu hình chữ, màu sắc và tiền tệ vào DB
        $currencyUnit = trim((string) $request->input('products_currency_unit', 'đ'));
        if (empty($currencyUnit)) {
            $currencyUnit = 'đ';
        }
        ProductSetting::set('products_currency_unit', $currencyUnit, 'text', 'general', 'Đơn vị tiền tệ hiển thị toàn hệ thống (Web & Admin)');
        if (\Illuminate\Support\Facades\Schema::hasColumn('product_settings', 'currency_unit')) {
            ProductSetting::query()->update(['currency_unit' => $currencyUnit]);
        }

        ProductSetting::set('products_pagination', (int) $request->input('products_pagination', 8), 'number', 'general');
        ProductSetting::set('products_font_size', $request->input('products_font_size', '16px'), 'text', 'style');
        ProductSetting::set('products_show_intro', $request->has('products_show_intro') ? '1' : '0', 'boolean', 'general');
        ProductSetting::set('products_click_image_detail', $request->has('products_click_image_detail') ? '1' : '0', 'boolean', 'general');
        ProductSetting::set('products_title_color', $request->input('products_title_color', '#064e3b'), 'text', 'style');
        ProductSetting::set('products_category_color', $request->input('products_category_color', '#b45309'), 'text', 'style');

        // Cam kết & Chính sách sản phẩm (Bảo hành, Hỗ trợ, Đổi trả,...)
        ProductSetting::set('products_show_commitments', $request->has('products_show_commitments') ? '1' : '0', 'boolean', 'general', 'Bật tắt hiển thị cam kết sản phẩm ngoài frontend');
        ProductSetting::set('products_commitments_title', $request->input('products_commitments_title', 'Chính sách bán hàng & Cam kết'), 'text', 'general', 'Tiêu đề khối cam kết sản phẩm');
        ProductSetting::set('products_commitments_subtitle', $request->input('products_commitments_subtitle', ''), 'text', 'general', 'Mô tả phụ khối cam kết sản phẩm');

        $commitments = [];
        $titles = $request->input('commitment_titles', []);
        $icons = $request->input('commitment_icons', []);
        $descs = $request->input('commitment_descs', []);

        if (is_array($titles)) {
            foreach ($titles as $idx => $title) {
                $trimmedTitle = trim((string) $title);
                if (!empty($trimmedTitle)) {
                    $commitments[] = [
                        'title' => $trimmedTitle,
                        'icon' => !empty($icons[$idx]) ? trim($icons[$idx]) : 'fa-solid fa-circle-check',
                        'desc' => !empty($descs[$idx]) ? trim($descs[$idx]) : '',
                    ];
                }
            }
        }
        ProductSetting::set('products_commitments', json_encode($commitments, JSON_UNESCAPED_UNICODE), 'json', 'general', 'Danh sách các cam kết & chính sách sản phẩm');

        // 2. Xử lý upload Banner Danh mục
        if ($request->has('delete_products_banner_category')) {
            $oldPath = ProductSetting::get('products_banner_category');
            if ($oldPath) {
                $this->imageService->deleteImage($oldPath, 'product-setting');
            }
            ProductSetting::set('products_banner_category', '', 'text', 'banner');
        } elseif ($request->hasFile('products_banner_category') || $request->filled('products_banner_category_selected')) {
            $oldPath = ProductSetting::get('products_banner_category');
            $newPath = $this->imageService->saveImage($request, 'product-setting', 'products_banner_category', $this->defaultImageConfig);
            if ($newPath) {
                ProductSetting::set('products_banner_category', $newPath, 'text', 'banner');
            }
        }

        // 3. Xử lý upload Banner Chi tiết
        if ($request->has('delete_products_banner_detail')) {
            $oldPath = ProductSetting::get('products_banner_detail');
            if ($oldPath) {
                $this->imageService->deleteImage($oldPath, 'product-setting');
            }
            ProductSetting::set('products_banner_detail', '', 'text', 'banner');
        } elseif ($request->hasFile('products_banner_detail') || $request->filled('products_banner_detail_selected')) {
            $oldPath = ProductSetting::get('products_banner_detail');
            $newPath = $this->imageService->saveImage($request, 'product-setting', 'products_banner_detail', $this->defaultImageConfig);
            if ($newPath) {
                ProductSetting::set('products_banner_detail', $newPath, 'text', 'banner');
            }
        }

        // 4. Dọn dẹp cache để frontend cập nhật ngay lập tức
        if (class_exists(\App\Services\CacheService::class)) {
            \App\Services\CacheService::forgetTags(['frontend', 'products', 'categories']);
        }
        \Illuminate\Support\Facades\Cache::forget('frontend_global_data');

        toast('Cập nhật cấu hình sản phẩm thành công!', 'success');

        return back();
    }
}
