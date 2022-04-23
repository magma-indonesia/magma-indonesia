<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page title -->
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-16x16.0.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.0.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.0.png') }}" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset('favicon-192x192.0.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('favicon-180x180.0.png') }}" sizes="180x180">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/metisMenu/dist/metisMenu.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/animate.css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />
    @yield('add-vendor-css')
    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" />
    <link rel="stylesheet" href="{{ asset('fonts/pe-icon-7-stroke/css/helper.css') }}" />
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/app.css') }}">

    @yield('add-css')
    {{-- Protect from Telkom Shit ad injection --}}
    <!-- </head> -->

    @if (config('app.debug') === false)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-213713124-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-213713124-1');
    </script>
    @endif
</head>