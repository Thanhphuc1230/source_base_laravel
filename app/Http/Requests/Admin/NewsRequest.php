<?php

namespace App\Http\Requests\Admin;

class NewsRequest extends BaseAdminRequest
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
            'name_vn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'intro_vn' => 'required|string|max:1000',
            'intro_en' => 'nullable|string|max:1000',
            'content_vn' => 'required|string',
            'content_en' => 'nullable|string',
            'category_id' => 'required|exists:tp_cate_news,id_cate_new',
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
            'name_vn.required' => 'Vui lòng nhập tiêu đề bài viết (Tiếng Việt)',
            'name_vn.max' => 'Tiêu đề bài viết không được quá 255 ký tự',
            'name_en.max' => 'Tiêu đề tiếng Anh không được quá 255 ký tự',
            'intro_vn.required' => 'Vui lòng nhập mô tả ngắn / Sapo (Tiếng Việt)',
            'intro_vn.max' => 'Mô tả ngắn không được quá 1000 ký tự',
            'intro_en.max' => 'Mô tả ngắn tiếng Anh không được quá 1000 ký tự',
            'content_vn.required' => 'Vui lòng nhập nội dung chi tiết bài viết (Tiếng Việt)',
            'category_id.required' => 'Vui lòng chọn chủ đề tin tức',
            'category_id.exists' => 'Chủ đề tin tức được chọn không tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái hiển thị',
            'status.in' => 'Trạng thái hiển thị không hợp lệ',
            'stt.required' => 'Vui lòng nhập số thứ tự',
            'stt.integer' => 'Số thứ tự phải là số nguyên',
            'stt.min' => 'Số thứ tự không được nhỏ hơn 0',
            'slug_vn.max' => 'Slug tiếng Việt không được quá 255 ký tự',
            'slug_en.max' => 'Slug tiếng Anh không được quá 255 ký tự',
            'image_vn.required' => 'Vui lòng chọn hình ảnh đại diện bài viết (Tiếng Việt)',
            'keyword_vn.required' => 'Vui lòng nhập từ khóa SEO (Tiếng Việt)',
            'keyword_vn.max' => 'Từ khóa SEO không được quá 255 ký tự',
            'keyword_en.max' => 'Từ khóa SEO tiếng Anh không được quá 255 ký tự',
            'description_vn.required' => 'Vui lòng nhập mô tả SEO (Tiếng Việt)',
            'description_vn.max' => 'Mô tả SEO không được quá 1000 ký tự',
            'description_en.max' => 'Mô tả SEO tiếng Anh không được quá 1000 ký tự',
        ];
    }
}
