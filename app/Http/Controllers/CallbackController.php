<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\CallbackType;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function __invoke($uuid, Request $request)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        try {
            saveCallbackHistory($order, $request->all(), CallbackType::CALLBACK);

            $order->update([
                'status' => OrderStatus::from($request->input('status')),
            ]);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
