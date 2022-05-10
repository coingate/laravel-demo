@extends('layouts.app')

@section('content')
    <div id="checkout">
        <h4>Order #{{ $orderId }}</h4>

        <div v-if="!order">
            <p>Loading...</p>
        </div>

        <div class="mt-5" v-else>
            <div class="row justify-content-center">
                <div class="col-md-6 col-sm-12">
                    <div class="alert alert-info">
                        Payment address: <strong>@{{ order.payment_address }}</strong>
                    </div>

                    <div class="mt-5">
                        <h5>Order details</h5>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Cryptocurrency</th>
                                <td>@{{ order.pay_currency }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>@{{ order.pay_amount }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span v-if="order.status === 'new'" class="badge badge-info">
                                        New
                                    </span>
                                    <span v-if="order.status === 'pending'" class="badge badge-warning">
                                        Pending
                                    </span>
                                    <span v-if="order.status === 'confirming'" class="badge badge-warning">
                                        Confirming
                                    </span>
                                    <span v-if="order.status === 'paid'" class="badge badge-success">
                                        Paid
                                    </span>
                                    <span v-if="order.status === 'invalid'" class="badge badge-danger">
                                        Invalid
                                    </span>
                                    <span v-if="order.status === 'expired'" class="badge badge-danger">
                                        Expired
                                    </span>
                                    <span v-if="order.status === 'canceled'" class="badge badge-danger">
                                        Cancelled
                                    </span>
                                    <span v-if="order.status === 'refunded'" class="badge badge-warning">
                                        Refunded
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Expires in</th>
                                <td>@{{ expiresIn }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-5">
                        <div v-if="isExpired">
                            <div class="alert alert-danger text-center">
                                This order has expired.
                                <hr>
                                <a class="btn btn-link" href="{{ route('home') }}">Create new order</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endsection

            @section('scripts')
                <script>
                    const {createApp} = Vue;
                    createApp({
                        data() {
                            return {
                                orderId: '{{ $orderId }}',
                                order: null,
                                loading: true,
                                error: null,
                                expiresIn: null,
                                isExpired: false,
                                expiresInInterval: null,
                                fetchInterval: null,
                            }
                        },
                        mounted() {
                            this.fetchOrder();

                            this.expiresInInterval = setInterval(() => {
                                this.fetchOrder();
                            }, 30 * 1000);

                            this.expiresInInterval = setInterval(() => {
                                this.calculateExpiresIn();
                            }, 1000);
                        },
                        methods: {
                            fetchOrder() {
                                this.error = null;

                                axios.get('/api/orders/' + this.orderId)
                                    .then(response => {
                                        this.order = response.data.order;
                                        this.loading = false;

                                        const stopStatuses = ['paid', 'invalid', 'expired', 'canceled', 'refunded'];
                                        if (stopStatuses.includes(this.order.status)) {
                                            clearInterval(this.expiresInInterval);
                                        }
                                    })
                                    .catch(error => {
                                        this.error = error.response.data.message;
                                        this.loading = false;
                                    });
                            },
                            calculateExpiresIn() {
                                if (!this.order) {
                                    this.expiresIn = null;
                                    return;
                                }

                                const expiresAt = new Date(this.order.expire_at);
                                const now = new Date();

                                const diff = expiresAt.getTime() - now.getTime();

                                if (diff < 0) {
                                    this.expiresIn = 'Expired';
                                    clearInterval(this.expiresInInterval);
                                    this.isExpired = true;
                                    this.order.status = 'expired';
                                    return;
                                }

                                const hours = Math.floor(diff / (1000 * 60 * 60));
                                const minutes = Math.floor(diff / (1000 * 60)) % 60;
                                const seconds = Math.floor(diff / 1000) % 60;

                                this.expiresIn = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                            }
                        }
                    }).mount('#checkout');
                </script>
@endsection
