<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RedirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_url' => ['required', 'string', 'max:255'],
            'target_url' => ['nullable', 'string', 'max:500', 'required_if:status_code,301,302'],
            'status_code' => ['required', 'integer', 'in:301,302,404'],
            'is_active' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
