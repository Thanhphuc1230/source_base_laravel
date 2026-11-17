<?php

namespace App\Http\Requests\Admin;

class ServiceRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_vn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'intro_vn' => 'required|string|max:1000',
            'intro_en' => 'nullable|string|max:1000',
            'content_vn' => 'required|string',
            'content_en' => 'nullable|string',
            'category_id' => 'required|exists:tp_cate_services,id_cate_service',
            'slug_vn' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'stt' => 'required|integer|min:0',
            'image_vn' => $this->imageRule(!request()->route('uuid')),
            'image_en' => $this->imageRule(false),
            'keyword_vn' => 'required|string|max:255',
            'keyword_en' => 'nullable|string|max:255',
            'description_vn' => 'required|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Vui lòng nhập tên dịch vụ (Tiếng Việt)',
            'name_vn.max' => 'Tên dịch vụ không được quá 255 ký tự',
            'intro_vn.required' => 'Vui lòng nhập mô tả ngắn dịch vụ (Tiếng Việt)',
            'intro_vn.max' => 'Mô tả ngắn dịch vụ không được quá 1000 ký tự',
            'content_vn.required' => 'Vui lòng nhập nội dung chi tiết dịch vụ (Tiếng Việt)',
            'category_id.required' => 'Vui lòng chọn danh mục dịch vụ',
            'category_id.exists' => 'Danh mục dịch vụ không tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'stt.required' => 'Vui lòng nhập số thứ tự',
            'stt.integer' => 'Số thứ tự phải là số nguyên',
            'stt.min' => 'Số thứ tự không được nhỏ hơn 0',
            'image_vn.required' => 'Vui lòng chọn hình ảnh dịch vụ (Tiếng Việt)',
            'keyword_vn.required' => 'Vui lòng nhập từ khóa SEO (Tiếng Việt)',
            'description_vn.required' => 'Vui lòng nhập mô tả SEO (Tiếng Việt)',
            'description_vn.max' => 'Mô tả SEO không được quá 1000 ký tự',
        ];
    }
}
