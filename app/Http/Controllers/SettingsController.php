<?php

namespace App\Http\Controllers;

use App\Helpers\ConfigHelper;
use App\Helpers\CurrencyHelper;
use App\Http\Requests\StoreSettingsRequest;
use CoinGate\Client;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $configHelper = ConfigHelper::make();
        $currencyHelper = CurrencyHelper::make();

        return view('settings.index', compact('configHelper', 'currencyHelper'));
    }

    public function update(StoreSettingsRequest $request)
    {
        $testConnectionResult = Client::testConnection(
            $request->input('api_key'),
            $request->input('sandbox')
        );

        if (! $testConnectionResult) {
            return redirect()->back()->with('error', 'Connection failed for the given API key: ' . $request->input('api_key'));
        }

        $configHelper = ConfigHelper::make();
        $configHelper->save(
            $request->input('api_key'),
            $request->input('sandbox'),
            $request->input('currency'),
            $request->input('checkout_method') === 'local'
        );

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
