<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="geohazard" name="keywords">
    <meta content="CVGHM | ESDM" name="author">
    <meta content="MAGMA Indonesia Login Form" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="favicon.png" rel="shortcut icon">
    <link href="//fast.fonts.net/cssapi/175a63a1-3f26-476a-ab32-4e21cbdb8be2.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/main.css?version=2.6') }}" rel="stylesheet">
</head>

<body class="auth-wrapper">
    <div class="all-wrapper menu-side with-pattern">
        <div class="auth-box-w">
            <div class="logo-w">
                <a href="index.html">
                    <img alt="" src="img/logo-big.png">
                </a>
            </div>
            <h4 class="auth-header">
                Login Form
            </h4>
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="">NIP / Email</label>
                    <input  name="username" class="form-control" placeholder="Masukkan NIP/Email" type="text" required>
                    <div class="pre-icon os-icon os-icon-user-male-circle"></div>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input  name="password" class="form-control" placeholder="Password" type="password" required>
                    <div class="pre-icon os-icon os-icon-fingerprint"></div>
                </div>

            @if (count($errors) > 0)
                <div class="form-group {{ count($errors) > 0 ? 'has-error has-danger' : '' }}">
                    <div class="help-block form-text with-errors form-control-feedback">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
                <div class="buttons-w">
                    <button type="submit" class="btn btn-primary">Login MAGMA</button>
                    {{--  <div class="form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox">Remember Me</label>
                    </div>  --}}
                </div>
            </form>
        </div>
    </div>
</body>

</html>