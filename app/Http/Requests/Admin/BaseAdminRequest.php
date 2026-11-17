<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorization levels:
     * - Level 1 (Admin): Full access to all admin functions
     * - Level 2 (Staff): Access to most admin functions (products, categories, orders, etc.)
     * - Level 3 (Users): No access to admin functions
     *
     * Only authenticated admin users (level >= 1) can access admin functions
     */
    public function authorize(): bool
    {
        // Check if user is authenticated
        if (! auth()->check()) {
            return false;
        }

        // Check if user has admin level (level >= 1) and is active
        $user = auth()->user();

        return $user->level >= 1 && $user->status == 1;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute là bắt buộc.',
            'string' => ':attribute phải là chuỗi.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
            'numeric' => ':attribute phải là số.',
            'integer' => ':attribute phải là số nguyên.',
            'boolean' => ':attribute phải là true hoặc false.',
            'exists' => ':attribute được chọn không tồn tại trên hệ thống.',
            'unique' => ':attribute đã tồn tại trên hệ thống.',
            'image' => ':attribute phải là tệp hình ảnh.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'dimensions' => ':attribute không đúng kích thước yêu cầu.',
            'email' => ':attribute không đúng định dạng email.',
            'in' => ':attribute không nằm trong danh sách hợp lệ.',
            'url' => ':attribute phải là đường dẫn URL hợp lệ.',
            'array' => ':attribute phải là dạng danh sách mảng.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Tiêu đề / Tên',
            'name_vn' => 'Tiêu đề (Tiếng Việt)',
            'name_en' => 'Tiêu đề (Tiếng Anh)',
            'title_vn' => 'Tiêu đề (Tiếng Việt)',
            'title_en' => 'Tiêu đề (Tiếng Anh)',
            'intro_vn' => 'Mô tả ngắn / Sapo (Tiếng Việt)',
            'intro_en' => 'Mô tả ngắn / Sapo (Tiếng Anh)',
            'content_vn' => 'Nội dung chi tiết (Tiếng Việt)',
            'content_en' => 'Nội dung chi tiết (Tiếng Anh)',
            'image' => 'Hình ảnh',
            'image_vn' => 'Hình ảnh (Tiếng Việt)',
            'image_en' => 'Hình ảnh (Tiếng Anh)',
            'image_desktop_vn' => 'Hình ảnh Desktop (Tiếng Việt)',
            'image_desktop_en' => 'Hình ảnh Desktop (Tiếng Anh)',
            'image_mobile_vn' => 'Hình ảnh Mobile (Tiếng Việt)',
            'image_mobile_en' => 'Hình ảnh Mobile (Tiếng Anh)',
            'image_detail' => 'Thư viện hình ảnh',
            'status' => 'Trạng thái hiển thị',
            'hot' => 'Trạng thái nổi bật',
            'stt' => 'Số thứ tự',
            'category_id' => 'Danh mục / Chủ đề',
            'parent_id' => 'Danh mục cha',
            'brand_id' => 'Thương hiệu',
            'slug' => 'Đường dẫn (Slug)',
            'slug_vn' => 'Đường dẫn Slug (Tiếng Việt)',
            'slug_en' => 'Đường dẫn Slug (Tiếng Anh)',
            'keyword_vn' => 'Từ khóa SEO (Tiếng Việt)',
            'keyword_en' => 'Từ khóa SEO (Tiếng Anh)',
            'keywords' => 'Từ khóa SEO',
            'description' => 'Mô tả SEO',
            'description_vn' => 'Mô tả SEO (Tiếng Việt)',
            'description_en' => 'Mô tả SEO (Tiếng Anh)',
            'price' => 'Giá bán',
            'price_old' => 'Giá gốc (cũ)',
            'sku' => 'Mã sản phẩm',
            'created_at' => 'Ngày đăng',
            'email' => 'Địa chỉ Email',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'address_vn' => 'Địa chỉ (Tiếng Việt)',
            'address_en' => 'Địa chỉ (Tiếng Anh)',
        ];
    }

    /**
     * Rule that accepts either an UploadedFile or a valid media library path string
     */
    public function imageRule(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            function ($attribute, $value, $fail) {
                if (empty($value)) return;
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    $ext = strtolower($value->getClientOriginalExtension() ?: $value->guessExtension());
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'])) {
                        $fail($attribute . ' phải là định dạng hình ảnh hợp lệ (jpg, png, webp, svg).');
                    }
                    if ($value->getSize() > 10485760) {
                        $fail($attribute . ' không được vượt quá 10MB.');
                    }
                } elseif (is_string($value)) {
                    $clean = ltrim(parse_url($value, PHP_URL_PATH) ?: $value, '/');
                    if (!str_starts_with($clean, 'uploads/')) {
                        $fail($attribute . ' phải là file tải lên hoặc đường dẫn ảnh hợp lệ từ thư viện.');
                    }
                }
            }
        ];
    }
}
