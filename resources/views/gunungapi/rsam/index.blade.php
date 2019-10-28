@extends('layouts.default')

@section('title')
    Gunung Api | RSAM
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
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>RSAM </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Grafik RSAM Gunung Api
                </h2>
                <small>Menggunakan data Winston Server.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">

    <div class="row">

        @if ($health != 200)
        <div class="col-lg-12">
            <div class="alert alert-danger text-center">
                <h4><i class="fa fa-gears"></i> Mohon maaf Winston Wave Server sedang mengalami gangguan. Silahkan coba beberapa saat lagi.</h4>
            </div>
        </div>
        @else

        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Pilih Paramater
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ URL::signedRoute('chambers.json.rsam') }}">
                        @csrf
                        <div class="tab-content">
                            <div id="step1" class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk membuat grafik data RSAM.
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
                                                <label class="control-label">Channel</label>
                                                <select id="channel" class="form-control m-b" name="channel">
                                                @foreach ($gadds as $gadd)
                                                    @foreach ($gadd->seismometers as $seismomter)
                                                    <option value="{{ $seismomter->scnl }}">{{ $gadd->name }} - {{ $seismomter->scnl }}</option>
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

                                            <div class="form-group">
                                                <label class="control-label">Periode RSAM</label>
                                                <select id="periode" class="form-control m-b" name="periode">
                                                    <option value="60">1 Menit</option>
                                                    <option value="600">10 Menit</option>
                                                    <option value="3600">1 Jam</option>
                                                    <option value="21600">6 Jam</option>
                                                </select>
                                            </div>

                                            <hr>
                                            <button id="submit" class="btn btn-magma" type="submit">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12 rsam" style="display: none;">
            <div class="hpanel">
                <div class="panel-heading rsam-heading">
                    Grafik RSAM
                </div>
                <div class="panel-body">
                    <div class="row p-md">
                        <div class="progress m-t-xs full progress-striped active">
                            <div style="width: 90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class=" progress-bar progress-bar-success">Loading....
                            </div>
                        </div>
                        <div id="rsam" style="min-width: 310px; min-height: 680px; margin: 0 auto; display: none;"></div>
                    </div>
                </div>
            </div>
        </div>

        @endif
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
        startDate: '2015-05-01',
        endDate: '{{ now()->format('Y-m-d') }}',
        language: 'id',
        todayHighlight: true,
        todayBtn: 'linked',
        enableOnReadonly: false
    });

    $('#form').submit(function(e) {
        e.preventDefault();

        $('.rsam').show();
        $('#rsam').hide();
        $('.progress').show();

        var channel = $('#channel').val();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{ URL::signedRoute('chambers.json.rsam') }}',
            data: $(this).serialize(),
            type: 'POST',
            success: function(data) {
                console.log(data);
                plotRSAM(data,channel);
            },
            complete: function() {
                $('.progress').hide();
                $('#rsam').show();
            },
        });
    });

    function plotRSAM(data,channel)
    {
        Highcharts.stockChart('rsam', {
            rangeSelector: {
                selected: 4,
            },
            chart: {
                zoomType: 'x',
            },
            title: {
                text: 'Grafik RSAM - '+channel,
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'RSAM Count',
                }
            },
            legend: {
                enabled: false,
            },
            plotOptions: {
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
            rangeSelector: {
                buttons: [{
                    type: 'hour',
                    count: 1,
                    text: '1H'
                }, {
                    type: 'hour',
                    count: 12,
                    text: '12H'
                }, {
                    type: 'day',
                    count: 1,
                    text: '1D'
                }, {
                    type: 'all',
                    count: 1,
                    text: 'All'
                }],
                selected: 3,
                inputEnabled: false
            },
            series: [{
                marker: {
                    enabled: true,
                    radius: 3
                },
                type: 'area',
                name: 'RSAM',
                data: data,
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, '#1E88E5'],
                        [1, '#ffffff']
                    ]
                },
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
