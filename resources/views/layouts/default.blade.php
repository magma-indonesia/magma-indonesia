<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('includes.head')

<body class="fixed-navbar fixed-sidebar">

    <!-- Simple splash screen-->
    @include('includes.loader')

    <!-- Header -->
    @include('includes.header')

    {{--  Navigasi  --}}
    @include('includes.navigation')

    {{--  Content wrapper  --}}
    <div id="wrapper">
    @yield('content-header')
    @yield('content-body')
    @include('includes.footer')
    </div>

<!-- Vendor scripts -->
@include('includes.script')

</body>

</html>