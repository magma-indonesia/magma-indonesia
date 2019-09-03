@extends('layouts.default')

@section('title')
    Daftar Kesimpulan MAGMA-VAR
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="content animate-panel content-boxed normalheader">
        <div class="hpanel">
            <div class="panel-body">   
                <h2 class="font-light m-b-xs">
                    Daftar Kesimpulan MAGMA-VAR 
                </h2>
                <small class="font-light"> Digunakan dalam pelaporan MAGMA-VAR</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content content-boxed animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar Kesimpulan
                    </div>

                    @if ($kesimpulans->isEmpty())
                    <div class="alert alert-danger">
                        <i class="fa fa-gears"></i> Data Kesimpulan belum ada. <a href="{{ route('chambers.v1.gunungapi.form-kesimpulan.create') }}"><b>Mau buat baru?</b></a>
                    </div>
                    @else

                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif

                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.v1.gunungapi.form-kesimpulan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Buat Kesimpulan</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-kesimpulan" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Gunung Api</th>
                                        <th>Tingkat Aktivitas</th>
                                        <th>Kesimpulan</th>
                                        <th>Dibuat oleh</th>
                                        <th>Digunakan Sebanyak</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kesimpulans as $key => $kesimpulan)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $kesimpulan->gunungapi->ga_nama_gapi }}</td>
                                        <td>{{ $kesimpulan->status }}</td>
                                        <td>
                                                {!! $kesimpulan->kesimpulan_1 ? '<p>'.$kesimpulan->kesimpulan_1.'</p>' : '' !!}
                                                {!! $kesimpulan->kesimpulan_2 ? '<p>'.$kesimpulan->kesimpulan_2.'</p>' : '' !!}
                                                {!! $kesimpulan->kesimpulan_3 ? '<p>'.$kesimpulan->kesimpulan_3.'</p>' : '' !!}
                                                {!! $kesimpulan->kesimpulan_4 ? '<p>'.$kesimpulan->kesimpulan_4.'</p>' : '' !!}
                                                {!! $kesimpulan->kesimpulan_5 ? '<p>'.$kesimpulan->kesimpulan_5.'</p>' : '' !!}
                                        </td>
                                        <td>{{ $kesimpulan->user->vg_nama }}</td>
                                        <td>{{ $kesimpulan->vars_count }}</td>
                                        <td>
                                            @if (auth()->user()->nip == $kesimpulan->user->vg_nip)
                                            <a href="{{ route('chambers.v1.gunungapi.form-kesimpulan.edit', $kesimpulan) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                            @else
                                            -
                                            @endif

                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.v1.gunungapi.form-kesimpulan.destroy', $kesimpulan) }}" accept-charset="UTF-8">
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

                    <div class="panel-footer">
                        <div class="alert alert-danger">
                            <i class="fa fa-gears"></i> Untuk menghapus data yang digunakan, silahkan kontak Admin</a>
                        </div>
                    </div>
                    @endif
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
