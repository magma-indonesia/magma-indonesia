@extends('layouts.default')

@section('title')
    Detail Kegiatan Bidang MGA
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="content content-boxed animate-panel normalheader">
        <div class="hpanel">
            <div class="panel-body">   
                <h2 class="font-light m-b-xs p-lg">
                    {{ '('.$kegiatan->jenis_kegiatan->bidang->code.') '.$kegiatan->tahun.' - '.$kegiatan->jenis_kegiatan->nama }} <small class="font-light"> - Jumlah kegiatan {{ $kegiatan->detail_kegiatan->count()  }}</small>
                </h2>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content content-boxed animate-panel">
        <div class="row">

            {{-- Panel Jumlah Kegiatan --}}
            <div class="col-lg-6">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Jumlah Kegiatan</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-share fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h3 class="m-b-xs">{{ $kegiatan->target_jumlah }}</h3>
                            <span class="font-bold no-margins">Target Jumlah</span>
                            <div class="progress m-t-xs full progress-small">
                                <div style="width: {{ number_format((float)$kegiatan->detail_kegiatan_count/$kegiatan->target_jumlah*100, 2, '.', '') }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ number_format((float)$kegiatan->detail_kegiatan_count/$kegiatan->target_jumlah*100, 2, '.', '') }}" role="progressbar" class=" progress-bar progress-bar-success">
                                    <span class="sr-only">{{ number_format((float)$kegiatan->detail_kegiatan_count/$kegiatan->target_jumlah*100, 2, '.', '') }}% Realisasi</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stats-label">Jumlah Realisasi</small>
                                    <h4>{{ $kegiatan->detail_kegiatan_count }}</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stats-label">% Realisasi</small>
                                    <h4>{{ number_format((float)$kegiatan->detail_kegiatan_count/$kegiatan->target_jumlah*100, 2, '.', '') }}%</h4>
                                </div>

                                <div class="col-xs-12">
                                    <small class="stats-label">Sisa Kegiatan</small>
                                    <h4>{{ ($kegiatan->target_jumlah-$kegiatan->detail_kegiatan_count) <0 ? 0 : $kegiatan->target_jumlah-$kegiatan->detail_kegiatan_count}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Target dan Jumlah Realisasi Kegiatan
                    </div>
                </div>
            </div>

            {{-- Panel Anggaran Kegiatan --}}
            <div class="col-lg-6">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Anggaran</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-share fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h3 class="m-b-xs">{{ number_format($kegiatan->target_anggaran,0,',','.') }}</h3>
                            <span class="font-bold no-margins">Target Anggaran</span>

                            <div class="progress m-t-xs full progress-small">
                                <div style="width: {{ number_format((float)$kegiatan->total_biaya/$kegiatan->target_anggaran*100, 2, '.', '') }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ number_format((float)$kegiatan->total_biaya/$kegiatan->target_anggaran*100, 2, '.', '') }}" role="progressbar" class=" progress-bar progress-bar-success">
                                    <span class="sr-only">{{ number_format((float)$kegiatan->total_biaya/$kegiatan->target_anggaran*100, 2, '.', '') }}% Realisasi Anggaran</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stats-label">Realisasi Anggaran</small>
                                    <h4>{{ number_format($kegiatan->total_biaya,0,',','.') }}</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stats-label">% Realisasi</small>
                                    <h4>{{ number_format((float)$kegiatan->total_biaya/$kegiatan->target_anggaran*100, 2, '.', '') }}%</h4>
                                </div>

                                <div class="col-xs-12">
                                    <small class="stats-label">Sisa Anggaran Kegiatan</small>
                                    <h4>{{ number_format($kegiatan->target_anggaran-$kegiatan->total_biaya,0,',','.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Target dan Realisasi Anggaran
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">

                @if ($kegiatan->detail_kegiatan->isEmpty())
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Kegiatan {{ $kegiatan->jenis_kegiatan->nama }} belum ada. <a href="{{ route('chambers.administratif.mga.detail-kegiatan.create', ['id' => $kegiatan->id ]) }}"><b>Buat baru?</b></a>
                </div>
                @else

                <div class="hpanel">
                    <div class="panel-heading">
                        Detail Kegiatan
                    </div>

                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.administratif.mga.detail-kegiatan.create', ['id' => $kegiatan->id ]) }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Kegiatan</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-kegiatan" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Lokasi</th>
                                        <th>Waktu Berangkat</th>
                                        <th>Waktu Pulang</th>
                                        <th>Jumlah Hari</th>
                                        <th>Total</th>
                                        <th>Ketua Tim</th>
                                        <th>Proposal</th>
                                        <th>Laporan</th>
                                        @role('Super Admin|Kortim MGA')
                                        <th>Tim</th>
                                        <th width="20%">Action</th>
                                        @endrole
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($kegiatan->detail_kegiatan as $key => $detail)
                                    <tr>
                                        <td>{{ $key+1}}</td>
                                        <td>{{ $detail->code_id ? $detail->gunungapi->name : $detail->lokasi_lainnya }}</td>
                                        <td>{{ $detail->start_date }}</td>
                                        <td>{{ $detail->end_date }}</td>
                                        <td>{{ $detail->jumlah_hari }}</td>
                                        <td>{{ number_format($detail->biaya_kegiatan_total,0,',','.') }}</td>
                                        <td>{{ $detail->ketua->name }}</td>
                                        <td>
                                            @if ($detail->proposal)
                                            <a href="{{ route('chambers.administratif.mga.detail-kegiatan.download', ['id' => $detail->id, 'type' => 'proposal']) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">Download</a>                                                
                                            @else
                                            <i class="fa fa fa-close text-danger"></i>
                                            @endif
                                        
                                        </td>
                                        <td>
                                            @if ($detail->laporan)
                                            <a href="{{ route('chambers.administratif.mga.detail-kegiatan.download', ['id' => $detail->id, 'type' => 'laporan']) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">Download</a>                                                
                                            @else
                                            <i class="fa fa fa-close text-danger">
                                            @endif                                        
                                        </td>
                                        @role('Super Admin|Kortim MGA')
                                        <td>
                                            <a href="{{ route('chambers.administratif.mga.anggota-kegiatan.create', ['id' => $detail->id]) }}" class="btn btn-sm btn-info btn-outline" style="margin-right: 3px;">Add Tim</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('chambers.administratif.mga.detail-kegiatan.show', $detail) }}" class="btn btn-sm btn-info btn-outline" style="margin-right: 3px;">Detail</a>

                                            <a href="{{ route('chambers.administratif.mga.detail-kegiatan.edit', $detail) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                            @role('Super Admin')                                
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.administratif.mga.detail-kegiatan.destroy', $detail) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                            @endrole
                                        </td>
                                        @endrole
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