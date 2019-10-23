@extends('layouts.default')

@section('title')
    Statistik Kegiatan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('add-css')
<style>
table tr td[class*="1"] {
	border-left: 1px solid #333333;
}
</style>
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>Administratif</span>
                        </li>
                        <li>
                            <span>MGA</span>
                        </li>
                        <li class="active">
                            <span>Statistik Kegiatan </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Statistik kegiatan bidang MGA
                </h2>
                <small>Meliputi seluruh statistik kegiatan yang telah dilakukan </small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.administratif.mga.jenis-kegiatan.index') }}" class="btn btn-magma btn-outline btn-block" type="button">Kegiatan Utama MGA</a>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.administratif.mga.jenis-kegiatan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Kegiatan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1">Tabel Data</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-2">Grafik Harian</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3">Grafik Realisasi (Rp)</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="table-harian" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jumlah Lapangan</th>
                                            <th>Jumlah Hari</th>
                                            <th></th>
                                            <th>Jumlah Uang</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $user->nip }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->anggota_kegiatan_count }}</td>
                                            <td>{{ $user->jumlah_dinas }}</td>
                                            <td>Rp.</td>
                                            <td>{{ $user->jumlah_realisasi }}</td>
                                            <td>
                                                <a href="{{ route('chambers.administratif.mga.anggota-kegiatan.show', $user->nip) }}" class="btn btn-sm btn-info btn-outline" type="button">Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="row p-md">
                                <div id="grafik-harian" style="min-width: 310px; min-height: 2400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <div class="row p-md">
                                <div id="grafik-realisasi" style="min-width: 310px; min-height: 2400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<!-- DataTables buttons scripts -->
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<!-- DataTables -->
<script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- DataTables buttons scripts -->
<script src="{{ asset('vendor/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            var $harian = @json($grafik_harian);
            var $realisasi = @json($grafik_realisasi);

            var table = $('#table-harian').dataTable({
                language: {
                    thousands: '.'
                },
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                buttons: [
                    { 
                        extend: 'copy', 
                        className: 'btn-sm'
                    },
                    { 
                        extend: 'csv',
                        title: 'Daftar Users',
                        className: 'btn-sm',
                        exportOptions: { 
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    },
                    { 
                        extend: 'pdf', 
                        title: 'Daftar Users', 
                        className: 'btn-sm',
                        orientation: 'landscape',
                        exportOptions: { 
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    },
                    { 
                        extend: 'print', 
                        className: 'btn-sm',
                        orientation: 'landscape',
                        exportOptions: { 
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: [4,6],
                        orderable: false,
                        sortable: false,
                    },
                ],
            });

            Highcharts.setOptions({
                lang: {
                    thousandsSep: '.'
                }
            });

            Highcharts.chart('grafik-harian', {
                title: {
                    text: 'Rekap Kegiatan Tahun {{ now()->format('Y') }}',
                },
                xAxis: {
                    visible: true,
                    type: 'category',
                    labels: {
                        step: 1,
                    },
                    title: {
                        text: 'Nama',
                    },
                    tickInterval: 1,
                    tickWidth: 1,
                    tickLength: 15
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Hari',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                plotOptions: {
                    series: {
                        pointWidth: 5,
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none',
                            allowOverlap: true,
                            formatter: function (e) {
                                return this.y+' hari'
                            }
                        }
                    }
                },
                tooltip: {
                    valueSuffix: ' Hari'
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                series: [{
                    type: 'bar',
                    name: 'Jumlah Hari',
                    data: $harian,
                    color: '#007fff'
                }],
                exporting: {
                    enabled: true,
                    scale: 1,
                    sourceHeight: 2400,
                    sourceWidth: 800,
                }
            });

            Highcharts.chart('grafik-realisasi', {
                title: {
                    text: 'Rekap Kegiatan Tahun {{ now()->format('Y') }}',
                },
                xAxis: {
                    visible: true,
                    type: 'category',
                    labels: {
                        step: 1,
                    },
                    title: {
                        text: 'Nama',
                    },
                    tickInterval: 1,
                    tickWidth: 1,
                    tickLength: 15
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Uang',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                plotOptions: {
                    series: {
                        pointWidth: 5,
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none',
                            allowOverlap: true,
                            formatter: function (e) {
                                return 'Rp. '+Highcharts.numberFormat(this.y,0)
                            }
                        }
                    }
                },
                tooltip: {
                    valuePrefix: 'Rp. ',
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                series: [{
                    type: 'bar',
                    name: 'Jumlah Realisasi',
                    data: $realisasi,
                    color: '#007fff',
                }],
                exporting: {
                    enabled: true,
                    scale: 1,
                    sourceHeight: 2400,
                    sourceWidth: 800,
                }
            });

        });
    </script>
@endsection