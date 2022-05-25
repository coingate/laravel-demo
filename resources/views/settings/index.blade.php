@extends('layout.app')

@section('content')
    <div class="w-1/2 mx-auto">
        <h1 class="text-xl font-bold dark:text-white">
            Settings
        </h1>

        @if (session('success'))
            <div class="mt-2 p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-2 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-5 bg-gray-100 dark:bg-gray-800 dark:text-white p-5 rounded border dark:border-gray-700">
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="api_key" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">API
                        Key</label>
                    <input type="text" id="api_key" name="api_key" value="{{ old('api_key', $settings['api_key']) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    @error('api_key')
                        <div class="mt-2 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                            role="alert">
                            {{ $message }}
                        </div>
                    @enderror()
                </div>

                <div class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Payment method</div>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="checkout_method-redirect" name="checkout_method" type="radio" value="redirect"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                            {{ old('checkout_method', $settings['checkout_method']) === 'redirect' ? 'checked' : '' }}>
                    </div>
                    <label for="checkout_method-redirect" class="ml-2 text-sm text-gray-900 dark:text-gray-300">
                        Redirect to CoinGate ({{ $redirectCurrencyCount }} currencies)
                    </label>
                </div>
                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                        <input id="checkout_method-local" name="checkout_method" type="radio" value="local"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                            {{ old('checkout_method', $settings['checkout_method']) === 'local' ? 'checked' : '' }}>
                    </div>
                    <label for="checkout_method-local" class="ml-2 text-sm  text-gray-900 dark:text-gray-300">
                        Local on page ({{ $localCurrencyCount }} currencies)
                    </label>
                </div>

                <div class="mb-6">
                    <label for="test-mode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        Test mode
                    </label>

                    <select id="test-mode" name="test_mode"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="1" {{ old('test_mode', $settings['test_mode']) ? 'selected' : '' }}>
                            Enabled
                        </option>
                        <option value="0" {{ !old('test_mode', $settings['test_mode']) ? 'selected' : '' }}>
                            Disabled
                        </option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="payout_currency" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        Payout currency
                    </label>
                    <select id="payout_currency" name="payout_currency"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @foreach ($payoutCurrencies as $payoutCurrency)
                            <option value="{{ $payoutCurrency['symbol'] }}"
                                {{ old('payout_currency', $settings['payout_currency']) === $payoutCurrency['symbol'] ? 'selected' : '' }}>
                                {{ $payoutCurrency['title'] }}
                            </option>
                        @endforeach
                    </select>

                    @error('payout_currency')
                        <div class="mt-2 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                            role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Save
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
