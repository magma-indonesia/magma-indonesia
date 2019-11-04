@extends('layouts.default')

@section('title')
    {{ $anggotas->first()->user->name }}
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
                {{ $anggotas->first()->user->name }}                    
            </h2>
            <small class="font-light"> Daftar Program/Kegiatan yang pernah diikuti</small>
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
                    Daftar Kegiatan yang diikuti - {{ $anggotas->count() }} Kegiatan
                </div>

                <div class="panel-body">
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Lokasi</th>
                                    <th>Bidang</th>
                                    <th>Kegiatan</th>
                                    <th>Periode</th>
                                    <th>Uang yang Diterima</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($anggotas as $key => $anggota)
                                <tr>
                                    <td>{{ $anggota->detail_kegiatan->gunungapi->name ?? $anggota->detail_kegiatan->lokasi_lainnya}}</td>
                                    <td>{{ $anggota->kegiatan->jenis_kegiatan->code }}</td>
                                    <td>{{ $anggota->kegiatan->tahun }} | {{ $anggota->kegiatan->jenis_kegiatan->nama }}</td>
                                    <td>{{ $anggota->detail_kegiatan->start_date }} - {{ $anggota->detail_kegiatan->end_date }} ({{ $anggota->detail_kegiatan->jumlah_hari }} hari)</td>
                                    <td>Rp. {{ number_format($anggota->uang_yang_diterima,0,',','.') }}</td>
                                    <td>
                                        <a href="{{ route('chambers.administratif.mga.detail-kegiatan.show', $anggota->detail_kegiatan) }}" class="btn btn-sm btn-info btn-outline" style="margin-right: 3px;">Detail</a>
                                    </td>
                                </tr> 
                                @endforeach
                                <tr>
                                    <td colspan="3"><hr></td>
                                    <td><b>Total</b></td>
                                    <td><b>Rp. {{ number_format($anggotas->sum('uang_yang_diterima'),0,',','.') }}</b></td>
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