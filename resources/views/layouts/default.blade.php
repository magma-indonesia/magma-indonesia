<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('includes.head')

<body>
    <div class="all-wrapper menu-top">
        <div class="layout-w">
            @include('includes.mobile')
            @include('includes.desktop')
            <!-- Content -->
            <div class="content-w">
                @yield('breadcrumb')

                <div class="content-i">
                    <div class="content-box">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <div class="display-type"></div>
    </div>
    
    @include('includes.js')

</body>

</html>