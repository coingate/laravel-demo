<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pay_currency' => 'required|string',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer',
            'products.*.price' => 'required|numeric',
        ];
    }
}
