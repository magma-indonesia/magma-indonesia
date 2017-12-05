    <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ asset('vendor/sparkline/index.js') }}"></script>
    <script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>

    
    @yield('add-vendor-script')
    <!-- App scripts -->
    <script src="{{ asset('scripts/homer.js') }}"></script>

    @yield('add-script')