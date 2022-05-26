<?php

namespace App\Http\Controllers;

use App\Enums\CallbackType;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Requests\OrderStoreRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function store(OrderStoreRequest $request)
    {
        $client = getCoingateClient();

        $totalAmount = array_reduce($request->products, function ($carry, $item) {
            return $carry + $item['price'] * $item['quantity'];
        }, 0);

        $settings = getSettings();

        $order = Order::create([
            'coingate_order_id' => null,
            'status' => OrderStatus::PENDING,
            'amount' => $totalAmount,
        ]);

        $params = [
            'order_id'          => $order->uuid,
            'price_amount'      => $totalAmount,
            'price_currency'    => 'EUR',
            'receive_currency'  => $settings['payout_currency'],
            'callback_url'      => route('orders.callback', $order->uuid),
            'cancel_url'        => route('orders.cancel', $order->uuid),
            'success_url'       => route('orders.success', $order->uuid),
            'title'             => 'Order ' . $order->uuid,
            'description'       => 'This is order description for order ' . $order->uuid,
        ];

        try {
            $coinGateOrder = $client->order->create($params);
            $redirect_url = $coinGateOrder->payment_url;

            if ($settings['checkout_method'] === 'local') {
                $coinGateOrder = $client->order->checkout($coinGateOrder->id, [
                    'pay_currency' => $request->input('pay_currency'),
                ]);

                $redirect_url = route('orders.checkout', $order->uuid);
            }
        } catch (\Exception $e) {
            $order->delete();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        $order->update([
            'coingate_order_id' => $coinGateOrder->id,
            'status' => OrderStatus::from($coinGateOrder->status),
        ]);

        return response()->json([
            'success' => true,
            'redirect_to' => $redirect_url,
        ]);
    }

    public function fetchCoingateStatus($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        $client = getCoingateClient();

        try {
            $response = $client->order->get($order->coingate_order_id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        saveCallbackHistory($order, (array) $response, CallbackType::MANUAL);

        $order->update([
            'status' => OrderStatus::from($response->status),
        ]);

        return response()->json([
            'status' => $response->status,
        ]);
    }

    public function success($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        return view('orders.success', [
            'order' => $order,
        ]);
    }

    public function cancel($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        return view('orders.cancel', [
            'order' => $order,
        ]);
    }

    public function callbackHistory($uuid)
    {
        $order = Order::where('uuid', $uuid)->with('callbacks')->firstOrFail();

        return view('orders.callback-history', [
            'order' => $order,
        ]);
    }

    public function checkout($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $coinGateOrder = getCoingateClient()->order->get($order->coingate_order_id);
        return view('orders.checkout', compact('order', 'coinGateOrder'));
    }

    public function checkStatus($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'status' => $order->status,
        ]);
    }
}
