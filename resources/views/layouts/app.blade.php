<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CoinGate Example</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex">
                <a class="navbar-brand" href="{{ route('home') }}">CoinGate Example</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}">Home <span
                                    class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item {{ Request::is('orders') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('orders') }}">Order List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div>
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ Request::is('settings') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('settings.index') }}">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
    @yield('scripts')
</div>
</body>
</html>
