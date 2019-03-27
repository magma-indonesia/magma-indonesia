<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('includes.slim-head')

<body>

    <!-- Header -->
    @include('includes.slim-header')

    <!-- Navbar -->
    @include('includes.slim-navbar')

    <!-- Main Panel -->
    @include('includes.slim-main')

    <!-- Footer -->
    @include('includes.slim-footer')

    <!-- Vendor scripts -->
    @include('includes.slim-script')

</body>
</html>