<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaundryOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'date_received' => 'required|date',
            'date_release' => 'nullable|date|after_or_equal:date_received',
            'notes' => 'nullable|string|max:1000',
            'amount_paid' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,washing,drying,folding,ready,claimed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.kilos' => 'required|numeric|min:0.1',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least one service item.',
            'items.*.service_id.required' => 'Please select a service.',
            'items.*.kilos.required' => 'Please enter the weight in kilos.',
            'items.*.kilos.min' => 'Minimum weight is 0.1 kg.',
        ];
    }
}
