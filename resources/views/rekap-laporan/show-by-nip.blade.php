@extends('layouts.default')

@section('title')
Rekap Laporan {{ $user->vg_nama }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
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
                <strong>{{ $user->vg_nama }}</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index') }}">Rekap Laporan</a></li>
                    <li class="active"><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.show.nip', ['year' => $selected_year, 'nip' => $user->vg_nip]) }}">{{ $user->vg_nama }}</a></li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Menu ini digunakan untuk memberikan gambaran rekapitulasi laporan yang dibuat oleh pengamat gunung api maupun staff gunung api setiap bulannya. Distribusi sebaran pembuatan laporan dapat dilihat dalam bentuk tabel di bawah ini.
            </p>
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
                        <h4>Rekap Laporan</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $user->vg_nama }}</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh <b>{{ $user->vg_nama }}</b>. Pilih tahun laporan yang ingin dilihat.
                        </p>
                        @foreach ($years as $year)
                        <a href="{{ route('chambers.v1.gunungapi.rekap-laporan.show.nip', ['year' => $year->format('Y'), 'nip' => $user->vg_nip]) }}" class="btn btn-outline btn-danger m-t-xs {{ $selected_year == $year->format('Y') ? 'active disabled' : ''}}">{{ $year->format('Y') }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Rekap Laporan</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>Gunung Api</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh pengamat gunung api yang telah <b>dikelompokkan berdasarkan gunung api</b>.
                        </p>

                        @foreach ($years as $year)
                        <a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index.gunung-api', ['year' => $year->format('Y')]) }}" class="btn btn-outline btn-danger m-t-xs">{{ $year->format('Y') }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Rekap Laporan tahun {{ $selected_year }} - {{ $user->vg_nama }}
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-rekap-user" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Gunung Api</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Periode</th>
                                    <th>Dibuat pada</th>
                                    <th>Link Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vars as $var)
                                <tr>
                                    <td>{{ $var['nip'] }}</td>
                                    <td class="border-right">{{ $var['nama'] }}</td>
                                    <td><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.show.gunung-api', ['year' => $selected_year, 'slug' => $var['slug']]) }}" style="color: #337ab7; text-decoration: none;">{{ $var['gunung_api'] }}</a></td>
                                    <td>{{ $var['tanggal_laporan']->formatLocalized('%A, %d %B %Y') }}</td>
                                    <td>{{ $var['jenis_periode_laporan'] }}, {{ $var['periode_laporan'] }}</td>
                                    <td>{{ $var['dibuat_pada']->formatLocalized('%A, %d %B %Y %H:%M:%S') }} {{ $var['time_zone'] }}</td>
                                    <td><a href="{{ $var['link'] }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a></td>
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
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endrole

@endsection

@section('add-script')
<script>
    $(document).ready(function () {
    // Initialize table
    $('#table-rekap-user').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
        buttons: [
            { extend: 'csv', title: 'Rekap Laporan', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL', title: 'Rekap Laporan', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13 ]} },
        ]
    });
});

</script>
@endsection