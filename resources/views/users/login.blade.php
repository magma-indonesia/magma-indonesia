<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Page title -->
	<title>Login</title>
	<meta name="twitter:image" content="{{ asset('snapshot.png') }}">
	<meta property="og:image" content="{{ asset('snapshot.png') }}">
	<meta property="og:image:secure_url" content="{{ asset('snapshot.png') }}">
	<meta name="description" content="MAGMA Indonesia | Bridging the Will of Nature to Society">
    <meta name="author" content="Kementerian Energi dan Sumber Daya Mineral">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-16x16.0.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.0.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.0.png') }}" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset('favicon-192x192.0.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('favicon-180x180.0.png') }}" sizes="180x180">
    <link rel="dns-prefetch" href="{{ config('app.url') }}">

	<!-- Vendor styles -->
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />

	<!-- App styles -->
	<link rel="stylesheet" href="{{ asset('styles/style.css') }}">
	<link rel="stylesheet" href="{{ asset('styles/app.css') }}">

	<style>
		.full-img {
			background-image: url('/svg/login.svg');
			position: absolute;
			top: 0;
			bottom: 0;
			background-size: cover;
			background-repeat: no-repeat;
			background-position: left;
			min-height: 100vh;
			width: 100%;
		}
	</style>

</head>

<body class="blank">

	@include('includes.loader')
	<div class="row">
		<div class="col-lg-6 hidden-xs hidden-sm hidden-md">
			<div class="full-img"></div>
		</div>
		<div class="col-lg-6">
			<div class="login-container" style="padding-top: 25%;>
				<div class="row">
					<div class="col-md-12">
						<div class="text-center m-b-md">
							<img alt="logo" class="p-m" src="{{ url('/').'/svg/login-logo.svg' }}" style="width: 240px;">
							<h2>Login MAGMA</h2>
						</div>
						<div class="hpanel">
							<div class="panel-body" style="background: transparent;border: 0;">
								<form method="POST" action="{{ route('login.post') }}" id="loginForm">
									@csrf
									<div class="form-group">
										<label class="control-label" for="username">NIP/Email</label>
										<input type="text" placeholder="Masukkan NIP atau email Anda" title="NIP/Email" name="username" id="username" class="form-control"
											required>
									</div>
									<div class="form-group">
										<label class="control-label" for="password">Password</label>
										<input type="password" title="Password" placeholder="******" name="password" id="password"
											class="form-control" required>
									</div>

									@if(count($errors) > 0)
									<div class="form-group">
										<div class="alert alert-danger">
											<ul class="list-unstyled">
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									</div>
									@endif

									<button type="submit" class="btn btn-magma btn-block">Login</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<img style="max-width: 60px;" src="{{ url('/') }}/images/logo/esdm.gif">
						<br>
						<strong>Badan Geologi, PVMBG</strong> - MAGMA Indonesia
						<br/> {{ now()->format('Y') }} &copy; Kementerian Energi dan Sumber Daya Mineral
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Vendor scripts -->
	<script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

	<!-- App scripts -->
	<script src="{{ asset('scripts/login.js') }}"></script>

</body>

</html>