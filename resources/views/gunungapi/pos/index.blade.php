@extends('layouts.default')

@section('title')
    Gunung Api | Pos Pengamatan Gunung Api
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>Data Dasar</span>
                        </li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Pos Pengamatan Gunung Api</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Pos Pengamatan Gunung Api
                </h2>
                <small>Daftar Pos Pengamatan Gunung Api Indonesia</small>
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
                        Tabel Pos Pengamatan Gunung Api
                    </div>
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body table-responsive">
                        <table id="table-pos" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Pos Pengamatan</th>
                                    <th>Alamat</th>
                                    <th>Ketinggian (mdpl)</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Google Map</th>                                    
                                    <th width="180px">Action</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pgas as $key => $pga)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $pga->gunungapi->name }}</td>
                                    <td>{{ $pga->observatory }}</td>
                                    <td>{{ $pga->address }}</td>
                                    <td>{{ $pga->elevation }}</td>
                                    <td>{{ $pga->latitude }}</td>
                                    <td>{{ $pga->longitude }}</td>
                                    <td><a class="btn btn-sm btn-magma btn-outline" href="http://maps.google.com/maps?q={{ $pga->latitude }},{{ $pga->longitude }}" target="_blank">Link</a></td>                                    
                                    <td>
                                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.pos.destroy',['id'=>$pga->id]) }}" accept-charset="UTF-8">
                                            @method('DELETE')
                                            @csrf
                                            <a href="{{ route('chambers.pos.edit',$pga) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;" target="_blank" title="Edit Pos Pengamatan"><i class=" pe-7s-note"></i></a>
                                            <a href="{{ route('chambers.pos.create',['id'=>$pga->code_id]) }}" class="btn btn-sm btn-warning2 btn-outline" style="margin-right: 3px;" target="_blank">Tambah Pos</a>
                                            <button value="Delete" class="btn btn-sm btn-danger delete" type="submit" title="Delete Pos Pengamatan"><i class="pe-7s-trash"></i></button>
                                        </form>
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
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>

@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            var t = $('#table-pos').DataTable({
                columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: [0,7,8]
                }],
                order: [[ 1, 'asc' ]],
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                    "lengthMenu": [[20, 40, 60, -1], [20, 40, 60, "All"]],
                    buttons: [
                        { extend: 'copy', className: 'btn-sm'},
                        { extend: 'csv', title: 'Daftar Pos Pengamatan Gunung Api', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]} },
                        { extend: 'pdf', title: 'Daftar Pos Pengamatan Gunung Api', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]}, orientation: 'landscape' },
                        { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]}, orientation: 'landscape' }
                ]
            });
    
            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $('body').on('submit','#deleteForm',function (e) {
                e.preventDefault();                

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();

                var $tableuser = $('#table-users').DataTable();
                var $row = $tableuser.row($(this).parents('tr'));

                swal({
                    title: "Anda yakin?",
                    text: "Data yang telah dihapus tidak bisa dikembalikan",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, hapus!",
                    cancelButtonText: "Gak jadi deh!",
                    closeOnConfirm: false,
                    closeOnCancel: true },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: $url,
                            data: $data,
                            type: 'POST',
                            success: function(data){
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    $row.remove().draw();
                                }
                            }
                        });
                    }
                });

                return false;
            });

        });

    </script>
@endsection