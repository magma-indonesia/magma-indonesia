@extends('layouts.default')

@section('title')
    Daftar Rekomendasi MAGMA-VAR
@endsection

@section('add-vendor-css')
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
    <div class="content animate-panel content-boxed normalheader">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Daftar Rekomendasi MAGMA-VAR
                </h2>
                <small class="font-light"> Digunakan dalam pelaporan MAGMA-VAR</small>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endrole
@endsection

@section('content-body')
<div class="content content-boxed animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Rekomendasi
                </div>

                <div class="panel-body">
                    <div class="alert alert-info">
                        <i class="fa fa-bolt"></i> Rekomendasi gunung api per Tingkat Aktivitas. </a>
                    </div>
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Gunung Api</th>
                                    <th>Tingkat Aktivitas</th>
                                    <th>Rekomendasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach ($gadds as $gadd)

                                @foreach ($gadd->rekomendasi as $key => $rekomendasi)
                                @php
                                    $counter = $counter+1;
                                @endphp
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $gadd->name }}</td>
                                    <td>{{ $rekomendasi->status_text }}</td>
                                    <td>{!! nl2br($rekomendasi->rekomendasi) !!}</td>
                                </tr>
                                @endforeach

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

@section('add-script')
@role('Super Admin')
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
@endrole
@endsection