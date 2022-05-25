<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CoinGate Demo Shop</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.6/dist/flowbite.min.css"/>
</head>
<body class="dark:bg-gray-900">

@include('layout.header')

<div class="container mx-auto mt-10">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://unpkg.com/flowbite@1.4.6/dist/flowbite.js"></script>

@yield('scripts')
</body>
</html>
