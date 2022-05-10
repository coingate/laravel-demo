@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="api_key">API Key</label>
                            <input type="text" class="form-control" id="api_key" name="api_key"
                                   value="{{ $configHelper->getApiKey() }}">

                            @error('api_key')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="checkout_method">Checkout Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkout_method" id="redirect"
                                       value="redirect" {{ !$configHelper->isLocalCheckout() ? 'checked' : '' }}>
                                <label class="form-check-label" for="redirect">
                                    Redirect to coingate ({{ count($currencyHelper->getPayCurrencies()) }} currencies)
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkout_method" id="local"
                                       value="local" {{ $configHelper->isLocalCheckout() ? 'checked' : '' }}>
                                <label class="form-check-label" for="local">
                                    Local on page ({{ count($currencyHelper->getCheckoutCurrencies()) }} currencies)
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sandbox">Sandbox</label>
                            <select class="form-control" id="sandbox" name="sandbox">
                                <option value="1" {{ $configHelper->isSandbox() ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$configHelper->isSandbox() ? 'selected' : '' }}>No</option>
                            </select>

                            @error('sandbox')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="currency">Payout Currency</label>
                            <select class="form-control" id="currency" name="currency">
                                @foreach ($currencyHelper->getPayoutCurrencies() as $currency)
                                    <option
                                        value="{{ $currency['symbol'] }}" {{ $configHelper->getPayoutCurrency() == $currency['symbol'] ? 'selected' : '' }}>{{ $currency['title'] }}</option>
                                @endforeach
                            </select>

                            @error('currency')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3 w-100">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
