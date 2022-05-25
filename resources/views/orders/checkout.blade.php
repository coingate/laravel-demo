@extends('layout.app')

@section('content')
    <div>
        <h1 class="text-xl font-bold dark:text-white">
            Order {{ $order->uuid }}
        </h1>

        <div class="mt-10 p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800"
            role="alert">
            <span class="font-medium">Payment address: </span> {{ $coinGateOrder->payment_address }}
        </div>

        <div class="mt-10">

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            Status
                        </th>
                        <td class="px-6 py-4">
                            <div id="status" class="uppercase">
                                {{ $coinGateOrder->status }}
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            Amount
                        </th>
                        <td class="px-6 py-4">
                            {{ $coinGateOrder->pay_amount }} {{ $coinGateOrder->pay_currency }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            Expires in
                        </th>
                        <td class="px-6 py-4">
                            <span id="expires_in"></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const expiresAt = new Date('{{ $coinGateOrder->expire_at }}');

        const updateExpiresIn = () => {
            const status = document.getElementById('status');

            const now = new Date();
            const diff = expiresAt.getTime() - now.getTime();

            const seconds = Math.floor(diff / 1000);
            const minutes = Math.floor(seconds / 60);

            const secondsLeft = seconds % 60;
            const minutesLeft = minutes % 60;

            const expiresIn = `${minutesLeft}m ${secondsLeft}s`;

            document.getElementById('expires_in').innerText = expiresIn;

            if (diff > 0 && status.innerText !== 'expired' && status.innerText !== 'paid') {
                setTimeout(updateExpiresIn, 1000);
            }
        };

        updateExpiresIn();

        const url = '{{ route('orders.check-status', $order->uuid) }}';

        const checkStatus = () => {
            axios.get(url)
                .then(response => {
                    const status = document.getElementById('status');

                    status.innerText = response.data.status;

                    if (response.data.status === 'paid') {
                        window.location.href = '{{ route('orders.success', $order->uuid) }}';
                    } else if (response.data.status === 'expired') {
                        window.location.href = '{{ route('orders.cancel', $order->uuid) }}';
                    } else {
                        setTimeout(checkStatus, 3000);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        };

        checkStatus();
    </script>
@endsection
