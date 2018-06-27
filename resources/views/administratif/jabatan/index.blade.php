@extends('layouts.default')

@section('title')
    Daftar Jabatan
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
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li>
                            <span>Administratif </span>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.administratif.jabatan.index') }}">Daftar Jabatan</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Jabatan
                </h2>
                <small>Daftar Jabatan yang terdaftar di Pusat Vulkanologi dan Mitigasi Bencana Geologi.Digunakan untuk menambah kelas jabatan karyawan.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                @if($jabatans->isEmpty())
                <div class="alert alert-info">
                    <i class="fa fa-bolt"></i> Tidak ada data Jabatan.
                    <a href="{{ route('chambers.administratif.jabatan.create') }}">
                        <strong>Buat Jabatan baru?</strong>
                    </a>
                </div>
                @else
				@if(Session::has('flash_message'))
				<div class="alert alert-success">
					<i class="fa fa-bolt"></i> {!! session('flash_message') !!}
				</div>
				@endif
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar Jabatan yang terdaftar
                    </div>
                    <div class="panel-body float-e-margins m-b">
                        <div class="row text-center">
                            <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                                <a href="{{ route('chambers.administratif.jabatan.create') }}" class="btn btn-outline btn-block btn-magma" type="button">Buat Jabatan Baru</a>
                            </div>
                            <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                                <a href="{{ route('chambers.users.administrasi.index') }}" class="btn btn-block btn-outline btn-warning" type="button">Tambahkan Jabatan ke User</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="table-jabatan" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 25px;">#</th>
                                    <th>Jabatan</th>
                                    <th style="min-width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jabatans as $key => $jabatan)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $jabatan->nama }}</td>
                                    <td>
                                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.administratif.jabatan.destroy',['id'=>$jabatan->id]) }}" accept-charset="UTF-8">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" value="{{ $jabatan->id }}" class="btn btn-sm btn-magma btn-outline edit" data-toggle="modal" data-target="#edit">Edit</button>
                                            <button value="Delete" class="btn btn-sm btn-danger delete" type="submit" title="Delete {{ $jabatan->nama }}"><i class="fa fa-trash-o"></i> <span class="bold">Delete</span></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="modal fade hmodal-success" id="edit" tabindex="-1" role="dialog"  aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form role="form" id="form-edit" method="POST" action="{{ url()->current() }}/">
                                        <div class="color-line"></div>
                                        <div class="modal-header">
                                            <h4 class="modal-title">Rubah Jabatan</h4>
                                            <small class="font-bold">Ganti nama Jabatan dan selaraskan dengan roles</small>
                                        </div>
                                        <div class="modal-body">
                                            @method('PUT')
                                            @csrf
                                            <div class="form-group">
                                                <label>Nama Jabatan</label> 
                                                <input id="modal-jabatan" name="name" type="text" placeholder="Masukkan Nama Jabatan" class="form-control m-b" required>
                                                <div class="alert alert-danger" style="display:none;"></div>
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                            <button type="submit" id="form-submit" value="{{ $jabatan->id }}" class="btn btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Submit</span><span class="ladda-spinner">
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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

        var t = $('#table-jabatan').DataTable({
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: [0]
            }],
            order: [[ 1, 'asc' ]],
            dom: "<'row'<'col-md-4 m-b'l><'col-md-4 m-b text-center'B><'col-md-4 col-xs-12'f>>tp",
                "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
                buttons: [
                    { extend: 'copy', className: 'btn-sm'},
                    { extend: 'csv', title: 'Daftar Jabatan', className: 'btn-sm', exportOptions: { columns: [ 0, 1 ]} },
                    { extend: 'pdf', title: 'Daftar Jabatan', className: 'btn-sm', exportOptions: { columns: [ 0, 1 ]}, orientation: 'portrait' },
                    { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1 ]}, orientation: 'portrait' }
            ]
        });

        $('.edit').click(function(e){
            console.log($(this).val());
            $('.alert-danger').empty().hide();
            var $tablejabatan = $('#table-jabatan').DataTable(),
                $jabatan = $tablejabatan.row($(this).parents('tr')).data()[1];
            
            $row = $tablejabatan.row($(this).parents('tr'));

            $id = $(this).val();
        
            var $modal = $('#modal-jabatan').val($jabatan);

        });

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        $('#form-submit').on('click',function (e) {

            e.preventDefault();
            $('.close-modal').toggle();

            var l = Ladda.create(this),            
                $error = $('.alert-danger');

            l.start();

            var $url = $('form#form-edit').attr('action')+$id,
                $data = $('form#form-edit').serialize();

            console.log($url);

            $.ajax({
                url: $url,
                data: $data,
                type: 'POST',
                success: function(data){
                    console.log(data);
                    if (data.success){
                        $($error).hide();
                        var $temp = $row.data();
                        $temp[1] = $('#modal-jabatan').val();
                        $row.data($temp).invalidate();
                        t.on( 'order.dt search.dt', function () {
                            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                cell.innerHTML = i+1;
                            } );
                        } ).draw();
                        setTimeout(function(){
                            l.stop();
                            $('.close-modal').toggle();
                            $('.modal').modal('hide');
                        },1500);
                    } else {
                        l.stop();
                    }
                },
                error: function(data) {
                    // console.log(data.responseJSON);
                    $error.show();
                    l.stop();
                    var $data = data.responseJSON;
                    console.log($data.errors.name);
                    $.each($data.errors.name, function(key, value) {
                        $('.alert-danger').append('<p><i class="fa fa-bolt"></i> '+value+'</p>');
                    });
                }
            });

        });

        $('body').on('submit','#deleteForm',function (e) {
            e.preventDefault();                

            var $url = $(this).attr('action'),
                $data = $(this).serialize();

            var $tablejabatan = $('#table-jabatan').DataTable();
            var $row = $tablejabatan.row($(this).parents('tr'));

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