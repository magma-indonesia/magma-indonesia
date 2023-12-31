@extends('layouts.default')

@section('title')
    Rubah Seismometer
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
                        <span>Rubah Seismoter </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Rubah Seismoter
            </h2>
            <small>Gunakan form ini untuk merubah data Seismoter</small>
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
                    <form action="{{ route('chambers.seismometer.update', $seismometer) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk merubah data Seismoter.
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
                                                    <option value="{{ $gadd->code }}" {{ $seismometer->code == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Lokasi</label>
                                                <input class="form-control" value="{{ old('lokasi')?: $seismometer->lokasi }}" name="lokasi" placeholder="Nama Lokasi" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Station</label>
                                                <input class="form-control" value="{{ old('station')?: $seismometer->station }}" name="station" placeholder="Contoh: TMKS (maks 4 karakter)" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Channel</label>
                                                <input class="form-control" value="{{ old('channel')?: $seismometer->channel }}" name="channel" placeholder="Contoh: EHZ (maks 3 karakter)" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Network</label>
                                                <input class="form-control" value="{{ old('network')?: $seismometer->network }}" name="network" placeholder="VG untuk PVMBG" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Location</label>
                                                <input class="form-control" value="{{ old('location')?: $seismometer->location }}" name="location" placeholder="00 untuk lokasi pertama" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Elevation</label>
                                                <input class="form-control" value="{{ old('elevation')?: $seismometer->elevation }}" name="elevation" placeholder="Optional dalam mdpl">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Longitude</label>
                                                <input class="form-control" value="{{ old('longitude')?: $seismometer->longitude }}" name="longitude" placeholder="Optional - Longitude (Decimal Degree)">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Latitude</label>
                                                <input class="form-control" value="{{ old('latitude')?: $seismometer->latitude }}" name="latitude" placeholder="Optional - Latitude (Decimal Degree)">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Alat Aktif?</label>
                                                <div>
                                                    <label class="checkbox-inline"> 
                                                    <input name="is_active" class="i-checks" type="radio" value="1" id="is_active" {{ $seismometer->is_active ? 'checked' : '' }}> Ya </label> 
                                                    <label class="checkbox-inline">
                                                    <input name="is_active" class="i-checks" type="radio" value="0" id="is_active" {{ $seismometer->is_active ? '' : 'checked' }}> Tidak Aktif </label> 
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Tayangkan Seismogram?</label>
                                                <div>
                                                    <label class="checkbox-inline"> 
                                                    <input name="published" class="i-checks" type="radio" value="1" id="published" {{ $seismometer->published ? 'checked' : '' }}> Aktif </label> 
                                                    <label class="checkbox-inline">
                                                    <input name="published" class="i-checks" type="radio" value="0" id="published" {{ $seismometer->published ? '' : 'checked' }}> Tidak Aktif </label> 
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