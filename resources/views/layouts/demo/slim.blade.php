<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('includes.demo.slim-head')

<body>

    <!-- Header -->
    @include('includes.demo.slim-header')

    <!-- Navbar -->
    @include('includes.demo.slim-navbar')

    <!-- Main Panel -->
    @include('includes.demo.slim-main')

    <!-- Footer -->
    @include('includes.demo.slim-footer')

    <!-- Vendor scripts -->
    @include('includes.demo.slim-script')

</body>
</html>