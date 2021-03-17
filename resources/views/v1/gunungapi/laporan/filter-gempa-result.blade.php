@extends('layouts.default')

@section('title')
v1 - Hasil Laporan Berdasarkan Data Gempa
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
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
                    <li>
                        <span>Gunung Api</span>
                    </li>
                    <li class="active">
                        <span>Cari Laporan (VAR) </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Cari Laporan MAGMA-VAR
            </h2>
            <small>Memberikan hasil pencarian data laporan gunung api sesuai dengan parameter yang kita berikan</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            @role('Super Admin')
            @component('components.json-var')
                @slot('title')
                    For Developer
                @endslot
            @endcomponent
            @endrole
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Tabel Gempa Periode {{ $dates['start'] }} hingga {{  $dates['end'] }}
                </div>

                <div class="panel-body table-responsive">
                    <table id="table-gempa" class="table table-striped">
                        <thead>
                            <tr>
                                @foreach ($headers as $header)
                                <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            <tr>
                                <td>{{ $result['gunung_api'] }}</td>
                                @foreach ($result['gempa'] as $gempa)
                                <td>{{ $gempa['jumlah'] }}</td>
                                @endforeach
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
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    @role('Super Admin')
    $('#json-renderer').jsonViewer(@json($results), {collapsed: true});
    @endrole
    // Initialize table
    $('#table-gempa').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[-1, 10, 30], ["All", 10, 30]],
        buttons: [
            { extend: 'copy', className: 'btn-sm'},
            { extend: 'csv', title: 'Daftar Gempa', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
            { extend: 'pdf', title: 'Daftar Gempa', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
            { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} }
        ]
    });

});
</script>
@endsection