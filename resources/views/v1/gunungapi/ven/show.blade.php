@extends('layouts.default')

@section('title')
    v1 | Letusan {{ $ven->gunungapi->ga_nama_gapi }}
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
                            <span>Laporan Letusan (VEN) - {{ $ven->gunungapi->ga_nama_gapi }}</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Laporan Letusan Gunung Api (Volcano Eruption Notice)
                </h2>
                <small>Letusan terjadi pada tanggal {{ $ven->erupt_tgl }} pukul {{ $ven->erupt_jam }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel blog-article-box">
                    <div class="panel-body">
                        <div class="profile-picture" style="text-align: left;">
                            <img alt="logo" class="p-m" src="{{ url('/').'/images/volcano.svg' }}" style="width: 180px;">
                        </div>
                        <img class="img-responsive m-b" src="{{ $ven->erupt_pht }}">
                        <h3>
                            <b>Informasi Erupsi G. {{ $ven->gunungapi->ga_nama_gapi }}</b>
                        </h3>
                        <blockquote>
                            <p>{!!$visual !!}</p>
                        </blockquote>
                        @if(!empty($ven->erupt_rek))
                        <h3><b>Rekomendasi</b></h3>
                        <blockquote>
                            <p>{!! nl2br($ven->erupt_rek) !!}</p>
                        </blockquote>
                        @endif
                        @if(!empty($ven->erupt_ket))
                        <h3><b>Keterangan Lainnya</b></h3>
                        <blockquote>
                            <p>{!! nl2br($ven->erupt_ket) !!}</p>
                        </blockquote>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <img style="max-width: 60px;float: left;display: inline-block;" src="{{ url('/') }}/images/logo/esdm.gif">
                        <div style="padding-left: 15px;display: inline-block;">
                            <h7>Kementerian Energi dan Sumber Daya Mineral</h7>
                            <h6 class="font-bold">Badan Geologi</h6>
                            <h5 class="font-extra-bold">Pusat Vulkanologi dan Mitigasi Bencana Geologi</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection