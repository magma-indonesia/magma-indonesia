@extends('layouts.default')

@section('title')
    Seismometer
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
                        <span>Seismometer </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Daftar Seismometer yang terpasang
            </h2>
            <small>Diurut berdasarkan nama gunung api</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">

    @if ($lives->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Live Seismogram - {{ $lives->count() }} Channels
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Gunung Api</th>
                                    <th>SCNL</th>
                                    <th>Aktif</th>
                                    <th>Hit</th>
                                    <th>Updated At</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($lives as $live)
                            <tr>
                                <td>{{ $live->gunungapi->name }}</td>
                                <td>{{ $live->scnl }}</td>
                                <td>{{ $live->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $live->hit }}</td>
                                <td>{{ $live->live_seismogram->updated_at ?? 'Belum ada data' }}</td>
                                <td>
                                    <a href="{{ route('chambers.seismometer.edit', $live) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
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
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Station Seismometer - {{ $gadds->sum('seismometers_count') }} Channels
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.seismometer.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Seismometer</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    @foreach ($gadds as $gadd)
                    <div class="hpanel collapsed">
                        <div class="panel-heading hbuilt">
                            <div class="panel-tools">
                                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                            </div>
                            {{ $gadd->name }} - {{ $gadd->seismometers->count() }}
                        </div>
                        
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>SCNL</th>
                                            <th>Aktif</th>
                                            <th>Live Seismogram</th>
                                            <th>Hit</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($gadd->seismometers as $seismometer)
                                    <tr>
                                        <td>{{ $seismometer->scnl }}</td>
                                        <td>{{ $seismometer->is_active ? 'Ya' : 'Tidak' }}</td>
                                        <td>{{ $seismometer->published ? 'Ya' : 'Tidak' }}</td>
                                        <td>{{ $seismometer->hit }}</td>
                                        <td>
                                            <a href="{{ route('chambers.seismometer.edit', $seismometer) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.seismometer.destroy', $seismometer) }}" accept-charset="UTF-8">
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
                    @endforeach
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