<!-- Menu Mobile-->
<div class="menu-mobile menu-activated-on-click color-scheme-dark">
	<div class="mm-logo-buttons-w">
		<a class="mm-logo" href="index.html">
			<img src="{{ asset('img/logo.png') }}">
			<span>@yield('sub-title')</span>
		</a>
		<div class="mm-buttons">
			<div class="content-panel-open">
				<div class="os-icon os-icon-grid-circles"></div>
			</div>
			<div class="mobile-menu-trigger">
				<div class="os-icon os-icon-hamburger-menu-1"></div>
			</div>
		</div>
	</div>
	<div class="menu-and-user">
		<div class="logged-user-w">
			<div class="avatar-w">
				<img alt="" src="{{ asset('img/avatar1.jpg') }}">
			</div>
			<div class="logged-user-info-w">
				<div class="logged-user-name">
					Maria Gomez
				</div>
				<div class="logged-user-role">
					Administrator
				</div>
			</div>
		</div>
		<ul class="main-menu">
        @include('includes.submenu')
		</ul>
		<div class="mobile-menu-magic">
			<h4>
				Light Admin
			</h4>
			<p>
				Clean Bootstrap 4 Template
			</p>
			<div class="btn-w">
				<a class="btn btn-white btn-rounded" href="https://themeforest.net/item/light-admin-clean-bootstrap-dashboard-html-template/19760124?ref=Osetin"
				 target="_blank">Purchase Now</a>
			</div>
		</div>
	</div>
</div>