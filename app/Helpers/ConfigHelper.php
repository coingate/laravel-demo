<?php

namespace App\Helpers;

class ConfigHelper
{
    const CONFIG_PATH = 'app/config/config.json';

    public function __construct(
        private string $apiKey,
        private string $payoutCurrency,
        private bool $sandbox,
        private readonly bool $localCheckout
    ) {
    }

    public static function make(): ConfigHelper
    {
        self::checkConfigFile();

        $configFile = file_get_contents(storage_path(self::CONFIG_PATH));

        $config = json_decode($configFile, true);

        return new static($config['apiKey'], $config['payoutCurrency'], $config['sandbox'], $config['localCheckout']);
    }

    private static function checkConfigFile(): void
    {
        if (! is_dir(storage_path('app/config'))) {
            mkdir(storage_path('app/config'));
        }

        $configFile = storage_path(self::CONFIG_PATH);

        if (! file_exists($configFile)) {
            file_put_contents($configFile, json_encode([
                'apiKey' => "",
                'payoutCurrency' => "BTC",
                'sandbox' => true,
                'localCheckout' => false,
            ]));
        }
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getPayoutCurrency(): string
    {
        return $this->payoutCurrency;
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function isLocalCheckout(): bool
    {
        return $this->localCheckout;
    }

    public function save(string $apiKey, bool $sandbox, string $payoutCurrency, bool $localCheckout): void
    {
        $config = [
            'apiKey' => $apiKey,
            'payoutCurrency' => $payoutCurrency,
            'sandbox' => $sandbox,
            'localCheckout' => $localCheckout,
        ];

        file_put_contents(storage_path(self::CONFIG_PATH), json_encode($config));

        $this->apiKey = $apiKey;
        $this->payoutCurrency = $payoutCurrency;
        $this->sandbox = $sandbox;
    }
}
