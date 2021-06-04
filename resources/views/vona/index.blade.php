@extends('layouts.default')

@section('title')
    VONA | Volcano Observatory Notice for Aviation
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
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.vona.index') }}">VONA</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar VONA
                </h2>
                <small>Daftar VONA yang pernah dibuat dan dikirim kepada stakeholder terkait.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        VONA
                    </div>
                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.vona.create') }}" class="btn btn-outline btn-block btn-magma" type="button">Buat VONA Baru</a>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.vona.draft') }}" class="btn btn-outline btn-block btn-magma" type="button">Draft VONA</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hpanel">
                    <div class="panel-heading">
                        Cari VONA
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="GET" action="{{ route('chambers.vona.search') }}">
                            <div class="input-group">
                                <input name="q" class="form-control" type="text" placeholder="Cari VONA ...">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar VONA Terkirim
                    </div>
                    <div class="panel-body">
                        {{ $vonas->links() }}
                        <div class="table-responsive">
                            <table id="table-jabatan" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano</th>
                                        <th>Issued (UTC)</th>
                                        <th>Current Code</th>
                                        <th>Previous Code</th>
                                        <th>Cloud Height (ASL)</th>
                                        <th>Sender</th>
                                        <th style="min-width: 180px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vonas as $key => $vona)
                                    <tr>
                                        <td>{{ $vonas->firstItem()+$key }}</td>
                                        <td><a href="{{ route('chambers.vona.show',['uuid' => $vona->uuid])}}" target="_blank">{{ $vona->gunungapi->name }}</a></td>
                                        <td>{{ $vona->issued }}</td>
                                        <td>{{ title_case($vona->cu_code) }}</td>
                                        <td>{{ strtolower($vona->prev_code) }}</td>
                                        <td>{{ $vona->vch_asl > 0 ? round($vona->vch_asl*0.3048).' meter' : 'Tidak teramati' }}</td>
                                        <td>{{ $vona->user->name }}</td>
                                        <td>
                                            <a href="{{ route('chambers.vona.show',['uuid'=>$vona->uuid]) }}" class="m-t-xs m-b-xs btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                            <a href="{{ route('chambers.vona.edit',['uuid'=>$vona->uuid]) }}" class="m-t-xs m-b-xs btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.vona.destroy',['uuid'=>$vona->uuid]) }}" accept-charset="UTF-8">
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
                        {{ $vonas->links() }}
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