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
            'exists' => ':attribute không tồn tại.',
            'unique' => ':attribute đã tồn tại.',
            'image' => ':attribute phải là ảnh.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'dimensions' => ':attribute không đúng kích thước yêu cầu.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'name_vn' => 'Tên tiếng Việt',
            'name_en' => 'Tên tiếng Anh',
            'content_vn' => 'Nội dung tiếng Việt',
            'content_en' => 'Nội dung tiếng Anh',
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái',
            'stt' => 'Thứ tự',
            'category_id' => 'Danh mục',
            'slug' => 'Đường dẫn',
            'keywords' => 'Từ khóa',
            'description' => 'Mô tả',
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
