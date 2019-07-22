@extends('layouts.default')

@section('title')
    WOVOdat | Deformation
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
                            <span>Deformation</span>
                        </li>
                        <li class="active">
                            <span>Result </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Tiltmeter {{ $volcano }} - {{ $station_name }}
                </h2>
                <small>Range of period {{ $date }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content content-boxed">
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
                @if (count($station))
                <div class="hpanel">
                    <div class="panel-heading">
                        Tiltmeter {{ $volcano }} - {{ $station_name.' ('.$date.')' }} 
                    </div>
                    <div class="panel-body">
                        <div id="tilt" class="row p-md">
                            <div id="tilt-0" style="min-width: 310px; min-height: 320px; margin: 0 auto"></div>
                            <div id="tilt-1" style="min-width: 310px; min-height: 320px; margin: 0 auto"></div>
                            <div id="tilt-2" style="min-width: 310px; min-height: 320px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($station), {collapsed: true});
        @endrole

        var data = @json($station);

        ['mousemove', 'touchmove', 'touchstart'].forEach(function (eventType) {
            document.getElementById('tilt').addEventListener(
                eventType,
                function (e) {
                    var chart,
                        point,
                        i,
                        event;

                    for (i = 0; i < Highcharts.charts.length; i = i + 1) {
                        chart = Highcharts.charts[i];
                        // Find coordinates within the chart
                        event = chart.pointer.normalize(e);
                        // Get the hovered point
                        point = chart.series[0].searchPoint(event, true);

                        if (point) {
                            point.highlight(e);
                        }
                    }
                }
            );
        });

        Highcharts.Pointer.prototype.reset = function () {
            return undefined;
        };

        Highcharts.Point.prototype.highlight = function (event) {
            event = this.series.chart.pointer.normalize(event);
            this.onMouseOver(); // Show the hover marker
            this.series.chart.tooltip.refresh(this); // Show the tooltip
            this.series.chart.xAxis[0].drawCrosshair(event, this); // Show the crosshair
        };

        function syncExtremes(e) {
            var thisChart = this.chart;

            if (e.trigger !== 'syncExtremes') { // Prevent feedback loop
                Highcharts.each(Highcharts.charts, function (chart) {
                    if (chart !== thisChart) {
                        if (chart.xAxis[0].setExtremes) { // It is null while updating
                            chart.xAxis[0].setExtremes(
                                e.min,
                                e.max,
                                undefined,
                                false,
                                { trigger: 'syncExtremes' }
                            );
                        }
                    }
                });
            }
        }

        $.each(data, function(i, dataset) {
            Highcharts.chart('tilt-'+i, {
                chart: {
                    marginLeft: 40,
                    spacingTop: 20,
                    spacingBottom: 20
                },
                title: {
                    text: dataset.name,
                    align: 'left',
                    margin: 0,
                    x: 30
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                xAxis: {
                    type: 'datetime',
                    crosshair: true,
                    events: {
                        setExtremes: syncExtremes
                    },
                },
                yAxis: {
                    title: {
                        text: null
                    }
                },
                tooltip: {
                    positioner: function () {
                        return {
                            // right aligned
                            x: this.chart.chartWidth - 240,
                            y: 10 // align to title
                        };
                    },
                    borderWidth: 0,
                    backgroundColor: 'none',
                    headerFormat: '',
                    shadow: false,
                    style: {
                        fontSize: '14px'
                    },
                    valueDecimals: dataset.decimal,
                    formatter: function () {
                        return Highcharts.dateFormat('%e %b %Y', new Date(this.x))+', '+this.y+' '+dataset.unit;
                    },
                },
                series: [{
                    data: dataset.data,
                    name: dataset.name,
                    type: dataset.type,
                    color: Highcharts.getOptions().colors[i],
                    fillOpacity: 0.3,
                }]
            });
        });

    });
</script>   
@endsection