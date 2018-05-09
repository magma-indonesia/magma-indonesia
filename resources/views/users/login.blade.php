<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta key-magma="{{ str_random(40) }}">
	<!-- Page title -->
	<title>Login</title>

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link href="{{ asset('favicon.png') }}" rel="shortcut icon">

	<!-- Vendor styles -->
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />

	<!-- App styles -->
	<link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/app.css') }}">

</head>

<body class="blank">

    @include('includes.loader')

	<div class="color-line"></div>

	<div class="login-container">
		<div class="row">
			<div class="col-md-12">
				<div class="text-center m-b-md">
					<h2>Login</h2>
				</div>
				<div class="hpanel">
					<div class="panel-body">
						<form method="POST" action="{{ route('login') }}" id="loginForm">
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

							<button type="submit" class="btn btn-success btn-block">Login</button>
							<a class="btn btn-default btn-block" href="{{ route('chambers.users.create') }}">Register</a>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<strong>PVMBG</strong> - MAGMA Indonesia
				<br/> 2015 Copyright Kementerian Energi dan Sumber Daya Mineral
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