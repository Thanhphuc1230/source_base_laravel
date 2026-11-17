<?php

namespace App\Http\Requests\Admin;

class BrandRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_vn' => 'required|max:255',
            'name_en' => 'nullable|max:255',
            'stt' => 'required|integer',
            'image_vn' => $this->imageRule(false),
            'image_en' => $this->imageRule(false),
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Tên thương hiệu sản phẩm Tiếng Việt không được để trống',
            'name_vn.max' => 'Tên thương hiệu sản phẩm Tiếng Việt không được vượt quá 255 ký tự',
            'name_en.max' => 'Tên thương hiệu sản phẩm Tiếng Anh không được vượt quá 255 ký tự',
            'stt.required' => 'Số thứ tự không được để trống',
            'stt.integer' => 'Số thứ tự phải là số',
            'image_vn.image' => 'Ảnh thương hiệu sản phẩm Tiếng Việt phải là hình ảnh',
            'image_vn.max' => 'Ảnh thương hiệu sản phẩm Tiếng Việt không được vượt quá 10MB',
            'image_en.image' => 'Ảnh thương hiệu sản phẩm Tiếng Anh phải là hình ảnh',
            'image_en.max' => 'Ảnh thương hiệu sản phẩm Tiếng Anh không được vượt quá 10MB',
        ];
    }
}
