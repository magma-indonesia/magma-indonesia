@extends('layouts.default') 

@section('title') 
v1 - Press Release 
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote-bs3.css') }}" />
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
                            <span>MAGMA v1</span>
                        </li>
                        <li class="active">
                            <span>Press Release </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Press Release
                </h2>
                <small>Daftar Press Release yang pernah dibuat oleh PVMBG.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content content-boxed animate-panel">
        <div class="row">
            <div class="col-lg-12">

				@if(Session::has('flash_message'))
				<div class="alert alert-success">
					<i class="fa fa-bolt"></i> {!! session('flash_message') !!}
				</div>
                @endif

                @role('Super Admin|Humas PVMBG')
                <div class="hpanel">
                    <div class="panel-heading">
                        Press Release untuk MAGMA v1
                    </div>
                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.v1.press.create') }}" class="btn btn-outline btn-block btn-magma" type="button">Buat Press Release</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endrole

                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar Press Release
                    </div>
                    <div class="panel-body">
                        {{ $presses->onEachSide(1)->links() }}
                        <div class="table-responsive">
                            <table id="table-press" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Waktu</th>
                                        <th style="min-width: 20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presses as $key => $press)
                                    <tr>
                                        <td>{{ $presses->firstItem()+$key }}</td>
                                        <td>{{ $press->judul }}</td>
                                        <td>{{ $press->log }}</td>
                                        <td>
                                            <a href="{{ route('chambers.v1.press.show',['id'=>$press->id]) }}" class="m-t-xs m-b-xs btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                            <a href="{{ route('chambers.v1.press.edit',['id'=>$press->id]) }}" class="m-t-xs m-b-xs btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                            @role('Super Admin|Humas PVMBG')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.v1.press.destroy',['id'=>$press->id]) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="m-t-xs m-b-xs btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                            @endrole
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $presses->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {

            $('.click-here').click(function() {
                window.open($(this).data('href'),'_blank');
            });

            $('body').on('submit','#deleteForm',function (e) {
                e.preventDefault();                

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();

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
                                console.log(data);
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    setTimeout(function(){
                                        location.reload();
                                    },2000);
                                }
                            },
                            error: function(data){
                                var $errors = {
                                    'status': data.status,
                                    'exception': data.responseJSON.exception,
                                    'file': data.responseJSON.file,
                                    'line': data.responseJSON.line
                                };
                                console.log($errors);
                                swal("Gagal!", data.responseJSON.exception, "error");
                            }
                        });
                    }
                });

                return false;
            });
        });
    </script>
@endsection