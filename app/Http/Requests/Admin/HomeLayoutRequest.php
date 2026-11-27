<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HomeLayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:50'],
            'reference_ids' => ['nullable', 'array'],
            'reference_ids.*' => ['integer'],
            'block_types' => ['nullable', 'array'],
            'block_types.*' => ['string', 'max:50'],
            'reference_id' => ['nullable', 'integer'],
            'title_vn' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
