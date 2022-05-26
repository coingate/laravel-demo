<?php

use App\Enums\CallbackType;
use App\Models\Order;
use CoinGate\Client;

define("SETTING_FILE", "app/settings.json");

if (!function_exists('getCoingateClient')) {
    function getCoingateClient(): Client
    {
        $settings = getSettings();

        Client::setAppInfo("Laravel demo shop v1", "1.0.0");

        return new Client($settings['api_key'], $settings['test_mode']);
    }
}

if (!function_exists('saveCallbackHistory')) {
    function saveCallbackHistory(Order $order, array $data, CallbackType $callbackType): void
    {
        $order->callbacks()->create([
            'data' => json_encode($data),
            'type' => $callbackType->getValue(),
        ]);
    }
}

if (!function_exists('getSettings')) {
    function getSettings(): array
    {
        $settingsFile = storage_path(SETTING_FILE);

        if (!file_exists($settingsFile)) {
            return [
                'api_key' => null,
                'checkout_method' => 'redirect',
                'test_mode' => false,
                'payout_currency' => 'BTC',
                'pay_currency' => 'EUR',
            ];
        }

        return json_decode(file_get_contents($settingsFile), true);
    }
}

if (!function_exists('saveSettings')) {
    function saveSettings(array $settings): void
    {
        file_put_contents(storage_path(SETTING_FILE), json_encode($settings, JSON_PRETTY_PRINT));
    }
}
