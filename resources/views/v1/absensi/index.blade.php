@extends('layouts.default')

@section('title')
    v1 | Absensi Pegawai 
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/fooTable/css/footable.bootstrap.min.css') }}" />
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
                            <span>Absensi </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Absensi Pegawai PVMBG
                </h2>
                <small>Data absensi Pegawai PVMBG, Badan Geologi, ESDM</small>
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
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <div id="container-pemakai"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <div id="container-sebulan"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar Absensi Hari Ini
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            {{ $absensis->links() }}
                            <table id="absensi" class="footable table table-stripped toggle-arrow-tiny" data-sorting="true" data-show-toggle="true" data-paging="false" data-filtering="false" data-paging-size="31">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nip</th>
                                        <th>Nama</th>
                                        <th>Pos PGA</th>
                                        <th>Tanggal</th>
                                        <th>Checkin</th>
                                        <th>Checkout</th>
                                        <th>Durasi Kerja</th>
                                        <th>Radius Check In</th>
                                        <th>Radius Check Out</th>
                                        <th>Kehadiran</th>
                                        <th>Action</th>
                                        <th data-breakpoints="all" data-title="Foto Checkin">Foto Checkin</th>
                                        <th data-breakpoints="all" data-title="Foto Checkout">Foto Checkout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($absensis as $key => $absensi)
                                    <tr>
                                        <td></td>
                                        <td>{{ $absensi->vg_nip }}</td>
                                        <td>{{ $absensi->user->vg_nama }}</td>
                                        <td>{{ $absensi->pos->observatory }}</td>
                                        <td>{{ $absensi->date_abs }}</td>
                                        <td>{{ $absensi->checkin_time }}</td>
                                        <td>{{ $absensi->checkout_time == '00:00:00' ? '-' : $absensi->checkout_time}}</td>
                                        <td>{{ $absensi->length_work ? $absensi->length_work.' menit' : '-'}}</td>
                                        <td>{{ $absensi->checkin_dist ? number_format($absensi->checkin_dist,0,',','.').' m' : '-'}}</td>
                                        <td>{{ $absensi->checkout_dist ? number_format($absensi->checkout_dist,0,',','.').' m' : '-' }}</td>
                                        <td>
                                            @switch($absensi->ket_abs)
                                                @case(0)
                                                    <span class="label label-danger">Alpha</span>
                                                    @break
                                                @case(1)
                                                    <span class="label label-success">Hadir</span>
                                                    @break
                                                @default
                                                    <span class="label label-danger">Tidak ada dalam daftar</span>
                                            @endswitch
                                        </td>
                                        <td><a class="btn btn-sm btn-outline btn-magma" href="{{ route('chambers.v1.absensi.show',$absensi) }}" target="_blank">View</a></td>
                                        <td><img class="img-responsive m-t-md" src="{{ $absensi->checkin_image }}"></td>
                                        <td>
                                        @if($absensi->checkout_image)
                                        <img class="img-responsive m-t-md" src="{{ $absensi->checkout_image }}">
                                        @else
                                        -
                                        @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $absensis->links() }}
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
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="{{ asset('vendor/fooTable/js/footable.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>

    $(document).ready(function() {

        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($statistic), {collapsed: true});
        @endrole

        // var $size = screen.width <= 767 ? '400px' : '400px';
        // $('#container-pemakai').css('min-height',$size);
        // $('#container-sebulan').css('min-height',$size);

        var pemakai = {{ $pemakai_count }};
        var sebulan = {{ $sebulan_count }};

        Highcharts.chart('container-pemakai', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            }, 
            title: {
                text: 'Persentase Pemakai Fitur Absensi'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Absensi',
                colorByPoint: true,
                data: [{
                    name: 'Yang telah menggunakan',
                    y: pemakai/215*100,
                    color: '#1E88E5',
                    sliced: true,
                    selected: true,
                }, {
                    name: 'Yang belum menggunakan',
                    y: (215-pemakai)/215*100,
                    color: '#FFB74D'
                }]
            }],
        });

        Highcharts.chart('container-sebulan', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            }, 
            title: {
                text: 'Persentase Pemakai Fitur Absensi 30 hari terakhir'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Pemakaian Fitur Absensi 30 hari terakhir',
                colorByPoint: true,
                data: [{
                    name: 'Yang telah menggunakan',
                    y: sebulan/215*100,
                    color: '#1E88E5',
                    sliced: true,
                    selected: true
                }, {
                    name: 'Yang belum menggunakan',
                    y: (215-sebulan)/215*100,
                    color: '#FFB74D'
                }]
            }],
        });

        $('#absensi').footable();

    });

</script>
@endsection