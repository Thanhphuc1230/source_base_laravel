<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuickUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(?:\+84|0)\d{9}$/',
                'unique:users,phone'
            ],
            'password' => 'required|string|min:6',
            'level' => 'required|integer|in:1,2,3',
            'status' => 'required|in:0,1',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:tp_roles,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Vui lòng nhập họ và tên',
            'fullname.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã tồn tại trong hệ thống',
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự',
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'email.unique' => 'Địa chỉ email đã được sử dụng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'phone.regex' => 'Số điện thoại không đúng định dạng (ví dụ: 0xxxxxxxxx hoặc +84xxxxxxxxx)',
            'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập hoặc tạo mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'level.required' => 'Vui lòng chọn cấp độ',
            'level.in' => 'Cấp độ được chọn không hợp lệ',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'roles.array' => 'Dữ liệu vai trò không hợp lệ',
            'roles.*.exists' => 'Vai trò được chọn không tồn tại'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && !empty($this->phone)) {
            $phone = $this->phone;
            $normalized = preg_replace('/[^+0-9]/', '', $phone);
            $this->merge(['phone' => $normalized]);
        }

        if ($this->has('username') && !empty($this->username)) {
            $this->merge([
                'username' => trim(strtolower($this->username))
            ]);
        }

        if ($this->has('email') && !empty($this->email)) {
            $this->merge([
                'email' => trim(strtolower($this->email))
            ]);
        }
    }
}
