@extends('layouts.default')

@section('title')
Rekap Lembur
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
<div class="normalheader">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-ribbon fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Rekap Laporan</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li class="active"><a href="{{ route('chambers.overtime.index') }}">Rekap Lembur</a></li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Menu ini digunakan untuk memberikan gambaran rekapitulasi lembur yang dibuat oleh pengamat gunung api setiap bulannya. Waktu lembur diambil berdasarkan waktu laporab VAR, VEN, VONA, dan hari libur.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>
            @if (session('message'))
            <div class="alert alert-success">
                <i class="fa fa-check"></i> {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content no-top-padding">
    <div class="row">
        <div class="col-lg-4 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Rekap Lembur</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $selected_date }}</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh pengamat gunung api. Pilih tahun laporan yang ingin dilihat.
                        </p>
                        <form id="form-date" method="GET" data-action="{{ route('chambers.overtime.index') }}">
                            <div class="input-group">
                                <input name="date" id="datepicker" class="form-control date" type="text" value="{{ empty(old('date')) ? $date    : old('date') }}">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary submit-date">Lihat</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Rekap Lembur {{ $selected_date }}
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">

                            @if ($pengamat_only)
                                <a href="{{ route('chambers.overtime.index', ['year' => $date->format('Y-m')]) }}?pengamatOnly=false"
                                    class="btn btn-outline btn-block btn-magma" type="button">Tampilkan Semua</a>
                            @else
                                <a href="{{ route('chambers.overtime.index', ['year' => $date->format('Y-m')]) }}?pengamatOnly=true"
                                    class="btn btn-outline btn-block btn-magma" type="button">Tampilkan Hanya Pengamat</a>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-rekap" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center border-left">Pegawai</th>
                                    <th colspan="{{ $colspan }}" class="text-center border-right border-left">Tanggal</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    @foreach ($dates_period as $date_period)

                                        @if ($loop->first)
                                        <th class="border-left">{{ $date_period->format('d') }}</th>
                                        @elseif ($loop->last)
                                        <th class="border-right">{{ $date_period->format('d') }}</th>
                                        @else
                                        <th>{{ $date_period->format('d') }}</th>
                                        @endif

                                    @endforeach
                                    <th>Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($overtimes as $overtime)
                            <tr>
                                <td class="border-right">
                                    <a href="{{ route('chambers.overtime.show', ['nip' => $overtime['nip'], 'date' => $date->format('Y-m') ]) }}" style="color: #337ab7; text-decoration: none;">{{ $overtime['name'] }}</a>
                                </td>
                                <td class="border-right">{{ $overtime['nip'] }}</td>

                                @foreach ($dates_period as $date_period)

                                    @if ($overtime['overtime']->contains($date_period->format('Y-m-d')))
                                    <td class="bg-success border-right border-left"></td>
                                    @else
                                    <td class="border-right border-left"></td>
                                    @endif

                                @endforeach

                                <td>{{ $overtime['overtime_count'] }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
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
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/moment/locale/id.js') }}"></script>
<script src="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>

$(document).ready(function () {

    $('#datepicker').datetimepicker({
        minDate: '2023-01-01',
        maxDate: '{{ now()->format("Y-m-d")}}',
        locale: 'id',
        format: 'YYYY-MM',
    });

    $('body').on('click','.submit-date',function (e) {
        e.preventDefault();

        var $value = $('.date').val(),
            $url = $('#form-date').data('action')+'/'+$value;

        window.open($url, '_self');
    });

    // Initialize table
    $('#table-rekap').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
        buttons: [
            { extend: 'csv', title: 'Rekap Laporan', className: 'btn-sm'},
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL', title: 'Rekap Lembur', className: 'btn-sm'},
        ]
    });
});

</script>
@endsection