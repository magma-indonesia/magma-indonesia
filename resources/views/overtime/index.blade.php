@extends('layouts.default')

@section('title')
Rekap Lembur {{ $selected_date }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap.min.css" />
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
                <strong>Rekap Lembur</strong>
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

        @role('Super Admin')
        <div class="col-lg-4 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Jadwal Libur Nasional</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $selected_date }}</h1>
                        <p>
                            Gunakan menu ini untuk melihat atau menambahkan hari libur nasional. Adapun libur nasional pada bulan {{ $selected_date }} adalah sebagai berikut:
                        </p>
                        <a href="#" class="btn btn-outline btn-danger m-t-xs">Lihat Libur</a>
                    </div>
                </div>
            </div>
        </div>
        @endrole
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
                        <h3 class="text-center">{{ $selected_date }}</h3>
                        <table id="table-rekap" class="table table- condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    @foreach ($dates_period as $date_period)

                                        @if ($loop->first)
                                        <th class="border-left border-right {{ $holiday_dates->contains($date_period->format('Y-m-d')) ? 'text-danger' : '' }}">{{ $date_period->format('d') }}</th>
                                        @elseif ($loop->last)
                                        <th class="border-right {{ $holiday_dates->contains($date_period->format('Y-m-d')) ? 'text-danger' : '' }}">{{ $date_period->format('d') }}</th>
                                        @else
                                        <th class="border-right {{ $holiday_dates->contains($date_period->format('Y-m-d')) ? 'text-danger' : '' }}">{{ $date_period->format('d') }}</th>
                                        @endif

                                    @endforeach
                                    <th>Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($overtimes as $overtime)
                            <tr>
                                <td class="border-right">
                                    <a href="{{ route('chambers.overtime.show-nip', ['nip' => $overtime['nip'], 'date' => $date->format('Y-m') ]) }}" style="color: #337ab7; text-decoration: none;">{{ $overtime['nama'] }}</a>
                                </td>
                                <td class="border-right">{{ $overtime['nip'] }}</td>

                                @foreach ($dates_period as $date_period)

                                    @if ($overtime['unique_dates']->contains($date_period->format('Y-m-d')))
                                    <td title="{{ $overtime['titles'][$date_period->format('Y-m-d')] }}" class="bg-success border-right text-center" style="color: transparent;">1</td>
                                    @else
                                    <td class="border-right"></td>
                                    @endif

                                @endforeach

                                <td>{{ $overtime['unique_dates_count'] }}</td>
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
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- DataTables buttons scripts -->
<script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap.min.js"></script>
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
        columnDefs: @json($disable_order),
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center 'B><'col-sm-4'f>>tp",
        "lengthMenu": [[250, 100, 150, -1], [250, 100, 150, "All"]],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});

</script>
@endsection