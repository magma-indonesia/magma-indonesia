@extends('layouts.default')

@section('title')
    Aktivasi Gunung Api
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
                        <span>Form Pendahuluan </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Buat data Pendahuluan untuk Bencana Geologi Harian 
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
                    Form Aktivasi Gunung Api
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.bencana-geologi.store') }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk mengaktifkan gunung api.
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
                                                <select name="code" class="form-control">
                                                    <option value="{{ $gadd->code }}">{{ $gadd->name }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Urutan dalam Pelaporan</label>
                                                <input name="urutan" class="form-control" value="0" placeholder="Masukkan urutan pelaporan" type="number">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Pilih Pendahuluan</label>
                                                @foreach ($pendahuluans as $key => $pendahuluan)
                                                <div class="hpanel">
                                                    <div class="panel-body">
                                                        <div class="checkbox">
                                                            <label class="checkbox-inline">
                                                            <input name="pendahuluan_id" value="{{ $pendahuluan->id }}" type="radio" class="i-checks" required>
                                                                Pilih Pendahuluan
                                                            </label>
                                                        </div>
                                                        <div class="p-sm">
                                                            <p style="line-height: 1.6;">{!! nl2br($pendahuluan->pendahuluan) !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Aktifkan dalam laporan?</label>
                                                <div>
                                                    <label class="checkbox-inline"> 
                                                    <input name="active" class="i-checks" type="radio" value="1" id="active"> Aktif </label> 
                                                    <label class="checkbox-inline">
                                                    <input name="active" class="i-checks" type="radio" value="0" id="active" checked> Tidak Aktif </label> 
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Aktifkan</button>
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