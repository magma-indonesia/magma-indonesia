@extends('layouts.default')

@section('title')
    Buat Kesimpulan Baru
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>MAGMA v1</span>
                        </li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Form Kesimpulan </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Buat data Kesimpulan untuk Gunung Api 
                </h2>
                <small>Form input data kesimpulan. </small>
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
                    Form Kesimpulan
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.v1.gunungapi.form-kesimpulan.store') }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan data kesimpulan baru.
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
                                                <label>Nama Gunung Api</label>
                                                <select name="ga_code" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($gadds as $gadd)
                                                    <option value="{{ $gadd->ga_code }}">{{ $gadd->ga_nama_gapi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Tingkat Aktivitas yang digunakan</label>
                                                <select name="level" class="form-control selectpicker" data-live-search="true">
                                                    <option value="1">Level I (Normal)</option>
                                                    <option value="2">Level II (Waspada)</option>
                                                    <option value="3">Level III (Siaga)</option>
                                                    <option value="4">Level IV (Awas)</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 1</label>
                                                <textarea name="kesimpulan[]]" class="form-control" rows="3" placeholder="Kesimpulan Pertama wajib diisi"></textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="hr-line-dashed"></div>
                                                <label>Kesimpulan 2</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional"></textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 3</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional"></textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 4</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional"></textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 5</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional"></textarea>
                                            </div>

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

@section('add-vendor-script')
<script src="{{ asset('vendor/bootstrap/bootstrap-select.min.js') }}"></script>
@endsection