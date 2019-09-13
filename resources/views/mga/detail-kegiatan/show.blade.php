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
                <h2 class="font-light">
                    {{ '('.$detailKegiatan->kegiatan->jenis_kegiatan->bidang->code.') '.$detailKegiatan->kegiatan->tahun.' - '.$detailKegiatan->kegiatan->jenis_kegiatan->nama }}
                </h2>
                <p>{{ $detailKegiatan->code_id ? $detailKegiatan->gunungapi->name : $detailKegiatan->lokasi_lainnya }} - {{ $detailKegiatan->tanggal_mulai }} hingga {{ $detailKegiatan->tanggal_akhir }} ({{ $detailKegiatan->jumlah_hari }} hari). Ketua Tim : {{ $detailKegiatan->ketua->name }}</p>
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
                        Rincian Biaya Kegiatan
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive project-list">
                            <table class="table table-striped">
                                <tr>
                                    <th>Bahan-bahan </th>
                                    <td>Rp. {{ number_format($detailKegiatan->biaya_kegiatan->bahan,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th>Upah Tenaga Lepas Harian </th>
                                    <td>Rp. {{ number_format($detailKegiatan->biaya_kegiatan->upah,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sewa Kendaraan </th>
                                    <td>Rp. {{ number_format($detailKegiatan->biaya_kegiatan->carter,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th>Biaya Lainnya </th>
                                    <td>Rp. {{ number_format($detailKegiatan->biaya_kegiatan->bahan_lainnya,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total </th>
                                    <td><b>Rp. {{ number_format($detailKegiatan->biaya_kegiatan->total_biaya,0,',','.') }}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Rincian Biaya Anggota Tim
                    </div>

                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.administratif.mga.anggota-kegiatan.create', ['id' => $detailKegiatan->id]) }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Anggota</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body list">

                        @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                            <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                        </div>
                        @endif

                        <div class="table-responsive project-list">
                            <table class="table table-striped">
                                <thead>
                                    <th>Nama</th>
                                    <th>Lama Perjalanan</th>
                                    <th>Uang Harian</th>
                                    <th>Uang Penginapan</th>
                                    <th>Transport</th>
                                    <th>Jumlah</th>
                                    @role('Super Admin|Kortim MGA')
                                    <th width="20%">Action</th>
                                    @endrole
                                </thead>

                                <tbody>
                                    @foreach ($detailKegiatan->anggota_tim as $anggota_tim)
                                    <tr>
                                        <td>
                                            {{ $anggota_tim->user->name }}<br>
                                            <small>{{ $anggota_tim->user->nip }}</small>
                                        </td>
                                        <td>
                                            {{ $detailKegiatan->tanggal_mulai }} - {{ $detailKegiatan->tanggal_akhir }}<br>
                                            <small><b>{{ $anggota_tim->jumlah_hari }} hari</b></small>
                                        </td>
                                        <td>Rp. <span class="text-right">{{ number_format($anggota_tim->uang_harian_total,0,',','.') }}</span></td>
                                        <td>Rp. <span class="text-right">{{ number_format($anggota_tim->uang_penginapan_total,0,',','.') }}</span></td>
                                        <td>Rp. <span class="text-right">{{ number_format($anggota_tim->uang_transport,0,',','.') }}</span></td>
                                        <td>Rp. <span class="text-right">{{ number_format($anggota_tim->total_biaya,0,',','.') }}</span></td>
                                        @role('Super Admin|Kortim MGA')
                                        <td>
                                            <a href="{{ route('chambers.administratif.mga.anggota-kegiatan.edit', $anggota_tim->id) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.administratif.mga.anggota-kegiatan.destroy', $anggota_tim->id) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                        </td>
                                        @endrole
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td colspan="3"><hr></td>
                                        <td colspan="2" class="text-right"><b>Rp. {{ number_format($detailKegiatan->biaya_tim_total,0,',','.') }}</b></td>
                                    </tr>
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