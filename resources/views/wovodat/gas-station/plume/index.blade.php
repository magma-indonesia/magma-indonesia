@extends('layouts.default')

@section('title')
    WOVOdat | Gas Plume
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
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
                            <span>Gas</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    This table stores gas data collected from a plume including the location of the vent, the height of the plume, and the gas emission rates.
                </h2>
                <small>Base on WOVOdat database</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Choose Paramaters
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.wovodat.common-network.gas-station.plume.store') }}">
                            @csrf
                            <div class="tab-content">
                                <div id="step1" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-4 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk membuat grafik data plume.
                                            </p>
                                        </div>

                                        <div class="col-lg-8">
                                            @if ($errors->any())
                                            <div class="row m-b-md">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <p>{{ $error }}</p>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="row p-md">
                                                <div class="form-group">
                                                    <label class="control-label">Gas Station</label>
                                                    <select id="station" class="form-control m-b" name="station">
                                                        @foreach ($volcanoes as $volcano)
                                                            @foreach ($volcano->gas_stations as $station)
                                                            <option value="{{ $station->gs_id }}" {{ old('station') == $station->gs_id ? 'selected' : '' }}>{{ $volcano->vd_name.' - '.$station->gs_name.' ('.$station->gs_code.')' }}</option> 
                                                            @endforeach 
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Range Tanggal</label>
                                                    <div class="input-group input-daterange">
                                                        <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(30)->format('Y-m-d') : old('start')}}" name="start">
                                                        <div class="input-group-addon"> - </div>
                                                        <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                                    </div>
                                                </div>

                                                <hr>
                                                <button class="btn btn-magma" type="submit">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 plume" style="display: none;">
                <div class="hpanel">
                    <div class="panel-heading plume-heading">
                        Gas Plume
                    </div>
                    <div class="panel-body">
                        <div class="row p-md">
                            <div class="progress m-t-xs full progress-striped active">
                                <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class=" progress-bar progress-bar-success">Loading....
                                </div>
                            </div>
                            <div id="plume" style="min-width: 310px; min-height: 680px; margin: 0 auto; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        $.fn.datepicker.dates['id'] = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mi', 'Se', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            today: 'Hari ini',
            clear: 'Bersihkan',
            format: 'yyyy-mm-dd',
            titleFormat: 'MM yyyy',
            weekStart: 1
        };

        $('.input-daterange').datepicker({
            startDate: '2013-01-01',
            endDate: '{{ now()->format('Y-m-d') }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false
        });

        $('#form').submit(function(e) {
            e.preventDefault();

            $('.plume').show();
            $('#plume').hide();
            $('.progress').show();

            var station = $('#station').html();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '{{ URL::signedRoute('chambers.json.wovodat.plume') }}',
                data: $(this).serialize(),
                type: 'POST',
                success: function(data) {
                    console.log(data);
                    plotPlume(data,station);
                    $('.progress').hide();
                    $('#plume').show();
                },
            });
        });

        function plotPlume(data,station)
        {
            Highcharts.stockChart('plume', {
                rangeSelector: {
                    selected: 4,
                },
                chart: {
                    zoomType: 'x',
                },
                title: {
                    text: 'Gas Plume of '+station,
                },
                xAxis: {
                    type: 'datetime',
                },
                yAxis: {
                    title: {
                        text: 'ton/day',
                    }
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    marker: {
                        radius: 2,
                    },
                    scatter: {
                        turboThreshold: 0,
                    }
                },
                scrollbar: {
                    enabled: false,
                },
                tooltip: {
                    formatter: function () {
                        return Highcharts.dateFormat('%e %b %Y %H:%M:%S', new Date(this.x))+', '+this.y+' ton/day';
                    },
                },
                series: [{
                    type: 'scatter',
                    name: 'Gas Plume',
                    data: data,
                }],
                exporting: {
                    enabled: true,
                    scale: 1,
                    sourceHeight: 800,
                    sourceWidth: 1200,
                }
            });
        }
    });
</script>
@endsection