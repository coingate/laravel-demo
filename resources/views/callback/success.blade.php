@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h3 class="mb-5">Order #{{$orderId}}</h3>

        <div class="alert alert-success" role="alert">
            <h5 class="alert-heading">Success!</h5>
            <p>Your order has been successfully processed.</p>
            <hr>
            <p class="mb-0">
                <a href="{{route('home')}}" class="btn btn-primary">Make another order</a>
            </p>
        </div>
    </div>
@endsection
