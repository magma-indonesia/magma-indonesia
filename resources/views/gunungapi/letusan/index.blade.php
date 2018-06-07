@extends('layouts.default')

@section('title')
    Gunung Api | Laporan Letusan 
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
                            <a href="{{ route('chambers.index') }}">Chambers</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.datadasar.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.letusan.index') }}">Letusan</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Laporan Letusan Gunung Api
                </h2>
                <small>Daftar data laporan letusan Gunung Api</small>
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
                        Volcano Eruption Notice
                    </div>
                    <div class="panel-body">
                        <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                            <a href="{{ route('chambers.letusan.create') }}" class="btn btn-outline btn-block btn-success" type="button">Buat Informasi Letusan</a>
                        </div>
                    </div>
                </div>
                <div class="hpanel">
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body">
                        {{ $vens->links() }}
                        <div class="table-responsive">
                            <table id="table-ven" class="table  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Gunung Api</th>
                                        <th>Waktu Letusan</th>
                                        <th>Visual</th>
                                        <th>TInggi Letusan (m)</th>
                                        <th>Warna Abu</th>
                                        <th>Arah Abu (ke)</th>
                                        <th>Durasi</th>
                                        <th>Pelapor</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vens as $key => $ven)
                                    <tr>
                                        <td>{{ $vens->firstItem()+$key }}</td>
                                        <td><a href="{{ route('chambers.letusan.show',['uuid' => $ven->uuid]) }}" target="_blank">{{ $ven->gunungapi->name }}</a></td>
                                        <td>{{ $ven->date.' '.$ven->time.' '.$ven->gunungapi->zonearea }}</td>
                                        <td>{{ $ven->visibility ? 'Teramati':'Tidak Teramati' }}</td>
                                        <td>{{ $ven->height }}</td>
                                        <td>{{ implode(', ',$ven->wasap) }}</td>
                                        <td>{{ implode(', ',$ven->arah_asap) }}</td>
                                        <td>{{ $ven->durasi > 0 ?  $ven->durasi : 'Tidak Teramati' }}</td>
                                        <td>{{ $ven->user->name }}</td>
                                        <td>
                                            <a href="{{ route('chambers.letusan.edit',['uuid'=>$ven->uuid]) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">Edit</a>   
                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.letusan.destroy',['uuid'=>$ven->uuid]) }}" accept-charset="UTF-8">
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
                                l.stop();
                                $label.html('Error. Coba lagi?');
                            }
                        });
                    }
                });

                return false;
            });
        });
    </script>
@endsection