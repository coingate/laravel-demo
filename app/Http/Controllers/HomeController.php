<?php

namespace App\Http\Controllers;

use App\Helpers\ConfigHelper;
use App\Helpers\CurrencyHelper;
use CoinGate\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $configHelper = ConfigHelper::make();
        $currencyHelper = CurrencyHelper::make();

        return view('home', compact('configHelper', 'currencyHelper'));
    }

    public function orders(Request $request)
    {
        $from = $request->input('from') ?? now()->subWeek()->format('Y-m-d');

        $configHelper = ConfigHelper::make();

        if (!$configHelper->getApiKey()) {
            return redirect()->route('home');
        }

        $client = new Client($configHelper->getApiKey(), $configHelper->isSandbox());

        $options = ['created_at' => ['from' => $from]];
        $orderList = $client->order->list($options);

        return view('orders', compact('orderList', 'from'));
    }

    public function checkout($orderId)
    {
        return view('checkout', compact('orderId'));
    }
}
