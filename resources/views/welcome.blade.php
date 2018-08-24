<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        <link href="{{ asset('favicon.png') }}" rel="shortcut icon">

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
                font-size: 36px;
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
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a>Halo, {{ auth()->user()->name }}</a>
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('logout') }}">Logout</a>
                    @else
                        {{--  <a href="{{ route('login') }}">Login</a>  --}}
                        {{--  <a href="{{ route('register') }}">Register</a>  --}}
                    @endauth
                </div>
            @endif

            <div class="content">
                <img alt="logo" class="p-m" src="{{ url('/').'/images/volcano.svg' }}" style="width: 120px;">
                <div class="title m-b-md">MAGMA v2</div>            
                <div id="time" class="title m-b-md">Loading . . .</div>

                <div class="links">
                    <a href="{{ route('login') }}">Login</a>
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
