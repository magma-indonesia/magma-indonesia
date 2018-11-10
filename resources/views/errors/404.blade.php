<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>Halaman Tidak Ditemukan</title>
    <link href="{{ asset('favicon.png') }}" rel="shortcut icon">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/app.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/404.min.css') }}">

</head>

<body class="blank" style="line-height: 2em; background-color: #fff;">
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
            Halaman yang Anda cari tidak ditemukan.
        </h4>
        <h4 style="line-height: inherit;">
            Tinjau kembali URL yang Anda tuju, dan klik tombol refresh pada browser Anda.
        </h4>
        <br>
        <a href="{{ route('home') }}" class="btn btn-lg btn-magma">Kembali ke Halaman Awal</a>
        <hr>
    </div>
    <div class="error-container" style="text-align: center;padding: 0;line-height: 1.42857143;">
        <img style="max-width: 60px;margin-bottom: 15px;" src="{{ url('/') }}/images/logo/esdm.gif">
        <br>
        <strong>Badan Geologi, PVMBG</strong> - MAGMA Indonesia
        <br/> 2015 &copy; Kementerian Energi dan Sumber Daya Mineral
    </div>
</body>

</html>