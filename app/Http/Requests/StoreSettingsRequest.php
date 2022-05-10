<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_key' => 'required',
            'sandbox' => 'required|boolean',
            'currency' => 'required',
            'checkout_method' => 'required|in:redirect,local',
        ];
    }
}
