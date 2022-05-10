<?php

namespace App\Http\Controllers;

class CallbackController extends Controller
{
    public function index($orderId)
    {
        return "OK";
    }

    public function success($orderId)
    {
        return view('callback.success', compact('orderId'));
    }

    public function failure($orderId)
    {
        return view('callback.failure', compact('orderId'));
    }
}
