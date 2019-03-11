@extends('layouts.default')

@section('title')
    Step 5 - Kegempaan
@endsection

@section('add-vendor-css')
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
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
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.laporan.create.var.gempa') }}">Step 5 - Kegempaan</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru Step 3 - Kegempaan.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                @role('Super Admin')
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent
                @endrole
                <div class="hpanel">
                    <div class="panel-heading">
                        Form MAGMA-VAR data Kegempaan
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.var.gempa')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-default hidden-xs m-b" href="{{ route('chambers.laporan.create.var') }}">Step 1 - <span class="hidden-xs">Data Laporan</span></a>
                                <a class="btn btn-default hidden-xs m-b" href="{{ route('chambers.laporan.select.var.rekomendasi') }}">Step 2 - Rekomendasi</a>
                                <a class="btn btn-default hidden-xs m-b" href="{{ route('chambers.laporan.create.var.visual') }}">Step 3 - Visual</a>
                                <a class="btn btn-default hidden-xs m-b" href="{{ route('chambers.laporan.create.var.klimatologi') }}">Step 4 - Klimatologi</a>
                                <a class="btn btn-primary m-b" href="#">Step 5 - Kegempaan</a>
                            </div>
                            <hr>
                            <div class="tab-content">
                                <div id="step3" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class=" pe-7s-graph2 fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Input Laporan pengamatan Kegempaan MAGMA-VAR</strong>, form ini digunakan untuk input data kegempaan gunung api.
                                                <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-9">
                                            @if ($errors->any())
                                            <div class="row m-b-md">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <p>{!! $error !!}</p>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pembuat</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label>Data Kegempaan</label>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="has_gempa" value="1" type="radio" class="i-checks has-gempa" {{ $gempa['has_gempa'] ? 'checked' : ''}}> Tambahkan Kegempaan </label>
                                                        <label class="checkbox-inline"><input name="has_gempa" value="0" type="radio" class="i-checks has-gempa" {{ $gempa['has_gempa'] ? '' : 'checked'}}> Nihil </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Pilih Jenis Gempa --}}
                                            <div class="pilih-gempa">
                                                <div class="row">
                                                    @foreach($jenisgempa as $key => $values)
                                                    <div class="form-group col-lg-6">
                                                        <label>
                                                            @if($key == 0)
                                                            Pilih Gempa
                                                            @endif
                                                        </label>
                                                        @foreach($values as $key1 => $value)
                                                        <div class="checkbox">
                                                            <label><input name="tipe_gempa[]" value="{{ $value['kode'] }}" type="checkbox" class="i-checks tipe-gempa" {{ isset($gempa['data'][$value['kode']]) ? 'checked' : '' }}> {{ $value['nama'] }} </label>
                                                        </div>
                                                        @endforeach                                                    
                                                    </div>
                                                    @endforeach
                                                </div>

                                                {{-- Input Gempa --}}
                                                @foreach($jenisgempa as $key => $values)
                                                @foreach($values as $key1 => $value)
                                                
                                                @if($value['jenis'] == 'sp')
                                                {{-- Jenis Gempa - SP --}}
                                                <div class="row{{ isset($gempa['data'][$value['kode']]) ? '' : ' hidden' }}" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jenis --}}
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][jenis]" value="{{ $value['jenis'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][nama]" value="{{ $value['nama'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['jumlah'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- SP --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="SP-min" name="data[{{ $value['kode'] }}][spmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['spmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="SP-max" name="data[{{ $value['kode'] }}][spmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['spmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'erupsi')
                                                {{-- Jenis Gempa - Erupsi --}}
                                                <div class="row{{ isset($gempa['data'][$value['kode']]) ? '' : ' hidden' }}" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jenis --}}
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][jenis]" value="{{ $value['jenis'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            <input type="hidden" name="data[{{ $value['kode'] }}][nama]" value="{{ $value['nama'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['jumlah'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Tinggi Letusan --}}
                                                            <label class="m-t-sm">Tinggi Abu Letusan (meter di atas puncak)</label>
                                                            <div class="input-group">
                                                                <input placeholder="Tinggi min" name="data[{{ $value['kode'] }}][tmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=50 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['tmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Tinggi max" name="data[{{ $value['kode'] }}][tmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=50 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['tmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Warna Asap --}}
                                                            <label class="m-t-sm">Warna Abu Letusan</label>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Putih" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Putih',$gempa['data'][$value['kode']]['wasap'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Putih </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Kelabu" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Kelabu',$gempa['data'][$value['kode']]['wasap'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Kelabu </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Cokelat" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Cokelat',$gempa['data'][$value['kode']]['wasap'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Cokelat </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Hitam" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Hitam',$gempa['data'][$value['kode']]['wasap'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Hitam </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'normal')
                                                {{-- Jenis Gempa - Normal --}}
                                                <div class="row{{ isset($gempa['data'][$value['kode']]) ? '' : ' hidden' }}" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        {{-- Jenis --}}
                                                        <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][jenis]" value="{{ $value['jenis'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                        <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][nama]" value="{{ $value['nama'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                        {{-- Jumlah --}}
                                                        <div class="p-sm">
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['jumlah'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'dominan')
                                                {{-- Jenis Gempa - Dominan --}}
                                                <div class="row{{ isset($gempa['data'][$value['kode']]) ? '' : ' hidden' }}" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jenis --}}
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][jenis]" value="{{ $value['jenis'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][nama]" value="{{ $value['nama'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['jumlah'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>   
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm m-b-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Dominan --}}
                                                            <input placeholder="Amplitudo Dominan" name="data[{{ $value['kode'] }}][adom]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['adom'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'luncuran')
                                                {{-- Jenis Gempa - Luncuran --}}
                                                <div class="row{{ isset($gempa['data'][$value['kode']]) ? '' : ' hidden' }}" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jenis --}}
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][jenis]" value="{{ $value['jenis'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][nama]" value="{{ $value['nama'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['jumlah'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Jarak Luncur --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Jarak Luncur min" name="data[{{ $value['kode'] }}][rmin]" class="input-gempa form-control input-{{ $value['kode'] }}" step=10 type="number" value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['rmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Jarak Luncur max" name="data[{{ $value['kode'] }}][rmax]" class="input-gempa form-control input-{{ $value['kode'] }}" step=10 type="number" value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['rmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Arah Luncuran --}}
                                                            <label class="m-t-sm">Arah Luncuran</label>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Utara" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Utara',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Utara </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Timur" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Timur',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Timur </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Tenggara" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Tenggara',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Tenggara </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Selatan" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Selatan',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Selatan </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Barat" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Barat',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Barat </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Barat Daya" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Barat Daya',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Barat Daya </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Barat Laut" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Barat Laut',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Barat Laut </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Timur Laut" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('Timur Laut',$gempa['data'][$value['kode']]['alun'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> Timur Laut </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr style="margin-top:5px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'terasa')
                                                {{-- Jenis Gempa - Terasa --}}
                                                <div class="row{{ isset($gempa['data'][$value['kode']]) ? '' : ' hidden' }}" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jenis --}}
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][jenis]" value="{{ $value['jenis'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            <input type="hidden" class="input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][nama]" value="{{ $value['nama'] }}" {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['jumlah'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>   
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['amax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- SP --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="SP-min" name="data[{{ $value['kode'] }}][spmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['spmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="SP-max" name="data[{{ $value['kode'] }}][spmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['spmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmin'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="{{ isset($gempa['data'][$value['kode']]) ? $gempa['data'][$value['kode']]['dmax'] : '' }}" required {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}>
                                                            </div>
                                                            {{-- Skala Terasa --}}

                                                            <label class="m-t-sm">Skala Gempa</label>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="I" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('I',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI I </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="II" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('II',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI II </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="III" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('III',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI III </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="IV" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('IV',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI IV </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="V" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('V',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI V </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="VI" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('VI',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI VI </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][skala][]" value="VII" type="checkbox" class="i-checks skala input-{{ $value['kode'] }}" {{ (isset($gempa['data'][$value['kode']]) AND in_array('VII',$gempa['data'][$value['kode']]['skala'])) ? 'checked' : '' }} {{ isset($gempa['data'][$value['kode']]) ? '' : 'disabled' }}> MMI VII </label>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            {{-- <label class="m-t-sm">Skala Gempa</label>
                                                            <div class="input-group">
                                                                <select class="input-gempa form-control input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][skala][]">
                                                                    <option value="1" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '1') ? 'selected' : '' }}>I</option>
                                                                    <option value="2" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '2') ? 'selected' : '' }}>II</option>
                                                                    <option value="3" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '3') ? 'selected' : '' }}>III</option>
                                                                    <option value="4" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '4') ? 'selected' : '' }}>IV</option>
                                                                    <option value="5" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '5') ? 'selected' : '' }}>V</option>
                                                                    <option value="6" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '6') ? 'selected' : '' }}>VI</option>
                                                                    <option value="7" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][0] == '7') ? 'selected' : '' }}>VII</option>
                                                                </select>
                                                                <span class="input-group-addon"> hingga </span>
                                                                <select class="input-gempa form-control input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][skala][]">
                                                                    <option value="1" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '1') ? 'selected' : '' }}>I</option>
                                                                    <option value="2" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '2') ? 'selected' : '' }}>II</option>
                                                                    <option value="3" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '3') ? 'selected' : '' }}>III</option>
                                                                    <option value="4" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '4') ? 'selected' : '' }}>IV</option>
                                                                    <option value="5" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '5') ? 'selected' : '' }}>V</option>
                                                                    <option value="6" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '6') ? 'selected' : '' }}>VI</option>
                                                                    <option value="7" {{ (isset($gempa['data'][$value['kode']]) AND $gempa['data'][$value['kode']]['skala'][1] == '7') ? 'selected' : '' }}>VII</option>
                                                                </select>
                                                            </div> --}}
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>
                                                @endif

                                                @endforeach
                                                @endforeach
                                            </div>
                                            
                                            {{-- Button Footer --}}
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <a href="{{ route('chambers.laporan.create.var') }}" type="button" class="btn btn-default">Data Laporan <i class="text-success fa fa-check"></i></a>
                                                <a href="{{ route('chambers.laporan.select.var.rekomendasi') }}" type="button" class="btn btn-default">Rekomendasi <i class="text-success fa fa-check"></i></a>
                                                <a href="{{ route('chambers.laporan.create.var.visual') }}" type="button" class="btn btn-default">Visual <i class="text-success fa fa-check"></i></a>
                                                <a href="{{ route('chambers.laporan.create.var.klimatologi') }}" type="button" class="btn btn-default">Klimatologi <i class="text-success fa fa-check"></i></a>
                                                <button type="submit" class="submit btn btn-primary"> Berikutnya <i class="fa fa-angle-double-right"></i></button>
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
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
@endsection

@section('add-script')
    <script>
        $(document).ready(function() {
            @role('Super Admin')
            $('#json-renderer').jsonViewer(@json(session()->all()), {collapsed: true});
            @endrole
            var $checked = $('input[name="has_gempa"]:checked').val();
                $checked == '1' ? hasGempa() : nihil();

            // function initiate()
            // {
            //     $('.i-checks').not('.i-checks.has-gempa, .i-checks.tipe-gempa').iCheck('disable');
            // }

            function nihil()
            {
                $('.tipe-gempa').iCheck('uncheck');
                $('.input-gempa').prop('disabled',true);
                $('.i-checks').not('.i-checks.has-gempa').iCheck('disable');
                $('.pilih-gempa').addClass('hidden');
                console.log('Kegempaan Nihil');
            }

            function hasGempa()
            {
                $('.i-checks.has-gempa, .i-checks.tipe-gempa').iCheck('enable');
                $('.pilih-gempa').removeClass('hidden');
                console.log('Tambahkan Kegempaan');
            }
            
            // initiate();

            $('.has-gempa').on('ifChecked', function(e) {
                $(this).val() == '1' ? hasGempa() : nihil();
            });

            $('.tipe-gempa').on('ifChecked', function(e) {
                var $codeGempa = $(this).val();
                console.log('Gempa '+$codeGempa+' checked');
                $('div[data-code="gempa-'+$codeGempa+'"]').removeClass('hidden');
                $('.i-checks.input-'+$codeGempa).iCheck('enable');
                $('.input-'+$codeGempa).prop('disabled',false);
            });

            $('.tipe-gempa').on('ifUnchecked', function(e) {
                var $codeGempa = $(this).val();
                console.log('Gempa '+$codeGempa+' unchecked');
                $('div[data-code="gempa-'+$codeGempa+'"]').addClass('hidden');
                $('.i-checks.input-'+$codeGempa).iCheck('disable');
                $('.input-'+$codeGempa).prop('disabled',true);
            });
        });
    </script>
@endsection