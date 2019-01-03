@extends('layouts.default')

@section('title')
    Draft {{ $var['noticenumber'] }}
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.laporan.preview.magma.var') }}">Preview</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                Preview Laporan Magma VAR - {{ $var['noticenumber'] }}
                </h2>
                <small>Preview Laporan tanggal {{ $var['var_data_date'] }} periode {{ $var['periode'] }}</small>
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
                        Preview Laporan MAGMA-VAR
                    </div>
                    <div class="panel-body">
                        <div class="text-center m-b-md" id="wizardControl">
                            <a class="btn btn-primary m-b" href="#">Preview Laporan</a>
                        </div>
                        <hr>
                        <div class="tab-content">
                            <div id="step1" clas="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-3 text-center">
                                        <i class="pe-7s-browser fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Preview Laporan MAGMA</strong>, perhatikan data-data yang telah dimasukkan ke dalam MAGMA. Pastikan data yang diinput benar terutama <b>Tingkat Aktivitas</b> dan <b>Rekomendasi.</b>
                                            <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                        </p>
                                    </div>
                                    <div class="col-lg-9">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection