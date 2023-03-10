@extends('layouts.default')

@section('title')
Rekap Lembur {{ $user->name }} - {{ $date->formatLocalized('%B %Y') }}
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
                <strong>{{ $user->name }}</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.overtime.index') }}">Rekap Lembur</a></li>
                    <li class="active"><a href="{{ route('chambers.overtime.show-nip', ['date' => $date->format('Y-m'), 'nip' => $user->nip ]) }}">{{ $user->name }}</a></li>
                </ol>
            </div>

            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan performa bisa terjadi sewaktu-waktu
            </div>

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
                        <h1>{{ $date->formatLocalized('%B %Y') }}</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh pengamat gunung api. Pilih tahun laporan yang ingin dilihat.
                        </p>
                        <form id="form-date" method="GET" data-action="{{ route('chambers.overtime.show-nip', ['nip' => $user->nip]) }}">
                            <div class="input-group">
                                <input name="date" id="datepicker" class="form-control date" type="text" value="{{ empty(old('date')) ? $date : old('date') }}">
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
                <ul class="nav nav-tabs">
                @foreach ($reports as $key => $report)

                @if ($overtimes['group_by_type_and_dates'][$key]->isNotEmpty())
                    <li class="{{ $loop->first ? 'active' : '' }}">
                        <a data-toggle="tab" href="#{{ $report['slug'] }}" aria-expanded="true">{{ $report['name'] }}</a>
                    </li>
                @endif

                @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ($reports as $key => $report)

                    @if ($overtimes['group_by_type_and_dates'][$key]->isNotEmpty())
                    <div id="{{ $report['slug'] }}" class="tab-pane {{ $loop->first ? 'active' : '' }}">
                        <div class="panel-body">

                            @if ($key === 'vars')
                            <div class="table-responsive">
                                <h3 class="text-center">{{ $report['name'] }} - {{ $date->formatLocalized('%B %Y') }}</h3>
                                <table id="table-{{ $report['slug'] }}" class="table table- condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Gunung Api</th>
                                            <th>Tanggal Laporan</th>
                                            <th>Periode</th>
                                            <th>Waktu Pelaporan (lokal)</th>
                                            <th>Link Laporan</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($overtimes['reports']['vars'] as $var)
                                    <tr>
                                        <td>{{ $var['nip'] }}</td>
                                        <td class="border-right">{{ $var['nama'] }}</td>
                                        <td><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.show.gunung-api', ['year' => $selected_date->format('Y'), 'slug' => $var['slug']]) }}" style="color: #337ab7; text-decoration: none;">{{ $var['gunung_api'] }}</a></td>
                                        <td>{{ $var['tanggal_laporan']->formatLocalized('%A, %d %B %Y') }}</td>
                                        <td>{{ $var['jenis_periode_laporan'] }}, {{ $var['periode_laporan'] }}</td>
                                        <td>{{ $var['dibuat_pada']->formatLocalized('%A, %d %B %Y %H:%M:%S') }} {{ $var['time_zone'] }}</td>
                                        <td><a href="{{ $var['link'] }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif


                            @if ($key === 'vens')
                            <div class="table-responsive">
                                <h3 class="text-center">{{ $report['name'] }} - {{ $date->formatLocalized('%B %Y') }}</h3>
                                <table id="table-{{ $report['slug'] }}" class="table table- condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Gunung Api</th>
                                            <th>Waktu Letusan (lokal)</th>
                                            <th>Link Laporan</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($overtimes['reports']['vens'] as $ven)
                                    <tr>
                                        <td>{{ $ven['nip'] }}</td>
                                        <td class="border-right">{{ $ven['nama'] }}</td>
                                        <td>{{ $ven['gunung_api'] }}</td>
                                        <td>{{ $ven['waktu_letusan']->formatLocalized('%A, %d %B %Y %H:%I:%S') }} {{ $ven['time_zone'] }}</td>
                                        <td><a href="{{ $ven['link'] }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            @if ($key === 'vonas')
                            <div class="table-responsive">
                                <h3 class="text-center">{{ $report['name'] }} - {{ $date->formatLocalized('%B %Y') }}</h3>
                                <table id="table-{{ $report['slug'] }}" class="table table- condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Gunung Api</th>
                                            <th>Waktu VONA (UTC)</th>
                                            <th>Waktu Pelaporan (local)</th>
                                            <th>Link Laporan</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($overtimes['reports']['vonas'] as $vona)
                                    <tr>
                                        <td>{{ $vona['nip'] }}</td>
                                        <td class="border-right">{{ $vona['nama'] }}</td>
                                        <td>{{ $vona['gunung_api'] }}</td>
                                        <td>{{ $vona['issued'] }}</td>
                                        <td>{{ $vona['waktu_pelaporan']->formatLocalized('%A, %d %B %Y %H:%I:%S') }} {{ $ven['time_zone'] }}</td>
                                        <td><a href="{{ $ven['link'] }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                        </div>
                    </div>
                    @endif

                    @endforeach
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
    @foreach ($reports as $key => $report)
    @if ($overtimes['group_by_type_and_dates'][$key]->isNotEmpty())
    $('#table-{{ $report["slug"] }}').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center 'B><'col-sm-4'f>>tp",
        "lengthMenu": [[250, 100, 150, -1], [250, 100, 150, "All"]],
        buttons: [
            'copy', 'csv', 'excel', {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }, 'print'
        ]
    });
    @endif
    @endforeach
});

</script>
@endsection
