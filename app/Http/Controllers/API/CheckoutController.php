<?php

namespace App\Http\Controllers\API;

use App\Helpers\ConfigHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use CoinGate\Client;
use Exception;

class CheckoutController extends Controller
{
    public function payWithCoinGate(CheckoutRequest $request)
    {
        $products = $request->input('products');

        $configHelper = ConfigHelper::make();
        $client = new Client($configHelper->getApiKey(), $configHelper->isSandbox());

        $orderId = uniqid();

        try {
            $order = $client->order->create(
                $this->getCheckoutParams($orderId, $products, $configHelper)
            );

            if ($configHelper->isLocalCheckout()) {
                $checkout = $client->order->checkout($order->id, ['pay_currency' => $request->input('currency')]);

                return response()->json([
                    'checkout' => $checkout,
                    'order' => $order,
                    'redirect_url' => route('checkout', $order->id),
                ]);
            }

            return response()->json([
                'order' => $order,
                'order_id' => $orderId,
                'redirect_url' => $order->payment_url,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function getCheckoutParams(string $orderId, array $products, ConfigHelper $configHelper): array
    {
        return [
            'order_id' => $orderId,
            'price_amount' => $this->getCalculatedPrice($products),
            'price_currency' => 'EUR',
            'receive_currency' => $configHelper->getPayoutCurrency(),
            'callback_url' => route('callback', $orderId),
            'cancel_url' => route('callback.failure', $orderId),
            'success_url' => route('callback.success', $orderId),
            'title' => 'Order #' . $orderId,
            'description' => 'Order #' . $orderId,
        ];
    }

    private function getCalculatedPrice(array $products): float
    {
        $price = 0;

        foreach ($products as $product) {
            $price += $product['price'] * $product['quantity'];
        }

        return $price;
    }

    public function getOrder($orderId)
    {
        $configHelper = ConfigHelper::make();
        $client = new Client($configHelper->getApiKey(), $configHelper->isSandbox());

        try {
            $order = $client->order->get($orderId);

            return response()->json([
                'order' => $order,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
