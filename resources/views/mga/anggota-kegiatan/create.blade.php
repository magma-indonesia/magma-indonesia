@extends('layouts.default')

@section('title')
    Buat Detail Kegiatan
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
                            <span>Buat Detail Kegiatan </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    {{ $detailKegiatan->kegiatan->tahun }} - {{ $detailKegiatan->kegiatan->jenis_kegiatan->nama}}
                </h2>
                <small>Periode {{ $detailKegiatan->start_date }} hingga {{ $detailKegiatan->end_date }}, Ketua Tim : {{ $detailKegiatan->ketua->name }}</small>
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
                    Form Tambah Anggota Tim
                </div>

                <div class="panel-body">

                    <form action="{{ route('chambers.administratif.mga.anggota-kegiatan.store',['step' => '2', 'id' => $detailKegiatan->id]) }}" method="post">
                        @csrf
                        <div class="text-center m-b-md" id="wizardControl">
                            <a class="btn btn-primary m-b" href="#">Step 1 <span class="hidden-xs">- Pilih Anggota</span></a>
                            <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 2 - Periode</a>
                        </div>
                        <hr>

                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan anggota tim pada kegiatan Tahun {{ $detailKegiatan->kegiatan->tahun }} - {{ $detailKegiatan->kegiatan->jenis_kegiatan->nama}}.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

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

                                            <div class="form-group col-sm-12">
                                                <label>Nama Kegiatan</label>
                                                <select name="kegiatan_id" class="form-control">
                                                    <option value="{{ $detailKegiatan->kegiatan->id }}">{{ '('.$detailKegiatan->kegiatan->jenis_kegiatan->bidang->code.' '.$detailKegiatan->kegiatan->tahun.') ' }} - {{ $detailKegiatan->kegiatan->jenis_kegiatan->nama }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Lokasi</label>
                                                <select class="form-control">
                                                    <option>{{ $detailKegiatan->gunungapi->name ?? $detailKegiatan->lokasi_lainnya }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Anggota Tim</label>
                                                <select id="anggota_tim" name="anggota_tim[]" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    @foreach ($users as $user)
                                                    <option value="{{ $user->nip }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="hr-line-dashed"></div>
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Next</button>
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

@section('add-vendor-script')
<script src="{{ asset('vendor/bootstrap/bootstrap-select.min.js') }}"></script>
@endsection
