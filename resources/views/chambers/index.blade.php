@extends('layouts.default')

@section('title')
    {{ config('app.name') }}
@endsection

@section('content-body')   
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12 text-center m-t-md">
            <h2>
                Selamat Datang di MAGMA Chamber
            </h2>

            <p>
                <strong class="text-magma">MAGMA Indonesia </strong>(Multiplatform Application for Geohazard Mitigation and Assessment in Indonesia) adalah aplikasi multiplatform (web & mobile) dalam jaringan berisikan informasi dan rekomendasi kebencanaan geologi terintegrasi (gunungapi, gempabumi, tsunami, dan gerakan tanah) yang disajikan kepada masyarakat secara kuasi-realtime dan interaktif.
            </p>
            @include('includes.alert')
        </div>
    </div>

    <div class="row">
        <div class="col-md-3" style="">
            <div class="hpanel hbgred">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Level IV (Awas)</h3>
                        <p class="text-big font-light">
                            {{ $gadds->where('ga_status',4)->count() }}
                        </p>
                        <small>
                        @if($gadds->where('ga_status',4)->count())
                        Pada saat ini terdapat {{$gadds->where('ga_status',4)->count()}} gunung api dengan aktivitas Level IV (Awas)
                        @else
                        Pada saat ini tidak ada gunung api dengan aktivitas Level IV (Awas)
                        @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" style="">
            <div class="hpanel hbgorange">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Level III (Siaga)</h3>
                        <p class="text-big font-light">
                            {{ $gadds->where('ga_status',3)->count() }}
                        </p>
                        <small>
                        @if($gadds->where('ga_status',3)->count())
                        Pada saat ini terdapat {{$gadds->where('ga_status',3)->count()}} gunung api dengan aktivitas Level III (Siaga)
                        @else
                        Pada saat ini tidak ada gunung api dengan aktivitas Level III - (Siaga)
                        @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" style="">
            <div class="hpanel hbgyellow">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Level II (Waspada)</h3>
                        <p class="text-big font-light">
                            {{ $gadds->where('ga_status',2)->count() }}
                        </p>
                        <small>
                        @if($gadds->where('ga_status',2)->count())
                        Pada saat ini terdapat {{$gadds->where('ga_status',2)->count()}} gunung api dengan aktivitas Level II (Waspada)
                        @else
                        Pada saat ini tidak ada gunung api dengan aktivitas Level II - (Waspada)
                        @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" style="">
            <div class="hpanel hbggreen">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Level I (Normal)</h3>
                        <p class="text-big font-light">
                            {{ $gadds->where('ga_status',1)->count() }}
                        </p>
                        <small>
                        @if($gadds->where('ga_status',1)->count())
                        Pada saat ini terdapat {{$gadds->where('ga_status',1)->count()}} gunung api dengan aktivitas Level I (Normal)
                        @else
                        Pada saat ini tidak ada gunung api dengan aktivitas Level I - (Normal)
                        @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel collapsed">
                <div class="panel-heading hbuilt">
                    <div class="panel-tools">
                        <a class="showhide">
                            <i class="fa fa-chevron-up"></i> Detail
                        </a>
                    </div>
                    Tingkat Aktivitas Gunung Api
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table" cellspacing="1" cellpadding="1">
                            <thead>
                                <tr>
                                    <th>Tingkat Aktivitas</th>
                                    <th>Jumlah</th>
                                    <th>Gunung Api</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Level IV (Awas)</td>
                                    <td>{{ $gadds->where('ga_status',4)->count() }}</td>
                                    <td>
                                        @if($gadds->where('ga_status',4)->count())
                                        <ul>
                                            @foreach ($gadds->where('ga_status',4)->all() as $key => $gadd)
                                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        Tidak ada gunung api Level IV - (Awas)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Level III (Siaga)</td>
                                    <td>{{ $gadds->where('ga_status',3)->count() }}</td>
                                    <td>
                                        @if($gadds->where('ga_status',3)->count())
                                        <ul>
                                            @foreach ($gadds->where('ga_status',3)->all() as $key => $gadd)
                                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        Tidak ada gunung api Level III - (Siaga)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Level II (Waspada)</td>
                                    <td>{{ $gadds->where('ga_status',2)->count() }}</td>
                                    <td>
                                        @if($gadds->where('ga_status',2)->count())
                                        <ul>
                                            @foreach ($gadds->where('ga_status',2)->all() as $key => $gadd)
                                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        Tidak ada gunung api Level II - (Waspada)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Level I (Normal)</td>
                                    <td>{{ $gadds->where('ga_status',1)->count() }}</td>
                                    <td>
                                        @if($gadds->where('ga_status',1)->count())
                                        <ul>
                                            @foreach ($gadds->where('ga_status',1)->all() as $key => $gadd)
                                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        Tidak ada gunung api Level 1 - (Normal)
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel collapsed">
                <div class="panel-heading hbuilt">
                    <div class="panel-tools">
                        <a class="showhide">
                            <i class="fa fa-chevron-up"></i> Detail
                        </a>
                    </div>
                    Informasi dan Statistik Pengunjung Magma
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div id="container" style="min-width: 310px; height: 360px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="small">
                                <i class="fa fa-clock-o"></i> Jumlah Pengunjung
                            </div>
                            <div>
                                <h1 class="font-extra-bold m-t-xl m-b-xs">
                                    {{ number_format($statistics_sum,0,',','.') }}
                                </h1>
                                <small>Pengunjung</small>
                            </div>
                            <div class="small m-t-xl">
                                <i class="fa fa-clock-o"></i> Periode 28 Nov 2019 - {{ now()->formatLocalized('%d %b %Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    Last update: {{ now()->formatLocalized('%d %b %Y') }}
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="small">
                                <i class="fa fa-bolt"></i> Jumlah Data MAGMA-VAR
                            </div>
                            <div>
                                <h1 class="font-extra-bold m-t-xl m-b-xs">
                                        {{ number_format($vars_count,0,',','.') }}
                                </h1>
                                <small>Laporan Gunung Api</small>
                            </div>
                            <div class="small m-t-xl">
                                <i class="fa fa-clock-o"></i> Data dari Mei 2015
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    Last update: {{ $latest->updated_at->formatLocalized('%d %B %Y Pukul %T WIB') }}
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="small">
                                <i class="fa fa-clock-o"></i> Jumlah Data Letusan
                            </div>
                            <div>
                                <h1 class="font-extra-bold m-t-xl m-b-xs text-danger">
                                    {{ number_format($lts_sum,0,',','.') }}
                                </h1>
                                <small>Gempa Letusan</small>
                            </div>
                            <div class="small m-t-xl">
                                <i class="fa fa-clock-o"></i> Hingga {{ $latest_lts->updated_at->formatLocalized('%d %B %Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    Last update: {{ $latest_lts->updated_at->formatLocalized('%d %B %Y') }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('add-vendor-script')

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        var data = @json($statistics_chart);

        Highcharts.chart('container', {
            chart: {
                zoomType: 'x',
                type: 'column',
            },
            credits: {
                enabled: true,
                text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
            },
            title: {
                text: 'Pengunjung MAGMA v2 - 60 Hari terakhir '
            },
            xAxis: {
                categories: data.categories,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Pengunjung'
                },
                allowDecimals: false
            },
            tooltip: {
                enabled: true,
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{point.y}'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: data.series,
            exporting: {
                enabled: true,
                scale: 1,
                sourceHeight: 720,
                sourceWidth: 1080
            },
            legend: {
                enabled: false,
            },
        });
    });
</script>
@endsection