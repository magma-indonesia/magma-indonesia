<div class="slim-mainpanel @yield('bg-color')">
    <div class="@yield('container', 'container')">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('v1.home') }}">Home</a></li>
                @yield('breadcrumb')
            </ol>
            <h6 class="slim-pagetitle">@yield('page-title')</h6>
        </div>

        @yield('main')
    </div>
</div>