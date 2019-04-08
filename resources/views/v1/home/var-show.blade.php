@extends('layouts.slim') 

@section('title')
Laporan Aktivitas - {{ $var->gunungapi }}
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/SpinKit/css/spinkit.css') }}" rel="stylesheet">
@endsection
    
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Laporan Aktivitas</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}</li>
@endsection

@section('page-title')
Laporan Aktivitas
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">

        <div class="card card-blog bd-0">
            <img class="img-fluid" src="{{ $var->foto }}" alt="{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}">
            <div class="card-body pd-30 bd bd-t-0">
                <h5 class="blog-category">
                    @switch($var->status)
                        @case('1')
                            <h5><span class="badge badge-success">Level I (Normal)</span></h5>
                            @break
                        @case('2')
                            <h5><span class="badge badge-warning tx-white">Level II (Waspada)</span></h5>
                            @break
                        @case('3')
                            <h5><span class="badge bg-orange tx-white">Level III (Siaga)</span></h5>
                            @break
                        @default
                            <h5><span class="badge badge-danger">Level IV (Awas)</span></h5>
                            @break
                    @endswitch
                </h5>
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}, periode {{ $var->periode }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">Dibuat oleh,  {{ $var->pelapor }}</p>
                <br>
                <p class="col-lg-6 pd-0">{!! $var->intro !!}</p>
            </div>
        </div>

        <div class="card-columns column-count-2 mg-t-20">
            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-image tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Pengamatan Visual</h6>
                        <p>{{ $var->visual }}</p>
                    </div>
                </div>
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-area-chart tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Keterangan Lainnya</h6>
                        <p>{{ $var->visual_lainnya }}</p>
                    </div>
                </div>
            </div>

            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-cloud tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Klimatologi</h6>
                        <p>{!! $var->klimatologi !!}</p>
                    </div>
                </div>
            </div>

            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-area-chart tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Pengamatan Kegempaan</h6>
                        @if (empty($var->gempa))
                        <p>Nihil</p>
                        @else
                        @foreach ($var->gempa as $gempa)
                        <p>{{ $gempa }}</p>
                        <hr>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-info-circle tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Rekomendasi</h6>
                        <p>{!! nl2br($var->rekomendasi) !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mg-t-20 pd-20">
            <div class="loading d-flex bg-gray-200 ht-300 pos-relative align-items-center">
                <div class="sk-cube-grid">
                    <div class="sk-cube sk-cube1"></div>
                    <div class="sk-cube sk-cube2"></div>
                    <div class="sk-cube sk-cube3"></div>
                    <div class="sk-cube sk-cube4"></div>
                    <div class="sk-cube sk-cube5"></div>
                    <div class="sk-cube sk-cube6"></div>
                    <div class="sk-cube sk-cube7"></div>
                    <div class="sk-cube sk-cube8"></div>
                    <div class="sk-cube sk-cube9"></div>
                </div>
            </div>
            <div id="container"></div>
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
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '{{ URL::signedRoute('v1.json.highcharts') }}',
        type: 'POST',
        data: {id: '{{ $var->id }}'},
        beforeSend: function() {
            $('.loading').show();
        },
        success: function(data) {
            $('.loading').remove();
            $('#container').css('height','600px');

            Highcharts.chart('container', {
                chart: {
                    type: 'column',
                    renderTo: 'container',
                    events: {
                        load: function() {
                            this.renderer.image('https://magma.vsi.esdm.go.id/img/logo-esdm-magma.png', 80, 40, 106, 51)
                                .add();
                        }
                    }
                },
                credits: {
                    enabled: true,
                    text: 'Highcharts | MAGMA Indonesia - Kementerian ESDM'
                },
                title: {
                    text: 'Data Kegempaan 90 hari Terakhir'
                },
                xAxis: {
                    categories: data.categories,
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Gempa'
                    },
                    allowDecimals: false
                },
                legend: {
                    enabled: true,
                },
                tooltip: {
                    enabled: true,
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: false,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                        }
                    }
                },
                series: data.series,
                exporting: {
                    enabled: true,
                    scale: 1,
                    sourceHeight: 1080,
                    sourceWidth: 1920
                }
            });
        }
    });

});

</script>
@endsection

