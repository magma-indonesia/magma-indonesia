<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Karvak Nusak Geomatika">
    <meta name="author" content="Karvak Nusak Geomatika">

    <title>Demo Login</title>

    <!-- vendor css -->
    <link href="{{ asset('slim/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('slim/lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">
    @yield('add-vendor-css')

    <!-- Slim CSS -->
    <link rel="stylesheet" href="{{ asset('slim/css/slim.css') }}">
    @yield('add-css')

</head>

<body>
    <div class="signin-wrapper">
        <div class="signin-box">
            <h2 class="slim-logo"><a href="#">Demo<span>.</span></a></h2>
            <h2 class="signin-title-primary">Selamat Datang!</h2>
            <h3 class="signin-title-secondary">Silahkan login untuk melanjutkan.</h3>

            <form method="POST" action="{{ route('demo.nusa-geomatika.login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <input name="username" type="text" class="form-control" placeholder="Gunakan user Demo">
                </div>
                <div class="form-group mg-b-50">
                    <input name="password" type="password" class="form-control" placeholder="password Demo">
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-signin">Sign In</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('slim/lib/jquery/js/jquery.js') }}"></script>
    <script src="{{ asset('slim/lib/popper.js/js/popper.js') }}"></script>
    <script src="{{ asset('slim/lib/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('slim/js/slim.js') }}"></script>
    @yield('add-vendor-script')
    @yield('add-script')
</body>

</html>