<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>VONA - {{ strtoupper($vona->gunungapi->name) }} {{ $vona->issued_utc }}</title>
    <style>
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }

        table td {
            font-family: sans-serif;
            font-size: 12px;
            vertical-align: top;
        }

        .table td {
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .body {
            width: 100%;
        }

        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 780px;
            padding: 10px;
            width: 780px;
        }

        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 780px;
            padding: 10px;
        }

        .main {
            background: #ffffff;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }

        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 24px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 12px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }

        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }

        .btn {
            box-sizing: border-box;
            width: 100%;
        }

        .btn>tbody>tr>td {
            padding-bottom: 15px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: solid 1px #3498db;
            border-radius: 5px;
            box-sizing: border-box;
            color: #3498db;
            cursor: pointer;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary table td {
            background-color: #3498db;
        }

        .btn-primary a {
            background-color: #3498db;
            border-color: #3498db;
            color: #ffffff;
        }

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid #f6f6f6;
            margin: 20px 0;
        }

        @media all {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 12px !important;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 4px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table[class=body] .btn table {
                width: 100% !important;
            }

            table[class=body] .btn a {
                width: 100% !important;
            }

            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }
    </style>
</head>

<body class="">
    <span class="preheader">VONA - {{ $vona->ga_code }} {{ $vona->issued }}</span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td>&nbsp;</td>
            <td class="container">
                <div class="content">
                    <table role="presentation" class="main">
                        <tr>
                            <td class="wrapper">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            {{-- VONA Table --}}
                                            <table class="table">
                                                <tr style="background-color: rgba(0, 0, 0, 0.03);">
                                                    <img src="{{ asset('email-vona-header.png') }}" alt="Header VONA">
                                                </tr>
                                                <tr style="background-color: rgba(0, 0, 0, 0.03);">
                                                    <td colspan="3">
                                                        @if ($vona->type === 'EXERCISE')
                                                        <h2 style="margin-bottom: 10px"><b>VA EXERCISE APAC VOLCEX
                                                                22/01</b></h2>
                                                        @else
                                                        <h2 style="margin-bottom: 10px"><b>{{
                                                                strtoupper($vona->gunungapi->name) }} {{
                                                                $vona->issued_utc }}</b></h2>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="word-wrap:break-word;">(1) <b>VOLCANO OBSERVATORY
                                                            NOTICE FOR AVIATION - VONA</b></td>
                                                </tr>
                                                <tr>
                                                    <td>(2) <b>Issued</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $vona->issued_utc }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(3) <b>Volcano</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $vona->gunungapi->name.'
                                                        ('.$vona->gunungapi->smithsonian_id.')' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(4) <b>Current Aviation Colour Code</b></td>
                                                    <td><b>:</b></td>
                                                    <td><b>{{ $vona->current_code }}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>(5) <b>Previous Aviation Colour Code</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $vona->previous_code }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(6) <b>Source</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $vona->gunungapi->name }} Volcano Observatory</td>
                                                </tr>
                                                <tr>
                                                    <td>(7) <b>Notice Number</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $vona->noticenumber }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(8) <b>Volcano Location</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $location }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(9) <b>Area</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $vona->gunungapi->province_en }}, Indonesia</td>
                                                </tr>
                                                <tr>
                                                    <td>(10) <b>Summit Elevation</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ round($vona->gunungapi->elevation*3.2) }} FT ({{
                                                        $vona->gunungapi->elevation }} M)</td>
                                                </tr>
                                                <tr>
                                                    <td>(11) <b>Volcanic Activity Summary</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $volcano_activity_summary }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(12) <b>Volcanic Cloud Height</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $volcanic_cloud_height }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(13) <b>Other Volcanic Cloud Information</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ $other_volcanic_cloud_information }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(14) <b>Remarks</b></td>
                                                    <td><b>:</b></td>
                                                    <td>{{ blank($remarks) ? '-' : $remarks }}
                                                        @if ($vona->old_ven_uuid)
                                                        <br>
                                                        <a href="{{ route('v1.gunungapi.ven.show', $ven->uuid) }}"><img style="max-height: 100px" src="{{ $ven->erupt_pht }}" alt="Header VONA"></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>(15) <b>Contacts</b></td>
                                                    <td><b>:</b></td>
                                                    <td>Center for Volcanology and Geological Hazard Mitigation
                                                        (CVGHM).<br> Tel: +62-22-727-2606.<br> Facsimile:
                                                        +62-22-720-2761.<br> email : pvmbg@esdm.go.id</td>
                                                </tr>
                                                <tr>
                                                    <td>(16) <b>Next Notice</b></td>
                                                    <td><b>:</b></td>
                                                    <td>A new VONA will be issued if conditions change significantly or
                                                        the colour code is changes. Latest volcanic information is
                                                        posted at <b>VONA | MAGMA
                                                            Indonesia</b> Website.<br>
                                                        Link: <a href="{{ route('vona.index')}}">{{
                                                            route('vona.index')}}</a>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: rgba(0, 0, 0, 0.03);">
                                                    <td colspan="4">
                                                        @if ($vona->type === 'EXERCISE')
                                                        <h2 style="margin-bottom: 10px"><b>VA EXERCISE VA EXERCISE VA
                                                                EXERCISE</b></h2>
                                                        @else
                                                        <h2 style="margin-bottom: 10px"><b>{{
                                                                strtoupper($vona->gunungapi->name) }} {{
                                                                $vona->issued_utc }}</b></h2>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>

                                            <p style="margin-top: 1em">Please do not reply to this message. Replies to
                                                this message are routed to an unmonitored mailbox. If you have questions
                                                please email us at <a
                                                    href="mailto:pvmbg@esdm.go.id">pvmbg@esdm.go.id</a>. You may also
                                                call us at +62-22-727-2606</p>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                    </table>
                    <!-- END CENTERED WHITE CONTAINER -->

                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
</body>

</html>