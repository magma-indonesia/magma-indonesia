@extends('layouts.default')

@section('title')
    Tambah Seismometer
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
                    <li>
                        <span>Seismoter </span>
                    </li>
                    <li class="active">
                        <span>Tambah Seismoter </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Tambahkan Seismoter
            </h2>
            <small>Gunakan form ini untuk menambahkan data Seismoter yang terbaru</small>
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
                    Form Seismoter
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.seismometer.store') }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan data Seismoter baru.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

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

                                        <div class="row p-md">
                                            <div class="form-group col-sm-12">
                                                <label>Gunung Api</label>
                                                <select id="code" class="form-control" name="code">
                                                    @foreach($gadds as $gadd)
                                                    <option value="{{ $gadd->code }}" {{ old('code') == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Lokasi</label>
                                                <input class="form-control" value="{{ old('lokasi') }}" name="lokasi" placeholder="Nama Lokasi" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Station</label>
                                                <input class="form-control" value="{{ old('station') }}" name="station" placeholder="Contoh: TMKS (maks 4 karakter)" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Channel</label>
                                                <input class="form-control" value="{{ old('channel') }}" name="channel" placeholder="Contoh: EHZ (maks 3 karakter)" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Network</label>
                                                <input class="form-control" value="{{ old('network')?: 'VG' }}" name="network" placeholder="VG untuk PVMBG" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Location</label>
                                                <input class="form-control" value="{{ old('location')?: '00' }}" name="location" placeholder="00 untuk lokasi pertama" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Elevation</label>
                                                <input class="form-control" value="{{ old('elevation') }}" name="elevation" placeholder="Optional dalam mdpl">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Longitude</label>
                                                <input class="form-control" value="{{ old('longitude') }}" name="longitude" placeholder="Optional - Longitude (Decimal Degree)">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Latitude</label>
                                                <input class="form-control" value="{{ old('latitude') }}" name="latitude" placeholder="Optional - Latitude (Decimal Degree)">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Alat Aktif?</label>
                                                <div>
                                                    <label class="checkbox-inline"> 
                                                    <input name="is_active" class="i-checks" type="radio" value="1" id="is_active" checked> Ya </label> 
                                                    <label class="checkbox-inline">
                                                    <input name="is_active" class="i-checks" type="radio" value="0" id="is_active"> Tidak Aktif </label> 
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Publikasi Publik</label>
                                                <div>
                                                    <label class="checkbox-inline"> 
                                                    <input name="published" class="i-checks" type="radio" value="1" id="published"> Aktif </label> 
                                                    <label class="checkbox-inline">
                                                    <input name="published" class="i-checks" type="radio" value="0" id="published" checked> Tidak Aktif </label> 
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <div class="hr-line-dashed"></div>
                                                <button class="btn btn-sm btn-primary m-t-n-xs" type="submit">Submit</button>
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