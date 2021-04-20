@extends('layouts.default')

@section('title')
Daftar Pegawai Berdasarkan Gunung Api
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
                        <span>Users </span>
                    </li>
                    <li class="active">
                        <span>Berdasarkan Gunung Api</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Daftar Pengguna MAGMA Indonesia v1
            </h2>
            <small>Berdasarkan data gunung api.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="hpanel">
                <div class="panel-heading">
                    Data Pegawai
                </div>
                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.v1.kantor.index') }}"
                                class="btn btn-outline btn-block btn-magma" type="button">Seluruh Penempatan</a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.v1.kantor.pos-pengamatan.index') }}"
                                class="btn btn-outline btn-block btn-magma" type="button">Hanya Pos Pengamatan</a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.v1.kantor.gunung-api.index') }}"
                                class="btn btn-outline btn-block btn-magma" type="button">Berdasarkan Gunung Api</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-danger">
                <i class="fa fa-bolt"></i> Data ini perlu diperbarui. Terutama terkait penempatan pegawai.
            </div>

            <div class="hpanel">
                <div class="panel-heading">
                    Tabel Users
                </div>

                <div class="panel-body table-responsive">
                    <table id="table-users" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gunung Api</th>
                                <th style="min-width: 10%;">Provinsi</th>
                                <th>Jumlah Pos PGA</th>
                                <th>Jumlah Pegawai</th>
                                <th>Daftar Pegawai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gadds as $index => $gadd)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $gadd->ga_nama_gapi }}</td>
                                <td>{{ $gadd->ga_prov_gapi }}</td>
                                <td>{{ $gadd->pos_pgas_count }}</td>
                                <td>{{ $gadd->users_count }}</td>
                                <td>{{ implode(', ', $gadd->users->pluck('vg_nama')->all()) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            // Initialize table
            $('#table-users').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[30, 60, 100, -1], [30, 60, 100, "All"]],
                buttons: [
                    { extend: 'copy', className: 'btn-sm'},
                    { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4,5 ]} },
                    { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4,5 ]} },
                    { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4,5 ]} }
                ]
            });
        });

</script>
@endsection