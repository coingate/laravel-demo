<?php

namespace App\Http\Requests;

use App\Rules\ValidAPIKey;
use Illuminate\Foundation\Http\FormRequest;

class SettingsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_key' => ['required', new ValidAPIKey],
            'checkout_method' => 'required|in:local,redirect',
            'test_mode' => 'required|boolean',
            'payout_currency' => 'required',
        ];
    }
}
