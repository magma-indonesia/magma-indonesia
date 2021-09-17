@extends('layouts.default')

@section('title')
    MAGMA | Data Pegawai
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
                        <li class="active">
                            <span>Pegawai </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Data Pegawai PVMBG
                </h2>
                <small>Daftar pengguna MAGMA Indonesia -  Pusat Vulkanologi dan Mitigasi Bencana Geologi</small>
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
                        Tabel Pegawai
                    </div>

                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif

                    <div class="panel-body float-e-margins">
                        <a href="{{ route('chambers.administratif.administrasi.index') }}" type="button" class="btn btn-magma btn-outline">Data Administrasi</a>
                        @role('Super Admin')
                        <a href="{{ route('chambers.users.create') }}" type="button" class="btn btn-magma btn-outline">Tambah Data Pegawai</a>
                        <a href="{{ route('chambers.users.reset') }}" type="button" class="btn btn-danger btn-outline">Reset Password</a>
                        <a href="{{ route('chambers.users.statistik.login') }}" type="button" class="btn btn-danger btn-outline">Login Stats</a>
                        @endrole
                    </div>

                    <div class="panel-body">

                        <div class="table-responsive">
                            <table id="table-users" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Bidang</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        @role('Super Admin')
                                        <th>Roles</th>
                                        <th style="min-width: 20%;">Action</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{!! strlen($user->nip)<18 ? $user->nip.'<b>KTP</b>' : $user->nip !!}</td>
                                        <td>{{ $user->administrasi->bidang->nama ?? '-' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->status ? 'Aktif':'Tidak Aktif' }}</td>
                                        @role('Super Admin')
                                        <td>{{ $user->roles()->pluck('name')->implode(', ') }}</td>
                                        <td>
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.users.destroy',['id'=>$user->id]) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <a href="{{ route('chambers.users.edit',['id'=>$user->id]) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                        </td>
                                        @endrole
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
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>

@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            // Initialize table
            $('#table-users').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[15, 30, 100, -1], [30, 60, 100, "All"]],
                buttons: [
                    { extend: 'copy', className: 'btn-sm'},
                    { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
                    { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
                    { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} }
                ]

            });

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