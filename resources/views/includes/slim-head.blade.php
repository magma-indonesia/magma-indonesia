<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page title -->
    <title>@yield('title')</title>
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon">
    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <link rel="dns-prefetch" href="https://magma.vsi.esdm.go.id/">

    <!-- Twitter -->
    <meta name="twitter:site" content="@id_magma">
    <meta name="twitter:creator" content="@KementerianESDM">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name') }}">
    <meta name="twitter:description" content="{{ config('app.tag_line') }}">
    <meta name="twitter:image" content="{{ asset('snapshot.png') }}">

    <!-- Facebook -->
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.tag_line') }}">
    <meta property="og:image" content="{{ asset('snapshot.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('snapshot.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Meta -->
    <meta name="description" content="{{ config('app.tag_line') }}">
    <meta name="author" content="Kementerian ESDM">

    <!-- vendor css -->
    <link href="{{ asset('slim/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('slim/lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">
    @yield('add-vendor-css')

    <!-- Slim CSS -->
    <link rel="stylesheet" href="{{ asset('css/icon-magma.css') }}">
    <link rel="stylesheet" href="{{ asset('slim/css/slim.css') }}">
    <link rel="stylesheet" href="{{ asset('slim/css/slim-magma.css') }}">
    
    @yield('add-css')
</head>