@extends('layouts.default')

@section('title')
    Detail Kegiatan Bidang MGA
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="content animate-panel normalheader">
        <div class="hpanel">
            <div class="panel-body">   
                <h2 class="font-light m-b-xs p-lg">
                    {{ $kegiatan->tahun.' - '.$kegiatan->jenis_kegiatan->nama }} <small class="font-light"> - Jumlah kegiatan {{ $kegiatan->detail_kegiatan->count()  }}</small>
                </h2>
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
                        Detail Kegiatan
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
                                        <th>Biaya Upah</th>
                                        <th>Biaya Bahan</th>
                                        <th>Biaya Carter</th>
                                        <th>Biaya Bahan Lainnya</th>
                                        <th>Total</th>
                                        <th>Ketua Tim</th>
                                        <th>Proposal</th>
                                        <th>Laporan</th>
                                        @role('Super Admin|Kortim MGA')
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
                                        <td>{{ number_format($detail->biaya_kegiatan->upah,0,',','.') }}</td>
                                        <td>{{ number_format($detail->biaya_kegiatan->bahan,0,',','.') }}</td>
                                        <td>{{ number_format($detail->biaya_kegiatan->carter,0,',','.') }}</td>
                                        <td>{{ number_format($detail->biaya_kegiatan->bahan_lainnya,0,',','.') }}</td>
                                        <td>{{ number_format($detail->biaya_kegiatan->bahan_lainnya+$detail->biaya_kegiatan->upah+$detail->biaya_kegiatan->bahan+$detail->biaya_kegiatan->carter,0,',','.') }}</td>
                                        <td>{{ $detail->ketua->name }}</td>
                                        <td><i class="{{ $detail->proposal ? 'fa fa-check text-success' : 'fa fa fa-close text-danger' }}"></i>{{$detail->proposal}}</td>
                                        <td><i class="{{ $detail->laporan ? 'fa fa-check text-success' : 'fa fa fa-close text-danger' }}"></i>{{$detail->laporan}}</td>
                                        @role('Super Admin|Kortim MGA')
                                        <td>
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