@extends('layouts.default')

@section('title')
    MAGMA | List Roles User
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
                            <li><a href="{{ route('chamber') }}">Chamber</a></li>
                            <li>
                                <span>Roles</span>
                            </li>
                            <li class="active">
                                <span>List </span>
                            </li>
                        </ol>
                    </div>
                    <h2 class="font-light m-b-xs">
                        List Roles User
                    </h2>
                    <small>Daftar Roles pengguna MAGMA Indonesia -  Pusat Vulkanologi dan Mitigasi Bencana Geologi</small>
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
                            <div class="panel-tools">
                                <a class="showhide">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="closebox">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                            Tabel Roles
                        </div>
                        @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                            <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                        </div>
                        @endif
                        <div class="panel-body">
                            <table id="table-roles" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Permission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->permissions()->pluck('name')->implode(', ') }}</td>
                                        <td>
                                            <a href="{{ route('roles.edit',['id'=>$role->id]) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">Edit</a>
                                            <form style="display:inline" method="POST" action="{{ route('roles.destroy',['id'=>$role->id]) }}" accept-charset="UTF-8">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
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
    <script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>

@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            // Initialize table
            $('#table-roles').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                buttons: [
                    { extend: 'copy', className: 'btn-sm' },
                    { extend: 'csv', title: 'Daftar Roles', className: 'btn-sm', exportOptions: { columns: [ 0, 1 ]} },
                    { extend: 'pdf', title: 'Daftar Roles', className: 'btn-sm', exportOptions: { columns: [ 0, 1 ]} },
                    { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} }
                ]

            });

            $('form').on('submit',function (e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();

                var $tableuser = $('#table-roles').DataTable();
                var $row = $tableuser.row($(this).parents('tr'));

                e.preventDefault();

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
            });

        });

    </script>
@endsection