@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h3 class="mb-5">Order #{{$orderId}}</h3>

        <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">Failure!</h5>
            <p>Payment was not successful. Please try again.</p>
            <hr>
            <p class="mb-0">
                <a href="{{route('home')}}" class="btn btn-primary">Make another order</a>
            </p>
        </div>
    </div>
@endsection
