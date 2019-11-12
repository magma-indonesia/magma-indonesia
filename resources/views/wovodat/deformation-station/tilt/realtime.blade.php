@extends('layouts.default')

@section('title')
    WOVOdat | Realtime Tiltmeter
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
                            <span>Realtime </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Tiltmeter {{ $volcano }} - {{ $station_name }}
                </h2>
                <small>Realtime </small>
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
                        Tiltmeter {{ $volcano }} - {{ $station_name }} 
                    </div>

                    @if (count($station[0]['data']))
                    <div class="panel-body">
                        <div id="tilt" class="row p-md">
                            <div id="tilt-0" style="min-width: 310px; height: 200px; margin: 0 auto"></div>
                            <div id="tilt-1" style="min-width: 310px; height: 200px; margin: 0 auto"></div>
                            <div id="tilt-2" style="min-width: 310px; height: 200px; margin: 0 auto"></div>
                        </div>
                    </div>
                    @else
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <i class="fa fa-gears"></i> Tidak ada data dalam periode tersebut
                        </div>
                    </div>
                    @endif
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
    class StartDate {
        constructor() {
            this.current_datetime = new Date();
        }

        addZero(value) {
            return value<10 ? '0'+value : value;
        }

        setCurrentDateTime(datetime) {
            this.current_datetime = new Date(datetime);
            return this;
        }

        addSecondToCurrentDateTime(seconds = 1)
        {
            this.current_datetime = this.current_datetime.setSeconds(this.current_datetime.getSeconds()+seconds);
            return this;
        }

        setNewDateTime() {
            this.current_datetime = new Date(this.current_datetime);
            return this;
        }

        get val() {
            let current_datetime = new Date(this.current_datetime);
            let year = current_datetime.getFullYear();
            let month = this.addZero(current_datetime.getMonth()+1);
            let date = this.addZero(current_datetime.getDate());
            let hours = this.addZero(current_datetime.getHours());
            let minutes = this.addZero(current_datetime.getMinutes());
            let second = this.addZero(current_datetime.getSeconds());

            return year+'-'+month+'-'+date+' '+hours+':'+minutes+':'+second;
        }
    }

    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($station), {collapsed: true});
        @endrole

        var data = @json($station),
            tiltmeter = [];

        start_date = new StartDate();

        function requestData(seconds = 1)
        {
            console.log(start_date.val);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '{{ URL::signedRoute('chambers.json.wovodat.tilt.realtime', ['deformation_station' => '21']) }}',
                data: {start: start_date.val},
                type: 'POST',
                success: function(dataset) {
                    console.log(dataset);
                    $.each(tiltmeter, function(index, value) {
                        tiltmeter[index].series[0].addPoint(dataset[index].data[0], true, true);
                    });
                    start_date.addSecondToCurrentDateTime()
                            .setNewDateTime();
                    setTimeout(function() {
                        requestData(seconds)
                    }, seconds*1000);
                },
                cache: false
            });
        }

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

        Highcharts.setOptions({
            chart: {
                marginLeft: 80,
                spacingTop: 20,
                spacingBottom: 20,
                events: {
                    load: requestData(2)
                }
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
                        x: this.chart.chartWidth - 320,
                        y: 10
                    };
                },
                borderWidth: 0,
                backgroundColor: 'none',
                headerFormat: '',
                shadow: false,
                style: {
                    fontSize: '14px'
                },
                valueDecimals: 1,
                formatter: function () {
                    return Highcharts.dateFormat('%e %b %Y %H:%M:%S', new Date(this.x))+', '+this.y+' mm';
                },
            },
        });

        $.each(data, function(i, dataset) {
            tiltmeter[i] = Highcharts.chart('tilt-'+i, {
                title: {
                    text: dataset.name,
                    align: 'left',
                    margin: 0,
                    x: 30
                },
                tooltip: {
                    formatter: function () {
                        return Highcharts.dateFormat('%e %b %Y %H:%M:%S', new Date(this.x))+', '+this.y+' '+dataset.unit;
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