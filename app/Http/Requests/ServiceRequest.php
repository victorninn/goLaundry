<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_per_kilo' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'products' => 'nullable|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity_per_kilo' => 'numeric|min:0',
        ];
    }
}
