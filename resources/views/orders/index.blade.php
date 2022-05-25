@extends('layout.app')

@section('content')
    @if ($orders->count() > 0)
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Order ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 w-24">
                        </th>
                        <th scope="col" class="px-6 py-3 w-40">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $order->uuid }}
                            </th>
                            <td class="px-6 py-4">
                                <span id="status-{{ $order->uuid }}">
                                    {{ strtoupper($order->status->value) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->amount }} €
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" data-uuid="{{ $order->uuid }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</a>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('orders.callback-history', $order->uuid) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Check
                                    history</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
            No orders found.
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const updateButton = document.querySelectorAll('a[data-uuid]');

            updateButton.forEach(button => {
                button.addEventListener('click', event => {
                    event.preventDefault();

                    button.innerHTML = 'Updating...';

                    const uuid = event.target.dataset.uuid;

                    axios.put('/orders/' + uuid + '/update')
                        .then(response => {
                            const status = document.getElementById('status-' + uuid);
                            status.innerHTML = response.data.status.toUpperCase();
                            button.innerHTML = 'Update';
                        })
                        .catch(error => {
                            console.log(error);
                            button.innerHTML = 'Update';
                        });
                });
            });
        });
    </script>
@endsection
