@extends('layouts.default')

@section('title')
    Edit Pendahuluan
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
                        <span>Gunung Api</span>
                    </li>
                    <li class="active">
                        <span>Rubah Pendahuluan </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Rubah data Pendahuluan untuk Bencana Geologi Harian 
            </h2>
            <small>Form input data pendahuluan. </small>
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
                    Rubah Pendahuluan
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.bencana-geologi-pendahuluan.update', $pendahuluan) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk merubah data pendahuluan baru.
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
                                                <select name="code" class="form-control selectpicker" data-live-search="true">
                                                    <option value="{{ $pendahuluan->gunungapi->code }}">{{ $pendahuluan->gunungapi->name }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Pendahuluan Laporan Gunung Api</label>
                                                <textarea name="pendahuluan" class="form-control" rows="8" placeholder="Wajib diisi">{{ $pendahuluan->pendahuluan }}</textarea>
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