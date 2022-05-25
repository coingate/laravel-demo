@extends('layout.app')

@section('content')

<div>
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <div class="bg-gray-100 dark:bg-gray-700 rounded p-6">
                <div class="flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-center mt-4">
                    <h2 class="text-lg font-semibold dark:text-white">
                        Error!
                    </h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-200">
                        Your order has been failed.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="flex justify-center mt-5">
        <a href="{{ url('/') }}" class="dark:text-gray-400 dark:hover:text-gray-200 text-gray-600 hover:text-gray-800">
            Go to Home
        </a>
    </div>
</div>

@endsection
