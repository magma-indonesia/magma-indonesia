@extends('layouts.default')

@section('title')
    Letusan {{ $ven->gunungapi->name }}
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chambers</a></li>
                        <li>
                            <span>Gunung Api </span>
                        </li>
                        <li>
                            <span>Laporan Letusan</span>
                        </li>
                        <li class="active">
                            <span>Buat Laporan Letusan</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Buat Laporan Letusan Gunung Api
                </h2>
                <small>Form input letusan Gunung Api</small>
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
                                <img alt="logo" class="p-m" src="{{ url('/').'/img/volcano.svg' }}" style="width: 180px;">
                            </div>
                        <h3><b>Informasi Erupsi G. {{ $ven->gunungapi->name }}</b></h3>
                        <p>Telah terjadi erupsi G. {{ $ven->gunungapi->name }}, {{ $ven->gunungapi->province }} pada {{ $ven->date }}, pukul {{ $ven->time.$ven->gunungapi->zonearea}} dengan tinggi kolom abu teramati &plusmn; {{ $ven->height }} m di atas puncak (&plusmn; {{ $ven->height+$ven->gunungapi->elevation }} m di atas permukaan laut). Kolom abu teramati berwarna {{ count($ven->wasap) > 1 ? strtolower($ven->wasap[0]).' hingga '.strtolower(last($ven->wasap)) : strtolower($ven->wasap[0]) }} dengan intensitas sedang hingga kuat condong ke arah  {{ count($ven->arah_asap) > 1 ? strtolower($ven->arah_asap[0]).' dan '.strtolower(last($ven->arah_asap)) : strtolower($ven->arah_asap[0]) }}. {{ $ven->amplitudo > 0 ? 'Erupsi ini terekam di seismograf dengan amplitudo maksimum '.$ven->amplitudo.' mm dan durasi '.$ven->durasi.' detik.' : ''}}</p>
                        <h3><b>Rekomendasi</b></h3>
                        <p>{!! nl2br($ven->rekomendasi) !!}</p>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-right">
                            <i class="fa fa-user"> </i> {{ $ven->user->name }}
                        </span>
                        <i class="fa fa-eye"> </i> {{ $ven->page_views }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection