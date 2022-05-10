@extends('layouts.app')

@section('content')
    <h3 class="mb-5">Checkout</h3>

    <div class="alert alert-warning">
        Make sure you have entered API Key in the <a href="{{ route('settings.index') }}">settings</a> page.
    </div>

    <div id="checkout">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary" @click="addProduct()" id="checkout-button">Add product</button>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2" v-for="(product, index) in products">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between">
                                <div class="form-group mr-5">
                                    <label for="name">Product name</label>
                                    <input v-model="product.name" type="text" class="form-control">
                                </div>

                                <div class="form-group mr-5">
                                    <label for="quantity">Quantity</label>
                                    <input v-model="product.quantity" type="number" class="form-control" id="quantity">
                                </div>

                                <div class="form-group">
                                    <label for="price">Price per item</label>
                                    <input v-model="product.price" type="number" class="form-control" id="price">
                                </div>
                            </div>

                            <div>
                                <h3>
                                    <span id="total">@{{ (product.quantity * product.price).toFixed(2) }} EUR</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="payWithCoinGate" id="payWithCoinGate">
                <label class="form-check-label" for="payWithCoinGate">
                    Pay with cryptocurrencies via CoinGate
                </label>
            </div>

            @if ($configHelper->isLocalCheckout())
                <div>
                    <select v-model="currency" class="form-control" id="currency">
                        @foreach($currencyHelper->getCheckoutCurrencies() as $currency)
                            <option value="{{ $currency['symbol'] }}">{{ $currency['symbol'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="mt-5">
            <button class="btn btn-primary" @click="submit()" id="payWithCoinGate-button"
                    :disabled="!payWithCoinGate || loading">
                @{{ loading ? 'Loading...' : 'Confirm' }}
            </button>
            @if (!$configHelper->isLocalCheckout())
                <small class="form-text text-muted">
                    You will be redirected to CoinGate to make payment.
                </small>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const {createApp} = Vue
        createApp({
            data() {
                return {
                    loading: false,
                    currency: 'BTC',
                    payWithCoinGate: false,
                    products: [
                        {
                            name: 'Product 1',
                            price: 10,
                            quantity: 1
                        }
                    ],
                }
            },
            methods: {
                addProduct() {
                    this.products.push({
                        name: 'Product ' + (this.products.length + 1),
                        price: 10,
                        quantity: 1
                    });
                },
                submit() {
                    this.loading = true;
                    axios.post('/api/pay-with-coingate', {
                        products: this.products,
                        currency: this.currency,
                    }).then(response => {
                        window.location.href = response.data.redirect_url;
                    }, error => {
                        this.loading = false;
                        alert(error.response.data.message);
                    });
                }
            }
        }).mount('#checkout')
    </script>
@endsection
