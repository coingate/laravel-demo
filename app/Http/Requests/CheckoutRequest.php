<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => 'required',
            'products' => ['required', 'array'],
            'products.*.name' => ['required'],
            'products.*.price' => ['required', 'numeric'],
            'products.*.quantity' => ['required', 'numeric'],
        ];
    }
}
