<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsStoreRequest;

class SettingsController extends Controller
{
    const SETTING_FILE = 'app/settings.json';

    public function index()
    {
        $client = getCoingateClient();

        $payoutCurrencies = $client->getMerchantPayoutCurrencies();
        $redirectCurrencyCount = count((array) $client->getMerchantPayCurrencies());
        $localCurrencyCount = count((array) $client->getCheckoutCurrencies());
        $settingsFile = storage_path(self::SETTING_FILE);

        $settings = getSettings();

        return view('settings.index', compact(
            'payoutCurrencies',
            'settings',
            'redirectCurrencyCount',
            'localCurrencyCount',
        ));
    }

    public function update(SettingsStoreRequest $request)
    {
        $settings = $request->validated();

        saveSettings($settings);

        return redirect()->route('settings.index')->with('success', 'Settings updated');
    }
}
