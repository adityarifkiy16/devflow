<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icons/fontawesome/styles.min.css') }}">

    <!-- Global Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/scss/app.scss'])
</head>

<body>
    <!-- Main Content -->
    @yield('content')
    <!-- Core JS -->
    <script src="{{ asset('assets/js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.0.4') }}"></script>
    @stack('script')
</body>

</html>