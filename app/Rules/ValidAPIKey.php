<?php

namespace App\Rules;

use CoinGate\Client;
use Illuminate\Contracts\Validation\Rule;

class ValidAPIKey implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();

        if (!$client->testConnection($value, request()->input('test_mode') === "1")) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The given API key is invalid.';
    }
}
