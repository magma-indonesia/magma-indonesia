@extends('layouts.default')

@section('title')
    Edit Kesimpulan
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
                    Edit data Kesimpulan untuk Gunung Api 
                </h2>
                <small>Form edit data kesimpulan. </small>
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
                    Form Edit Kesimpulan
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.v1.gunungapi.form-kesimpulan.update', $kesimpulan) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk merubah data kesimpulan.
                                        </p>

                                        @if ($kesimpulan->vars_count > 0)
                                        <div class="alert alert-info m-b-md">
                                            <i class="fa fa-gears"></i> Nama Gunung Api tidak bisa dirubah jika <b>Kesimpulan telah digunakan dalam laporan MAGMA-VAR</b></a>
                                        </div>
                                        @endif
                                        
                                        <div class="alert alert-info m-b-md">
                                            <i class="fa fa-info"></i> Kesimpulan ini telah digunakan pada <b>{{ $kesimpulan->vars_count }}</b> Laporan MAGMA-VAR</b></a>
                                        </div>
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

                                            @if ($kesimpulan->vars_count == 0)
                                            <div class="form-group col-sm-12">
                                                <label>Nama Gunung Api</label>
                                                <select name="ga_code" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($gadds as $gadd)
                                                    <option value="{{ $gadd->ga_code }}" {{ $gadd->ga_code == $kesimpulan->ga_code ? 'selected' : ''}}>{{ $gadd->ga_nama_gapi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif


                                            <div class="form-group col-sm-12">
                                                <label>Tingkat Aktivitas yang digunakan</label>
                                                <select name="level" class="form-control selectpicker" data-live-search="true">
                                                    <option value="1" {{ '1' == $kesimpulan->level ? 'selected' : ''}}>Level I (Normal)</option>
                                                    <option value="2" {{ '2' == $kesimpulan->level ? 'selected' : ''}}>Level II (Waspada)</option>
                                                    <option value="3" {{ '3' == $kesimpulan->level ? 'selected' : ''}}>Level III (Siaga)</option>
                                                    <option value="4" {{ '4' == $kesimpulan->level ? 'selected' : ''}}>Level IV (Awas)</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 1</label>
                                                <textarea name="kesimpulan[]]" class="form-control" rows="3" placeholder="Kesimpulan Pertama wajib diisi">{{ $kesimpulan->kesimpulan_1 }}</textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="hr-line-dashed"></div>
                                                <label>Kesimpulan 2</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional">{{ $kesimpulan->kesimpulan_2 }}</textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 3</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional">{{ $kesimpulan->kesimpulan_3 }}</textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 4</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional">{{ $kesimpulan->kesimpulan_4 }}</textarea>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Kesimpulan 5</label>
                                                <textarea name="kesimpulan[]" class="form-control" rows="2" placeholder="Opsional">{{ $kesimpulan->kesimpulan_5 }}</textarea>
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