<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'unit' => 'required|string|max:20',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'low_stock_threshold' => 'required|numeric|min:0',
        ];
    }
}
