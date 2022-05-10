<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CurrencyHelper
{
    const BASE_URL = "https://api.coingate.com/v2/currencies";

    public function __construct(
        private readonly array $payoutCurrencies,
        private readonly array $payCurrencies,
        private readonly array $checkoutCurrencies
    ) {
    }

    public static function make(): self
    {
        if (Cache::has('currencies')) {
            $currencies = Cache::get('currencies');

            return new static(
                $currencies['payout'],
                $currencies['pay'],
                $currencies['checkout']
            );
        }

        $payoutCurrencies = json_decode(file_get_contents(self::BASE_URL . "?merchant_receive=true&disabled=false"), true);
        $payCurrencies = json_decode(file_get_contents(self::BASE_URL . "?merchant_pay=true&disabled=false"), true);
        $checkoutCurrencies = json_decode(file_get_contents(self::BASE_URL . "?merchant_pay=true&native=true&disabled=false"), true);

        Cache::put('currencies', [
            'payout' => $payoutCurrencies,
            'pay' => $payCurrencies,
            'checkout' => $checkoutCurrencies,
        ], now()->addDay());

        return new static($payoutCurrencies, $payCurrencies, $checkoutCurrencies);
    }

    public function getPayoutCurrencies(): array
    {
        return $this->payoutCurrencies;
    }

    public function getPayCurrencies(): array
    {
        return $this->payCurrencies;
    }

    public function getCheckoutCurrencies(): array
    {
        return $this->checkoutCurrencies;
    }
}
