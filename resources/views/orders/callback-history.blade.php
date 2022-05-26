@extends('layout.app')

@section('content')
    <div id="callbacks">
        <h1 class="text-xl dark:text-white">
            <strong>Callback history of</strong> {{ $order->uuid }}
        </h1>


        <div class="mt-10 relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->callbacks as $callback)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $callback->id }}
                            </th>
                            <td class="px-6 py-4">
                                @if ($callback->type === App\Enums\CallbackType::MANUAL->value)
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                                        Manual
                                    </span>
                                @else
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                                        Callback
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $callback->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" @click="openModal('{{ $callback->id }}')"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="callbackModal" v-if="selectedCallback" style="background-color: rgba(0, 0, 0, 0.5);"
            class="flex p-10 pt-32 items-center justify-center overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 bottom-0 z-50 w-full md:inset-0 h-modal md:h-full">
            <div class="border dark:border-gray-600 rounded w-1/2 bg-white text-black dark:bg-gray-700 dark:text-white"
                style="margin-top: 220px">
                <div class="border-b p-5 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>Callback</div>
                        <div>
                            <button type="button" @click="hideModal()"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="defaultModal">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                v-for="key in Object.keys(selectedCallback['data'])">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    @{{ key }}
                                </th>
                                <td class="px-6 py-4">
                                    @{{ selectedCallback['data'][key] }}
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const callbacks = new Vue({
            el: '#callbacks',
            data: {
                selectedCallback: null,
                callbacks: JSON.parse(JSON.stringify({!! $order->callbacks !!})),
            },
            methods: {
                openModal: function(callbackId) {
                    let selectedCallback = this.callbacks.find(callback => callback.id ==
                        callbackId);

                    if (typeof selectedCallback['data'] !== 'object') {
                        selectedCallback['data'] = JSON.parse(selectedCallback.data);
                    }

                    this.selectedCallback = selectedCallback;
                },
                hideModal: function() {
                    this.selectedCallback = null;
                }
            }
        });
    </script>
@endsection
