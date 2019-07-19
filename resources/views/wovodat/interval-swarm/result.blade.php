@extends('layouts.default') 

@section('title') 
    WOVOdat | Interval Swarm
@endsection

@section('add-vendor-css')
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
                        <span>WOVOdat</span>
                    </li>
                    <li class="active">
                        <span>Interval Swarm </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Giving information about Interval Swarm from specific seismic station
            </h2>
            <small>This table contains data about earthquakes that occur in specified time intervals, e.g., as seismic swarms.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            @role('Super Admin')
            <div class="col-lg-12">
            @component('components.json-var')
                @slot('title')
                    For Developer
                @endslot
            @endcomponent
            </div>
            @endrole

            <div class="col-lg-10">
                <div class="row p-md">
                    <div id="swarm" style="min-width: 310px; min-height: 480px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($swarm), {collapsed: true});
        @endrole

        var data = @json($swarm);

        Highcharts.chart('swarm', {
            chart: {
                zoomType: 'x',
                type: 'column',
                animation: false,
            },
            credits: {
                enabled: true,
                text: 'Highcharts | WOVOdat - EOS | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM',
            },
            title: {
                text: 'Grafik Kegempaan Gunung {{ $volcano_name }}',
            },
            subtitle: {
                text: 'Station: {{ $station_name }}',
            },
            xAxis: {
                type: 'datetime',
                minTickInterval: 86400000,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Gempa'
                },
                allowDecimals: false
            },
            tooltip: {
                enabled: true,
                xDateFormat: '%Y-%m-%d',
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                }
            },
            series: data,
            exporting: {
                enabled: true,
                scale: 1,
            }
        });
    });
</script>
@endsection