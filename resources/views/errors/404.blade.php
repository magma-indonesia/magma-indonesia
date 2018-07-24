<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>Not Found</title>
    <link href="{{ asset('favicon.png') }}" rel="shortcut icon">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />

    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" />
    <link rel="stylesheet" href="{{ asset('fonts/pe-icon-7-stroke/css/helper.css') }}" />
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/app.css') }}">

</head>
<body class="blank">
<div class="error-container">
    <i class="pe-7s-way text-danger big-icon"></i>
    <h1>{{ $exception->getStatusCode() }}</h1>
    <h4>Maaf Halaman Tidak Ditemukan</h4>
    <hr>
    <h5 style="line-height: inherit;">
        Halaman yang Anda cari tidak ditemukan. Coba tinjau kembali URL yang Anda tuju, dan coba klik tombol refresh pada browser Anda.
    </h5>
    <hr>
    <a href="{{ route('home') }}" class="btn btn-magma">Kembali ke Halaman Awal</a>
</div>


<!-- Vendor scripts -->
<script src="{{ asset('vendorjquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendorbootstrap/dist/js/bootstrap.min.js') }}"></script>

</body>
</html>
