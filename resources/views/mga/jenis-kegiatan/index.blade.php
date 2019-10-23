@extends('layouts.default')

@section('title')
    Kegiatan Utama Bidang MGA
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
                            <span>Administratif</span>
                        </li>
                        <li>
                            <span>MGA</span>
                        </li>
                        <li class="active">
                            <span>Kegiatan Utama </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar kegiatan utama bidang MGA
                </h2>
                <small>Meliputi seluruh kegiatan utama yang sedang, pernah, atau akan dilakukan (perencanaan) </small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">

                {{-- Kegiatan Utama --}}
                @if ($jenis->isEmpty())
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Data Kegiatan Utama belum tersedia. <a href="{{ route('chambers.administratif.mga.jenis-kegiatan.create') }}"><b>Buat baru?</b></a>
                </div>
                @else

                <div class="hpanel">
                    <div class="panel-heading">
                        Jenis-jenis Kegiatan MGA
                    </div>

                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.administratif.mga.jenis-kegiatan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Kegiatan Utama</a>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.administratif.mga.statistik-kegiatan.index') }}" class="btn btn-magma btn-outline btn-block" type="button">Data Statistik</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-kegiatan" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bidang</th>
                                        <th>Kegiatan Utama</th>
                                        <th>Jumlah</th>
                                        @role('Super Admin|Kortim MGA')
                                        <th width="20%">Action</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jenis as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->bidang->nama }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->detail_kegiatan_count }}</td>
                                        @role('Super Admin|Kortim MGA')
                                        <td>
                                            <a href="{{ route('chambers.administratif.mga.kegiatan.create') }}" class="btn btn-sm btn-info btn-outline" type="button"><i class="fa fa-plus"></i></a>

                                            <a href="{{ route('chambers.administratif.mga.jenis-kegiatan.edit', $item) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.administratif.mga.jenis-kegiatan.destroy', $item) }}" accept-charset="UTF-8">
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

                {{-- Rekap Kegiatan --}}
                @if ($kegiatans->isEmpty() AND !$jenis->isEmpty())
                <div class="hpanel">
                    <div class="panel-heading">
                        Rekap Kegiatan MGA
                    </div>
                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.administratif.mga.kegiatan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Kegiatan</a>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif (!$kegiatans->isEmpty())
                <div class="hpanel">
                    <div class="panel-heading">
                        Rekap Kegiatan MGA - per Tahun Anggaran
                    </div>

                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.administratif.mga.kegiatan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Kegiatan</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-kegiatan" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bidang</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tahun Anggaran</th>
                                        <th>Target Jumlah</th>
                                        <th>Jumlah Realisasi</th>
                                        <th>Target Anggaran</th>
                                        @role('Super Admin|Kortim MGA')
                                        <th>Kortim</th>
                                        <th width="20%">Action</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatans as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->jenis_kegiatan->bidang->code }}</td>
                                        <td>{{ $item->jenis_kegiatan->nama }}</td>
                                        <td>{{ $item->tahun }}</td>
                                        <td>{{ $item->target_jumlah }}</td>
                                        <td>
                                            {{ $item->detail_kegiatan_count }} ({{ number_format((float)$item->detail_kegiatan_count/$item->target_jumlah*100, 2, '.', '') }}%) <a href="{{ route('chambers.administratif.mga.kegiatan.show', $item) }}" class="btn btn-primary btn-xs">Detail</a></td>
                                        <td>{{ $item->target_anggaran ? 'Rp. '.number_format($item->target_anggaran,0,',','.') : '-'}}</td>
                                        @role('Super Admin|Kortim MGA')
                                        <td>{{ $item->kortim->name }}</td>
                                        <td>
                                            <a href="{{ route('chambers.administratif.mga.detail-kegiatan.create', ['id' => $item->id ]) }}" class="btn btn-sm btn-info btn-outline" type="button"><i class="fa fa-plus"></i></a>

                                            <a href="{{ route('chambers.administratif.mga.kegiatan.edit', $item) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.administratif.mga.kegiatan.destroy', $item) }}" accept-charset="UTF-8">
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