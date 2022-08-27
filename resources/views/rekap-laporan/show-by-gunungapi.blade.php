@extends('layouts.default')

@section('title')
Rekap Laporan {{ $gadd->ga_nama_gapi }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/dist/fullcalendar.print.css') }}" media='print'/>
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/dist/fullcalendar.min.css') }}" />
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
                <strong>{{ $gadd->ga_nama_gapi }}</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index') }}">Rekap Laporan</a></li>
                    <li><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index.gunung-api', ['year' => $selected_year]) }}">Gunung Api ({{ $selected_year }})</a></li>
                    <li class="active"><a href="#">{{ $gadd->ga_nama_gapi }}</a></li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Menu ini digunakan untuk memberikan gambaran rekapitulasi laporan yang dibuat oleh pengamat gunung api
                maupun staff gunung api setiap bulannya yang dikelompokkan berdasarkan gunung apinya. Distribusi sebaran pembuatan laporan dapat dilihat dalam bentuk tabel di bawah ini.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>

        </div>
    </div>
</div>
@endsection

@section('content-body')
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
<script src="{{ asset('vendor/fullcalendar/dist/fullcalendar.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endrole

@endsection

@section('add-script')
<script>
$(document).ready(function () {
    var calendars = @json($vars['calendar']);
});
</script>
@endsection
