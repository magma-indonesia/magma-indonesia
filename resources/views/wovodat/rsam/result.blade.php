@extends('layouts.default')

@section('title')
    WOVOdat | RSAM
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/stocktools/gui.css">
    <link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/annotations/popup.css">
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
                        <li>
                            <span>RSAM</span>
                        </li>
                        <li class="active">
                            <span>Result </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    RSAM Graphic of {{ $station_name }}
                </h2>
                <small>Range of period {{ $date }}.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content">
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

            <div class="col-lg-12">
                @if (count($rsam))
                <div class="hpanel">
                    <div class="panel-heading">
                        RSAM Graphic of {{ $station_name }}
                    </div>
                    <div class="panel-body">
                        <div class="row p-md">
                            <div id="rsam" style="min-width: 310px; min-height: 680px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/indicators/indicators-all.js"></script>
<script src="https://code.highcharts.com/stock/modules/drag-panes.js"></script>

<script src="https://code.highcharts.com/modules/annotations-advanced.js"></script>
<script src="https://code.highcharts.com/modules/price-indicator.js"></script>
<script src="https://code.highcharts.com/modules/full-screen.js"></script>

<script src="https://code.highcharts.com/modules/stock-tools.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($rsam), {collapsed: true});
        @endrole

        var data = @json($rsam);

        Highcharts.stockChart('rsam', {
            rangeSelector: {
                selected: 4,
            },
            chart: {
                zoomType: 'x',
            },
            title: {
                text: 'RSAM Graphic of {{ $station_name }} Range of period {{ $date }}',
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Count',
                }
            },
            legend: {
                enabled: false,
            },
            plotOptions: {
                marker: {
                    radius: 2,
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
            },
            scrollbar: {
                enabled: false,
            },
            series: [{
                type: 'area',
                name: 'Count',
                data: data,
            }],
            exporting: {
                enabled: true,
                scale: 1,
                sourceHeight: 800,
                sourceWidth: 1200,
            }
        });
    });
</script>   
@endsection