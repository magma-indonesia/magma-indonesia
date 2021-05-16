@extends('layouts.default')

@section('title')
    v1 - Laporan {{ $var->ga_nama_gapi }}
@endsection

@section('add-vendor-css')
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
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
                            <span>Laporan (VAR) - Gunung {{ $var->ga_nama_gapi }}</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Laporan Gunung Api {{ $var->ga_nama_gapi }}
                </h2>
                <small>Tanggal {{ $var->var_data_date->format('Y-m-d') }} periode {{ $var->periode }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
<div class="content animate-panel">
    @role('Super Admin')
    <div class="row">
        <div class="col-lg-12">
            @component('components.v1.json-var')
                @slot('title')
                    For Developer
                @endslot
            @endcomponent
        </div>
    </div>
    @endrole
    <div class="row">
        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="stats-title pull-left">
                        <h3>Data Laporan Gunung Api</h3>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-server fa-4x"></i>
                    </div>
                    <br>
                    <div class="m-t-xl border-top">
                        <h4 class="font-bold">Gunung Api</h4>
                        <p>{{ $var->ga_nama_gapi }}</p>
                        <h4 class="font-bold">Tingkat Aktivitas</h4>
                        <p>
                            @if($var->cu_status == '1')
                            Level I (Normal)
                            @elseif($var->cu_status == '2')
                            Level II (Waspada)
                            @elseif($var->cu_status == '3')
                            Level III (Siaga)
                            @else
                            Level IV (Awas)
                            @endif
                        </p>
                        <h4 class="font-bold">Tanggal Laporan</h4>
                        <p>{{ $var->var_data_date->formatLocalized('%A, %d %B %Y') }}</p>
                        <h4 class="font-bold">Periode Laporan</h4>
                        <p>{{ $var->var_perwkt }} Jam, Pukul {{ $var->periode }}</p>
                        <h4 class="font-bold">Pembuat Laporan</h4>
                        <p>{{ $var->var_nama_pelapor }}</p>
                        <h4 class="font-bold">Laporan Dibuat Pada</h4>
                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $var->var_issued)->formatLocalized('%A, %d %B %Y %H:%M:%S') }} WIB</p>
                        <h4 class="font-bold">Rekomendasi</h4>
                        <p>{!! nl2br($var->var_rekom) !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="stats-title pull-left">
                        <h3>Pengamatan Visual</h3>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-look fa-4x"></i>
                    </div>
                    <br>
                    <div class="m-t-xl border-top">
                        <h4 class="font-bold">Visual dan Meteorologi</h4>
                        <p>{!! $visual !!}</p>
                        @if(!empty($var->var_viskawah))
                        <h4 class="font-bold">Keterangan Visual Lainnya</h4>
                        <p>{{ $var->var_viskawah }}</p>
                        @endif
                        @if(!empty($var->var_viskawah))
                        <h4 class="font-bold">Keterangan Lainnya</h4>
                        <p>{!! nl2br($var->var_ketlain) !!}</p>
                        @endif
                    </div>
                    <div class="border-top m-t-md">
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h4 class="no-margins font-extra-bold text-success">Tinggi Asap</h4>
                                <h5>
                                    @if($var->var_tasap_min == 0)
                                    Tidak teramati
                                    @elseif($var->var_tasap_min == $var->var_tasap)
                                    {{ $var->var_tasap_min }} meter di atas puncak
                                    @else
                                    {{ $var->var_tasap_min }} - {{ $var->var_tasap }} meter di atas puncak
                                    @endif
                                </h5>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="no-margins font-extra-bold text-success">Suhu Udara</h4>
                                <h5>
                                    @if($var->var_suhumin == 0)
                                    Tidak teramati
                                    @elseif($var->var_suhumin == $var->var_suhumax)
                                    {{ $var->var_suhumin }}&deg;C
                                    @else
                                    {{ $var->var_suhumin }} - {{ $var->var_suhumax }}&deg;C
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h4 class="no-margins font-extra-bold text-success">Tekanan Udara</h4>
                                <h5>
                                    @if($var->var_tekananmin == 0)
                                    Tidak teramati
                                    @elseif($var->var_tekananmin == $var->var_tekananmax)
                                    {{ $var->var_tekananmin }} mmHg
                                    @else
                                    {{ $var->var_tekananmin }} - {{ $var->var_tekananmax }} mmHg
                                    @endif
                                </h5>
                            </div>
                            <div class="col-lg-6" style="">
                                <h4 class="no-margins font-extra-bold text-success">Kelembaban</h4>
                                <h5>
                                    @if($var->var_kelembabanmin == 0)
                                    Tidak teramati
                                    @elseif($var->var_kelembabanmin == $var->var_kelembabanmax)
                                    {{ $var->var_kelembabanmin }} %
                                    @else
                                    {{ $var->var_kelembabanmin }} - {{ $var->var_kelembabanmax }} %
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="border-top m-t-md">
                        <img class="img-responsive m-t-md" src="{{ $var->var_image }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="stats-title pull-left">
                        <h3>Pengamatan Kegempaan</h3>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-download fa-4x"></i>
                    </div>
                    <br>
                    <div class="m-t-xl border-top">
                        <h4 class="font-bold">Data Kegempaan</h4>
                        @if(empty($gempa))
                        <p>Kegempaan nihil.</p>
                        @else
                        <ul class="list-group">
                            @foreach ($gempa as $value)
                            <li class="list-group-item">
                                {{ $value}}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="border-top m-t-md">
                        <h4 class="font-bold">Kegempaan 3 Bulan Terakhir</h4>
                        <img class="img-responsive m-t-md" src="https://magma.vsi.esdm.go.id/img/eqhist/{{ $var->ga_code }}.png">
                    </div>
                    <div class="border-top m-t-md">
                        <h4 class="font-bold">Data RSAM</h4>
                        <img class="img-responsive m-t-md" src="https://magma.vsi.esdm.go.id/img/RSAM/{{ $var->ga_code }}.png">
                    </div>
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
    @role('Super Admin')
    <script>
        $(document).ready(function () {
            $('#json-renderer-var').jsonViewer(@json($var), {collapsed: true});
            $('#json-renderer-others').jsonViewer(@json($others), {collapsed: true});
        });
    </script>
    @endrole
@endsection