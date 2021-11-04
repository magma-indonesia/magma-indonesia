<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#007fff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="magma,esdm,bencana,gunungapi,pvmbg,badan geologi,vona,gempabumi,volcano" />

    <!-- Page title -->
    <title>@yield('title', config('app.name'))</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-16x16.0.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.0.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.0.png') }}" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset('favicon-192x192.0.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('favicon-180x180.0.png') }}" sizes="180x180">
    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <link rel="dns-prefetch" href="https://magma.vsi.esdm.go.id/">
    <link itemprop="thumbnailUrl" href="@yield('thumbnail', asset('snapshot.png'))">

    <!-- Twitter -->
    <meta name="twitter:site" content="@id_magma">
    <meta name="twitter:creator" content="@KementerianESDM">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.tag_line'))">
    <meta name="twitter:description" content="{{ config('app.name') }}">
    <meta name="twitter:image" content="@yield('thumbnail', asset('snapshot.png'))">

    <!-- Facebook -->
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="@yield('title', config('app.tag_line'))">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:image" content="@yield('thumbnail', asset('snapshot.png'))">
    <meta property="og:image:secure_url" content="@yield('thumbnail', asset('snapshot.png'))">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Meta -->
    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="Kementerian Energi dan Sumber Daya Mineral">

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