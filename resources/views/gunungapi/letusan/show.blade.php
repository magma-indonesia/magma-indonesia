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
                        <li>
                            <a href="{{ route('chambers.index') }}">Chambers</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.datadasar.index') }}">Gunung Api</a>
                        </li>
                        <li>
                            <span>Laporan Letusan</span>
                        </li>
                        <li class="active">
                            <span>{{ $ven->gunungapi->name }}</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Laporan Letusan Gunung Api {{ $ven->gunungapi->name }}
                </h2>
                <small>Letusan Gunung Api</small>
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
                        Volcano Eruption Notice
                    </div>
                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.letusan.index') }}" class="btn btn-outline btn-block btn-magma" type="button">Daftar Informasi Letusan</a>
                            </div>
                            <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.letusan.create') }}" class="btn btn-outline btn-block btn-magma" type="button">Buat Informasi Letusan</a>
                            </div>
                            <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.letusan.edit',['uuid'=>$ven->uuid]) }}" class="btn btn-outline btn-block btn-magma" type="button">Edit Letusan</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hpanel blog-article-box">
                    <div class="panel-body">
                        <div class="profile-picture" style="text-align: left;">
                            <img alt="logo" class="p-m" src="{{ url('/').'/images/volcano.svg' }}" style="width: 180px;">
                        </div>
                        <h3>
                            <b>Informasi Erupsi G. {{ $ven->gunungapi->name }}</b>
                        </h3>
                        <blockquote>
                            <p>{!!$visual !!}</p>
                        </blockquote>

                        @if(!empty($ven->rekomendasi))
                        <h3><b>Rekomendasi</b></h3>
                        <blockquote>
                            <p>{!! nl2br($ven->rekomendasi) !!}</p>
                        </blockquote>
                        @endif
                        @if(!empty($ven->lainnya))
                        <h3><b>Keterangan Lainnya</b></h3>
                        <blockquote>
                            <p>{!! nl2br($ven->lainnya) !!}</p>
                        </blockquote>
                        @endif
                        @if(!empty($ven->vona->uuid))
                        <a href={{ route('chambers.vona.show',['uuid'=> $ven->vona->uuid]) }} type="button" class="btn btn-outline btn-magma">Draft VONA</a>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <span class="pull-right">
                            <i class="fa fa-user"> </i> {{ $ven->user->name }}
                        </span>
                        <i class="fa fa-eye"> </i> {{ $ven->getViews() }}
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