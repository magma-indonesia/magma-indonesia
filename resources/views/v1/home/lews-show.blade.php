@extends('layouts.slim')

@section('title')
Landslide Early Warning System (LEWS)
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/SpinKit/css/spinkit.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gerakan Tanah</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $station_name }}</li>
@endsection

@section('page-title')
{{ $station_name }}
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        @foreach ($station->channels_used as $channelName)
        <div class="card mg-t-20 pd-20">
            <div id="{{ $channelName }}"></div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/highcharts.js"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    var $size = screen.width <= 767 ? '400px' : '600px';
    var $size_w = screen.width <= 767 ? 80 : 106;
    var $size_h = screen.width <= 767 ? 38 : 51;

    @foreach ($data['series'] as $serie)
    Highcharts.chart('{{ $serie["alias"] }}', {
        chart: {
            type: 'line',
            animation: false,
            renderTo: '{{ $serie["alias"] }}',
            events: {
                load: function() {
                    this.renderer.image('https://magma.vsi.esdm.go.id/img/logo-esdm-magma.png', 900, 0, $size_w, $size_h)
                        .add();
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: true,
            text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
        },
        title: {
            text: '{{ $serie["name"] }}'
        },
        xAxis: {
            categories: @json($data['categories']),
            labels: {
                style: {
                    fontSize: "12px",
                    color: "#333333",
                }
            }
        },
        yAxis: {
            min: 0,
            labels: {
                style: {
                    color: "#333333",
                    fontSize: "12px",
                }
            },
        },
        series: [@json($serie)],
    });
    @endforeach
});
</script>
@endsection