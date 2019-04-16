@extends('layouts.default')

@section('title')
    v1 - Data Dasar Gunung Api
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
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
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Data Dasar </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Data Dasar Gunung Api
                </h2>
                <small>Meliputi data administratif dan data dasar</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Data Dasar Gunung Api
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-gadd" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Alias</th>
                                    <th>Lokasi</th>
                                    <th>Kota Terdekat</th>
                                    <th>Tipe</th>
                                    <th>Ketinggian (mdpl)</th>
                                    <th>Koordinat (Lat,Lon)</th>
                                    <th>Morfologi</th>
                                    <th>Batuan Dominan</th>
                                    <th style="min-width: 180px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gadds as $key => $gadd)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $gadd->ga_nama_gapi }}</td>
                                    <td>{{ $gadd->ga_alias_gapi ? $gadd->ga_alias_gapi : '-' }}</td>
                                    <td>{{ $gadd->ga_kab_gapi.', '.$gadd->ga_prov_gapi }}</td>
                                    <td>{{ $gadd->ga_koter_gapi ? $gadd->ga_koter_gapi : '-' }}</td>
                                    <td>{{ $gadd->ga_tipe_gapi }}</td>
                                    <td>{{ $gadd->ga_elev_gapi }}</td>
                                    <td>{{ $gadd->ga_lat_gapi.', '.$gadd->ga_lon_gapi }}</td>
                                    <td>{{ $gadd->ga_morf_gapi ? $gadd->ga_morf_gapi : '-' }}</td>
                                    <td>{{ $gadd->ga_rtype_gapi ? $gadd->ga_rtype_gapi : '-' }}</td>
                                    <td>
                                        <a href="{{ route('chambers.v1.gunungapi.data-dasar.show',['id'=>$gadd->ga_code]) }}" class="m-t-xs m-b-xs btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                        <a href="{{ route('chambers.v1.gunungapi.data-dasar.edit',['id'=>$gadd->ga_code]) }}" class="m-t-xs m-b-xs btn btn-sm btn-warning btn-outline">Edit</a>
                                    </td>
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

@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        $('#table-gadd').dataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [[30, 60, 100, -1], [30, 60, 100, "All"]],
            buttons: [
                { extend: 'copy', className: 'btn-sm'},
                { extend: 'csv', title: 'Data Dasar Gunung Api', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]} },
                { extend: 'pdf', title: 'Data Dasar Gunung Api', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]} },
                { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]} }
            ]

        });
    });
</script>
@endsection