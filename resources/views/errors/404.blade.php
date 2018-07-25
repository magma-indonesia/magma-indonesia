<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>Not Found</title>
    <link href="{{ asset('favicon.png') }}" rel="shortcut icon">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/app.css') }}">

    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:800);

        #g-usetag {
            stroke-width: 6;
            stroke: #F2385A;
            animation: gstrokeanim 2s infinite linear;
        }

        @keyframes gstrokeanim {
            0% {
                stroke-width: 6;
            }
            50% {
                stroke-width: 0;
            }
            100% {
                stroke-width: 6;
            }

        }


        .text {

            fill: none;
            stroke-width: 6;
            stroke-linejoin: round;
            stroke-dasharray: 70 330;
            stroke-dashoffset: 0;
            -webkit-animation: stroke 6s infinite linear;
            animation: stroke 6s infinite linear;
        }

        .text:nth-child(5n + 1) {
            stroke: #F2385A;
            -webkit-animation-delay: -1.2s;
            animation-delay: -1.2s;
        }

        .text:nth-child(5n + 2) {
            stroke: #F5A503;
            -webkit-animation-delay: -2.4s;
            animation-delay: -2.4s;
        }

        .text:nth-child(5n + 3) {
            stroke: #E9F1DF;
            -webkit-animation-delay: -3.6s;
            animation-delay: -3.6s;
        }

        .text:nth-child(5n + 4) {
            stroke: #56D9CD;
            -webkit-animation-delay: -4.8s;
            animation-delay: -4.8s;
        }

        .text:nth-child(5n + 5) {
            stroke: #007fff;
            -webkit-animation-delay: -6s;
            animation-delay: -6s;
        }


        @-webkit-keyframes stroke {
            100% {
                stroke-dashoffset: -400;
            }
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: -400;
            }
        }

        /*v animation*/

        .text1 {

            fill: none;

            stroke-linejoin: round;
            stroke-dasharray: 1160;
            stroke-dashoffset: 1160;
            -webkit-animation: vstroke 6s infinite linear;
            animation: vstroke 6s infinite ease-in-out;

        }

        .text1:nth-child(3n+3) {
            stroke-width: 6;
            stroke: #007fff;
            -webkit-animation-delay: 0s;
            animation-delay: 0s;
        }

        .text1:nth-child(3n+1) {
            stroke: yellow;
            stroke-width: 6;
            -webkit-animation-delay: .2s;
            animation-delay: -.2s;
        }

        .text1:nth-child(3n+2) {
            stroke: red;
            stroke-width: 6;
            -webkit-animation-delay: -.1s;
            animation-delay: -.1s;
        }

        @keyframes vstroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        html, body {
            height: 100%;
        }

        body {
            background: #f1f3f6;
            background-size: .2em 100%;
            margin: 0;
        }

        svg {
            font: 25.5em/1 Open Sans, Impact;
            text-transform: uppercase;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .error-container {
            max-width: 50%;
            margin: auto;
            padding: 6%;
        }

        @media (max-width: 620px) {
            .error-container {
                margin: auto 10px;
                max-width: 90%;
            }
        }
    </style>

</head>

<body class="blank">
    <div class="error-container" style="text-align: center;padding-bottom: 0;">
        <svg viewBox="0 0 1320 300">
            <!--pattern-->
            <defs>
                <pattern id="GPattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse" patternTransform="rotate(35)">
                    <animateTransform attributeType="xml" attributeName="patternTransform" type="rotate" from="35" to="395" begin="0" dur="160s"
                        repeatCount="indefinite" />
                    <circle cx="10" cy="10" r="10" stroke="none" fill="#007fff">
                        <animate attributeName="r" type="xml" from="1" to="1" values="1; 10; 1" begin="0s" dur="2s" repeatCount="indefinite" />
                    </circle>
                </pattern>
            </defs>

            <!-- Symbol -->
            <symbol id="s-text">
                <text text-anchor="middle" x="35%" y="50%" dy=".35em">
                    4
                </text>
            </symbol>
            <symbol id="v-text">
                <text text-anchor="middle" x="50%" y="50%" dy=".35em">
                    0
                </text>
            </symbol>
            <symbol id="g-text">
                <text text-anchor="middle" x="65%" y="50%" dy=".35em">
                    4
                </text>
            </symbol>
            <!-- Duplicate symbols -->
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#v-text" class="text1">

            </use>
            <use xlink:href="#v-text" class="text1"></use>
            <use xlink:href="#v-text" class="text1"></use>


            <use id="g-usetag" xlink:href="#g-text" class="text2" style="fill: url(#GPattern)" /></use>

        </svg>
        <h2>Halaman Tidak Ditemukan</h2>
        <hr>
        <h4 style="line-height: inherit;">
            Halaman yang Anda cari tidak ditemukan. Coba tinjau kembali URL yang Anda tuju, dan coba klik tombol refresh pada browser
            Anda.
        </h4>
        <br>
        <a href="{{ route('home') }}" class="btn btn-magma">Kembali ke Halaman Awal</a>
        <hr>
    </div>
    <div class="error-container" style="text-align: center;padding: 0;">
        <img style="max-width: 60px;margin-bottom: 15px;" src="{{ url('/') }}/images/logo/esdm.gif">
        <br>
        <strong>Badan Geologi, PVMBG</strong> - MAGMA Indonesia
        <br/> 2015 &copy; Kementerian Energi dan Sumber Daya Mineral
    </div>
</body>

</html>