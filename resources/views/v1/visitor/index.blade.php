@extends('layouts.default')

@section('title')
    Data Pengunjung MAGMA
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>MAGMA v1</span>
                        </li>
                        <li class="active">
                            <span>Visitor </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Data Pengunjung MAGMA Indonesia v1
                </h2>
                <small>Pengunjung MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Filter Pengunjung
                    </div>
                    <div class="panel-body">
                        <div class="text-left">
                            <a href="{{ route('chambers.v1.visitor.index') }}" type="button" class="btn btn-magma btn-outline">Semua</a>
                            <a href="{{ route('chambers.v1.visitor.filter',['year' => '2016']) }}" type="button" class="btn btn-magma btn-outline">2016</a>
                            <a href="{{ route('chambers.v1.visitor.filter',['year' => '2017']) }}" type="button" class="btn btn-magma btn-outline">2017</a>
                            <a href="{{ route('chambers.v1.visitor.filter',['year' => '2018']) }}" type="button" class="btn btn-magma btn-outline">2019</a>
                        </div>
                        <hr>
                        <div class="row">
                            <div id="container" style="min-width: 310px; height: 600px; margin: 0 auto"></div>
                        </div>
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
        var data = @json($visitor);

        Highcharts.chart('container', {
            chart: {
                type: 'column',
            },
            credits: {
                enabled: true,
                text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
            },
            title: {
                text: 'Data Pengunjung MAGMA v1'
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
            }
        });
    });
</script>
@endsection