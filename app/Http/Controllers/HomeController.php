<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $settings = getSettings();
        $client = getCoingateClient();

        $payCurrencies = $client->getCheckoutCurrencies();

        return view('home', compact('settings', 'payCurrencies'));
    }
}
