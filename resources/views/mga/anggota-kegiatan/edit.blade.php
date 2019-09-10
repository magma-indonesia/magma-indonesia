@extends('layouts.default')

@section('title')
    Edit Data Tim
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('add-css')
<style>
    /* For Firefox */
    input[type='number'] {
        -moz-appearance:textfield;
    }

    /* Webkit browsers like Safari and Chrome */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
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
                            <span>Edit Data Tim </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    {{ $anggotaKegiatan->detail_kegiatan->kegiatan->tahun }} - {{ $anggotaKegiatan->detail_kegiatan->kegiatan->jenis_kegiatan->nama}}
                </h2>
                <small>Periode {{ $anggotaKegiatan->detail_kegiatan->start_date }} hingga {{ $anggotaKegiatan->detail_kegiatan->end_date }}, Ketua Tim : {{ $anggotaKegiatan->detail_kegiatan->ketua->name }}</small>
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
                    Form Edit Data Tim
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.administratif.mga.anggota-kegiatan.update', $anggotaKegiatan) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="tab-content">
                            <div class="p-m active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <h4>{{ $anggotaKegiatan->detail_kegiatan->kegiatan->tahun }} - {{ $anggotaKegiatan->detail_kegiatan->kegiatan->jenis_kegiatan->nama}}</h4>
                                        <h5>Periode {{ $anggotaKegiatan->detail_kegiatan->start_date }} hingga {{ $anggotaKegiatan->detail_kegiatan->end_date }}, Ketua Tim : {{ $anggotaKegiatan->detail_kegiatan->ketua->name }}</h5>
                                        <div class="hr-line-dashed"></div>
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan anggota tim pada kegiatan Tahun {{ $anggotaKegiatan->detail_kegiatan->kegiatan->tahun }} - {{ $anggotaKegiatan->detail_kegiatan->kegiatan->jenis_kegiatan->nama}}.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row">

                                            @if ($errors->any())
                                            <div class="row m-b-md">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <p>{{ $error }}</p>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12" style="display:none;">
                                                <select id="anggota_tim" name="anggota_tim" class="form-control selectpicker" data-live-search="true">
                                                    <option value="{{ $anggotaKegiatan->nip_anggota }}" selected>{{ $anggotaKegiatan->nip_anggota }}</option>
                                                </select>
                                            </div>

                                            <div class="hpanel">
                                                <div class="hpanel">
                                                    <div class="panel-heading">
                                                        <h4>{{ $anggotaKegiatan->user->name }}</h4>
                                                    </div>

                                                    <div class="panel-body">
                                                        <div class="form-group col-sm-12">
                                                            <label>Periode</label>
                                                            <div class="input-group input-daterange">
                                                                <input id="start" type="text" class="form-control" value="{{ $anggotaKegiatan->start_date }}" name="start">
                                                                <div class="input-group-addon"> - </div>
                                                                <input id="end" type="text" class="form-control" value="{{ $anggotaKegiatan->end_date }}" name="end">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-sm-12">
                                                            <label>Uang Harian</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"> Rp. </div>
                                                                <input type="numeric" class="form-control" value="{{ $anggotaKegiatan->uang_harian }}" min="0" name="uang_harian" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-sm-12">
                                                            <label>Penginapan 30%</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"> Rp. </div>
                                                                <input type="numeric" class="form-control" value="{{ $anggotaKegiatan->penginapan_tigapuluh }}" min="0" name="penginapan_tigapuluh" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-sm-12">
                                                            <label>Penginapan 100%</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"> Rp. </div>
                                                                <input type="numeric" class="form-control" value="{{ $anggotaKegiatan->penginapan_penuh }}" min="0" name="penginapan_penuh">
                                                                <div class="input-group-addon"> Jumlah Hari </div>
                                                                <input type="numeric" class="form-control" value="{{ $anggotaKegiatan->jumlah_hari_menginap }}" min="0" name="jumlah_hari">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-sm-12">
                                                            <label>Uang Transport</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"> Rp. </div>
                                                                <input type="numeric" class="form-control" value="{{ $anggotaKegiatan->uang_transport }}" min="0" name="uang_transport" required>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-line-dashed"></div>
                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
