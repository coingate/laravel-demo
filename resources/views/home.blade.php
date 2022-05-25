@extends('layout.app')

@section('content')
    <div id="checkout">
        <div class="flex justify-between">
            <h1 class="text-xl font-bold dark:text-white">
                Checkout
            </h1>

            <button type="button" @click="addProduct()"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Add product
            </button>
        </div>

        @if (!$settings['api_key'])
            <div class="mt-2 p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800"
                role="alert">
                Please set your API key in the <a href="{{ route('settings.index') }}">settings</a> page.
            </div>
        @endif

        <div class="mt-5">
            <ul>
                <li v-for="(product, index) in products"
                    class="mb-2 p-5 bg-gray-100 dark:bg-gray-800 border dark:border-gray-700 rounded">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex items-center">
                                <div class="mr-6">
                                    <label for="name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        Product name
                                    </label>
                                    <input type="text" id="name" v-model="product.name"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                <div class="mr-6">
                                    <label for="quantity"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        Quantity
                                    </label>
                                    <input type="number" id="quantity" v-model="product.quantity"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                <div class="mr-6">
                                    <label for="price"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        Item Price
                                    </label>
                                    <input type="number" step="0.1" id="price" v-model="product.price"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl dark:text-white flex items-center">
                            <div class="mr-2">
                                @{{ (product.quantity * product.price).toFixed(2) }} €
                            </div>
                            <div>
                                <button type="button" @click="removeProduct(index)"
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="text-center text-gray-500 dark:text-white text-2xl font-light p-5" v-if="products.length === 0">
            No products added yet
        </div>

        <div class="mt-10">
            <h1 class="text-xl font-bold dark:text-white">
                Payment method
            </h1>

            <div class="p-5 mt-5 bg-gray-100 dark:bg-gray-800 border dark:border-gray-700 rounded">
                <div class="flex items-center rounded">
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input id="payment_method" type="checkbox" v-model="payment_method"
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                        </div>
                        <label for="payment_method" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Pay with cryptocurrencies via <strong>CoinGate</strong>
                        </label>
                    </div>
                    @if ($settings['checkout_method'] === 'local')
                        <div class="ml-5">
                            <select id="pay_currency" v-model="pay_currency"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach ($payCurrencies as $currency)
                                    <option value="{{ $currency['symbol'] }}">
                                        {{ $currency['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-10">
            <button type="button" @click="submit()" :disabled="!canSubmit || loading"
                class="disabled:opacity-50 disabled:pointer-events-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <span v-if="!loading">
                    Make payment @{{ total.toFixed(2) }} €
                </span>

                <span v-else>
                    Submitting...
                </span>
            </button>
        </div>

        <div v-if="error">
            <div class="mt-10">
                <div
                    class="bg-red-700 text-white text-sm font-medium rounded-lg p-2.5 dark:bg-red-600 dark:text-white dark:p-2.5">
                    @{{ error }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const checkout = new Vue({
            el: '#checkout',
            data: {
                error: null,
                loading: false,
                products: [],
                payment_method: false,
                pay_currency: 'BTC',
            },
            mounted() {
                this.addProduct();
            },
            computed: {
                total() {
                    return this.products.reduce((total, product) => {
                        return total + (product.quantity * product.price);
                    }, 0);
                },
                canSubmit() {
                    return this.products.length > 0 && this.payment_method;
                }
            },
            methods: {
                removeProduct(index) {
                    this.products.splice(index, 1);
                },
                addProduct() {
                    this.products.push({
                        name: 'Product ' + (this.products.length + 1),
                        quantity: Math.floor(Math.random() * 10) + 1,
                        price: Math.floor(Math.random() * 100) + 1,
                    });
                },
                removeProduct(index) {
                    this.products.splice(index, 1);
                },
                submit() {
                    this.error = null;
                    this.loading = true;

                    axios.post('{{ route('orders.store') }}', {
                        products: this.products,
                        pay_currency: this.pay_currency,
                        _token: '{{ csrf_token() }}',
                    }).then(response => {
                        window.location.href = response.data.redirect_to;
                    }).catch(error => {
                        this.error = error.response.data.message;
                    }).finally(() => {
                        this.loading = false;
                    });
                }
            },
        });
    </script>
@endsection
