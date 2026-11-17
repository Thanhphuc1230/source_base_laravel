<?php

namespace App\Http\Requests\Admin;

class PageRequest extends BaseAdminRequest
{
    // Authorization is handled by BaseAdminRequest

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_vn' => [
                'required', 'max:255',
            ],
            'name_en' => [
                'nullable', 'max:255',
            ],
            'content_vn' => 'required',
            'content_en' => 'nullable',
            'slug_vn' => 'nullable|max:255',
            'slug_en' => 'nullable|max:255',
            'keyword_vn' => 'required|max:255',
            'keyword_en' => 'nullable|max:255',
            'description_vn' => 'required|max:1000',
            'description_en' => 'nullable|max:1000',
            'image_vn' => $this->imageRule(false),
            'image_en' => $this->imageRule(false),
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name_vn.required' => 'Vui lòng nhập tiêu đề trang (Tiếng Việt)',
            'name_vn.max' => 'Tiêu đề trang không được quá 255 ký tự',
            'name_en.max' => 'Tiêu đề tiếng Anh không được quá 255 ký tự',
            'content_vn.required' => 'Vui lòng nhập nội dung chi tiết trang (Tiếng Việt)',
            'keyword_vn.required' => 'Vui lòng nhập từ khóa SEO (Tiếng Việt)',
            'keyword_vn.max' => 'Từ khóa SEO không được quá 255 ký tự',
            'description_vn.required' => 'Vui lòng nhập mô tả SEO (Tiếng Việt)',
            'description_vn.max' => 'Mô tả SEO không được quá 1000 ký tự',
            'image_vn.max' => 'Hình ảnh tiếng Việt không được vượt quá 10MB',
            'image_en.max' => 'Hình ảnh tiếng Anh không được vượt quá 10MB',
        ];
    }
}
