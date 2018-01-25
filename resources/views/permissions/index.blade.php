@extends('layouts.default')

@section('title')
    List User Permissions
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/ladda/dist/ladda-themeless.min.css') }}" />
@endsection

@section('content-header')
        <div class="small-header">
            <div class="hpanel">
                <div class="panel-body">
                    <div id="hbreadcrumb" class="pull-right">
                        <ol class="hbreadcrumb breadcrumb">
                            <li><a href="{{ route('chamber') }}">Chamber</a></li>
                            <li>
                                <span>Permissions</span>
                            </li>
                            <li class="active">
                                <span>List </span>
                            </li>
                        </ol>
                    </div>
                    <h2 class="font-light m-b-xs">
                        List User Permissions
                    </h2>
                    <small>Daftar Permissions pengguna MAGMA Indonesia -  Pusat Vulkanologi dan Mitigasi Bencana Geologi</small>
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
                            Tabel Permissions
                        </div>
                        @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                            <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                        </div>
                        @endif
                        <div class="panel-body">
                            <table id="table-permissions" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Permission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                    <tr>
                                        <td class="permission">{{ $permission->name }}</td>
                                        <td>
                                            <button type="button" value="{{ $permission->id }}" class="btn btn-sm btn-success btn-outline edit" data-toggle="modal" data-target="#edit">Edit</button>
                                            <form style="display:inline" id="form-delete" method="POST" action="{{ route('permissions.destroy',['id'=>$permission->id]) }}" accept-charset="UTF-8">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button class="btn btn-sm btn-danger btn-outline" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if(!$permissions->isEmpty())
                            <div class="modal fade hmodal-success" id="edit" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form role="form" id="form-edit" method="POST" action="{{ url()->current() }}/">
                                            <div class="color-line"></div>
                                            <div class="modal-header">
                                                <h4 class="modal-title">Rubah Permission</h4>
                                                <small class="font-bold">Rubah nama Permission sesuai dengan fungsi yang Roles</small>
                                            </div>
                                            <div class="modal-body">
                                                    {{ method_field('PUT') }}
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>Nama Permission</label> 
                                                        <input id="modal-permission" name="name" type="text" placeholder="Masukkan Nama Permission" class="form-control" value="" required>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                <button type="submit" id="form-submit" value="{{ $permission->id }}" class="btn btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Submit</span><span class="ladda-spinner">
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

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
    <script src="{{ asset('vendor/ladda/dist/spin.min.js') }}"></script>
    <script src="{{ asset('vendor/ladda/dist/ladda.min.js') }}"></script>
    <script src="{{ asset('vendor/ladda/dist/ladda.jquery.min.js') }}"></script>

@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            var $id,$row;

            $('.edit').click(function(e){

                var $tableuser = $('#table-permissions').DataTable(),
                    $permission = $tableuser.row($(this).parents('tr')).data()[0];
                
                $row = $tableuser.row($(this).parents('tr'));

                $id = $(this).val();

                var $modal = $('#modal-permission').val($permission);

            });

            $('#form-submit').on('click',function (e) {

                e.preventDefault();
                $('.close-modal').toggle();

                var l = Ladda.create(this);
                l.start();

                var $url = $('form#form-edit').attr('action')+$id,
                    $data = $('form#form-edit').serialize();

                $.ajax({
                    url: $url,
                    data: $data,
                    type: 'POST',
                    success: function(res){
                        if (res.success){
                            var $temp = $row.data();
                            $temp[0] = $('#modal-permission').val();
                            $row.data($temp).invalidate();
                            setTimeout(function(){
                                l.stop();
                                $('.close-modal').toggle();
                                $('.modal').modal('hide');
                            },1500);
                        }
                    }
                });

            });

            // Initialize table
            $('#table-permissions').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                buttons: [
                    { extend: 'copy', className: 'btn-sm' },
                    { extend: 'csv', title: 'Daftar Permissions', className: 'btn-sm', exportOptions: { columns: [0]} },
                    { extend: 'pdf', title: 'Daftar Permissions', className: 'btn-sm', exportOptions: { columns: [0]} },
                    { extend: 'print', className: 'btn-sm' }
                ]

            });

            $('form#form-delete').on('submit',function (e) {

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();

                var $tableuser = $('#table-permissions').DataTable();
                var $row = $tableuser.row($(this).parents('tr'));

                e.preventDefault();

                swal({
                    title: "Anda yakin?",
                    text: "Data yang telah dihapus tidak bisa dikembalikan",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Hapus Gan!!",
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