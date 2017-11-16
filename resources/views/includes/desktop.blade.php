<!-- Menu Desktop -->
<div class="desktop-menu menu-top-w menu-activated-on-hover">
	<div class="menu-top-i">
		<div class="logo-w">
			<a class="logo" href="index.html">
				<img src="{{ asset('img/logo.png') }}">
			</a>
		</div>
		<ul class="main-menu">
        @include('includes.submenu')
		</ul>
		<div class="logged-user-w">
			<div class="avatar-w">
				<img alt="" src="{{ asset('img/avatar1.jpg') }}">
			</div>
		</div>
	</div>
</div>