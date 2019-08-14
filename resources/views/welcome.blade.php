<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#007fff">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="description" content="MAGMA Indonesia - Bridging the will of nature to society">
        <meta name="author" content="Kementerian ESDM">
        <link href="{{ asset('favicon.ico') }}" rel="shortcut icon">
        <link rel="dns-prefetch" href="{{ config('app.url') }}">
        <link rel="dns-prefetch" href="https://magma.vsi.esdm.go.id/">
        <title>{{ config('app.name') }} - {{ config('app.tag_line') }}</title>

        <!-- Twitter -->
        <meta name="twitter:site" content="@id_magma">
        <meta name="twitter:creator" content="@KementerianESDM">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name') }}">
        <meta name="twitter:description" content="{{ config('app.tag_line') }}">
        <meta name="twitter:image" content="{{ asset('snapshot.png') }}">

        <!-- Facebook -->
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:description" content="{{ config('app.tag_line') }}">
        <meta property="og:image" content="{{ asset('snapshot.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('snapshot.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <!-- Styles -->
        <style>
            /* raleway-100 - latin */
            @font-face {
            font-family: 'Raleway';
            font-style: normal;
            font-weight: 100;
            src: url('/fonts/raleway-v12-latin-100.eot'); /* IE9 Compat Modes */
            src: local('Raleway Thin'), local('Raleway-Thin'),
                url('/fonts/raleway-v12-latin-100.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
                url('/fonts/raleway-v12-latin-100.woff2') format('woff2'), /* Super Modern Browsers */
                url('/fonts/raleway-v12-latin-100.woff') format('woff'), /* Modern Browsers */
                url('/fonts/raleway-v12-latin-100.ttf') format('truetype'), /* Safari, Android, iOS */
                url('/fonts/raleway-v12-latin-100.svg#Raleway') format('svg'); /* Legacy iOS */
            }

            /* raleway-600 - latin */
            @font-face {
            font-family: 'Raleway';
            font-style: normal;
            font-weight: 600;
            src: url('/fonts/raleway-v12-latin-600.eot'); /* IE9 Compat Modes */
            src: local('Raleway SemiBold'), local('Raleway-SemiBold'),
                url('/fonts/raleway-v12-latin-600.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
                url('/fonts/raleway-v12-latin-600.woff2') format('woff2'), /* Super Modern Browsers */
                url('/fonts/raleway-v12-latin-600.woff') format('woff'), /* Modern Browsers */
                url('/fonts/raleway-v12-latin-600.ttf') format('truetype'), /* Safari, Android, iOS */
                url('/fonts/raleway-v12-latin-600.svg#Raleway') format('svg'); /* Legacy iOS */
            }
            
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 24px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <img alt="logo" class="p-m m-b-md" src="{{ url('/').'/svg/login-logo.svg' }}" style="width: 240px;">
                <div class="title m-b-sm">MAGMA v2</div>            
                <div id="time" class="title m-b-md">Loading . . .</div>

                <div class="links">
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('v1.home') }}">Magma v1</a>
                </div>
            </div>
        </div>
        <script>

        function showTime() {
            var utc = new Date();
            document.getElementById('time').innerHTML = utc.toLocaleString('id-ID', { hour12: false, timeZone: 'Asia/Jakarta' })+' WIB';
        }

        setInterval(showTime, 1000);
        </script>
    </body>
</html>
