@extends('layouts.default')

@section('title')
    Tambah CCTV
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
                        <span>CCTV </span>
                    </li>
                    <li class="active">
                        <span>Tambah CCTV </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Tambahkan CCTV
            </h2>
            <small>Gunakan form ini untuk menambahkan data CCTV yang terbaru</small>
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
                    Form CCTV
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.cctv.store') }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan data CCTV baru.
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
                                                <label>Path CCTV</label>
                                                <input class="form-control" value="{{ old('url') }}" name="url" placeholder="Contoh: /monitoring/CCTV/Sinabung/Latest/cam_1.jpg" required>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Latitude</label>
                                                <input class="form-control" value="{{ old('latitude') }}" name="latitude" placeholder="Latitude (Decimal Degree)">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Longitude</label>
                                                <input class="form-control" value="{{ old('longitude') }}" name="longitude" placeholder="Longitude (Decimal Degree)">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Elevation</label>
                                                <input class="form-control" value="{{ old('elevation') }}" name="elevation" placeholder="Elevation (mdpl)">
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