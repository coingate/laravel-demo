@extends('layouts.app')

@section('content')
    <h3 class="mb-5">Orders</h3>

    <div id="checkout">
        <div class="d-flex justify-content-end mb-5">
            <form class="d-flex align-items-center">
                From <input type="date" name="from" id="from" class="ml-2 form-control" value="{{ $from }}">
                <button class="btn btn-primary ml-2" id="filter">Filter</button>
            </form>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2" v-for="(product, index) in products">
                @if($orderList->total_orders > 0)
                    <table class="table table-bordered">
                        <tr>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Value</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>

                        @foreach($orderList->orders as $order)
                            <tr>
                                <td>
                                    <button class="btn btn-link" data-toggle="modal"
                                            data-target="#order-{{ $order['id'] }}">
                                        #{{ $order['id'] }}
                                    </button>
                                </td>
                                <td>{{ $order['price_amount'] }} {{ $order['price_currency'] }}</td>
                                <td>{{ $order['receive_amount'] ? $order['receive_amount'] : '-' }} {{ $order['receive_currency'] }}</td>
                                <td>{{ date('Y-m-d H:i:s', strtotime($order['created_at'])) }}</td>
                                <td>
                                    @if($order['status'] == 'canceled')
                                        <span class="badge badge-danger">{{ $order['status'] }}</span>
                                    @elseif($order['status'] == 'expired')
                                        <span class="badge badge-warning">{{ $order['status'] }}</span>
                                    @elseif($order['status'] == 'new')
                                        <span class="badge badge-primary">{{ $order['status'] }}</span>
                                    @elseif($order['status'] == 'paid')
                                        <span class="badge badge-success">{{ $order['status'] }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $order['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-info">No orders found</div>
                @endif
            </div>
        </div>
    </div>

    @foreach($orderList->orders as $order)

        <div class="modal fade" id="order-{{ $order['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="width: 700px; margin-left: -100px">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Order #{{ $order['id'] }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="overflow:auto;">
                        <table class="table table-bordered">
                            @foreach($order as $key => $value)
                                <tr>
                                    <th>{{ $key }}</th>
                                    <td>
                                        @if(is_array($value))
                                            @foreach($value as $v)
                                                {{ $v }}<br>
                                            @endforeach
                                        @elseif(is_bool($value))
                                            {{ $value ? 'true' : 'false' }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')

@endsection
