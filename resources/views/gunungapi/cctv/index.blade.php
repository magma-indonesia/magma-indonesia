@extends('layouts.default')

@section('title')
    CCTV Gunung Api
@endsection

@section('add-vendor-css')
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
                        <span>Gunung Api</span>
                    </li>
                    <li class="active">
                        <span>CCTV </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Daftar CCTV yang terpasang
            </h2>
            <small>Diurut berdasarkan nama gunung api</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">

            @if ($cctvs->isEmpty())
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Data CCTV belum tersedia. <a href="{{ route('chambers.cctv.create') }}"><b>Buat baru?</b></a>
            </div>
            @else

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar CCTV Gunung Api
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.cctv.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah CCTV</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-kegiatan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Lokasi CCTV</th>
                                    <th>URL</th>
                                    <th>Hit</th>
                                    <th>Published</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cctvs as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->gunungapi->name }}</td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>{{ $item->url }}</td>
                                    <td>{{ $item->hit }}</td>
                                    <td>{{ $item->publish ? 'Ya' : 'Tidak'}}</td>
                                    <td>
                                        <a href="{{ route('chambers.cctv.show', $item->uuid) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>

                                        <a href="{{ route('chambers.cctv.edit', $item) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                        @role('Super Admin')
                                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.cctv.destroy', $item) }}" accept-charset="UTF-8">
                                            @method('DELETE')
                                            @csrf
                                            <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                        </form>
                                        @endrole
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @endif

            
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