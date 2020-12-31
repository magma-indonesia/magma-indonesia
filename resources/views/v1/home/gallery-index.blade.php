@extends('layouts.slim')

@section('title')
Gallery Photo Gunung Api
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
<link href="{{ asset('slim/lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">Gallery</li>
@endsection

@section('page-title')
Gallery Photo Gunung Api
@endsection

@section('main')
<div class="row">
    <div class="col-lg-8">
        <div class="card pd-20">
            <div class="card-body">
                <div class="mg-b-30">
                    {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
                </div>
                <div class="row">
                    @foreach ($vars as $index => $var)
                    <div class="col-6 col-lg-4 col-md-6 mg-b-10">
                        <div class="card-body bd">
                            <p class="mg-b-0">{{ $var->ga_nama_gapi}}</p>
                            <a href="{{ $var->var_image }}" data-lightbox="file-set"
                                data-title="{{ $var->ga_nama_gapi.'_'.$var->data_date.' '.$var->periode }}">
                                <img class="bd img-fluid" src="{{ $var->var_image }}" alt="" />
                            </a>
                            <small>{{$var->data_date.' '.$var->periode }}</small>
                        </div>
                    </div>
                    @endforeach
                    <hr>
                </div>
                <div class="mg-t-30">
                    {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
                </div>
            </div>
            <hr>
            <p xmlns:dct="http://purl.org/dc/terms/" xmlns:cc="http://creativecommons.org/ns#"><a rel="cc:attributionURL"
            property="dct:title" href="https://magma.esdm.go.id/v1/gunung-api/gallery">MAGMA Indonesia Photo Gallery
            Images</a> by <span property="cc:attributionName">Center for Volcanology and Geological Hazard Mitigation of
            Indonesia</span> is licensed under <a rel="license"
            href="https://creativecommons.org/licenses/by-nc-nd/4.0?ref=chooser-v1" target="_blank"
            rel="license noopener noreferrer" style="display:inline-block;">CC BY-NC-ND 4.0<img
                style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
                src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1"><img
                style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
                src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1"><img
                style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
                src="https://mirrors.creativecommons.org/presskit/icons/nc.svg?ref=chooser-v1"><img
                style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
                src="https://mirrors.creativecommons.org/presskit/icons/nd.svg?ref=chooser-v1"></a></p>
        </div>
    </div>

    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
        <div class="card card-connection">
            <label class="slim-card-title">Filter Data Laporan</label>
            <p>Cari data laporan gunung api</p>
            <form class="form-layout" role="form" method="GET" action="{{ route('v1.gunungapi.gallery.search',['q' => 'q']) }}">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mg-t-10">
                            <label class="form-control-label">Gunung Api: <span class="tx-danger">*</span></label>
                            <select name="code" class="form-control select2-show-search" data-placeholder="Pilih Gunung Api">
                                <option label="Choose one"></option>
                                @foreach ($gadds as $gadd)
                                @if ($loop->first)
                                <option value="{{ $gadd->ga_code }}" selected>{{ $gadd->ga_nama_gapi }}</option>
                                @else
                                <option value="{{ $gadd->ga_code }}">{{ $gadd->ga_nama_gapi }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Pilih Tanggal Awal: <span class="tx-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input id="start" value="{{ now()->subDays(7)->format('Y-m-d') }}" name="start" type="text"
                                    class="form-control fc-datepicker" placeholder="{{ now()->subDays(7)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Pilih Tanggal Akhir: <span class="tx-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input id="end" value="{{ now()->format('Y-m-d') }}" name="end" type="text"
                                    class="form-control fc-datepicker" placeholder="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary btn-block mg-b-10">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card pd-20 mg-t-20">
            <h6 class="slim-card-title">Follow Kami</h6>
            <p>Pilih salah satu akun sosial media kami untuk mendapatkan update terkini tentang bahaya geologi di
                Indonesia.</p>
            <hr>
            <h6 class="slim-card-title">Pusat Vulkanologi dan Mitigasi Bencana Geologi</h6>
            <div class="tx-20">
                <a href="https://www.facebook.com/pvmbg" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
                <a href="https://twitter.com/vulkanologi_mbg" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                <a href="https://www.instagram.com/pvmbg_kesdm/" class="tx-pink mg-r-5"><i
                        class="fa fa-instagram"></i></a>
            </div>
            <hr>
            <h6 class="slim-card-title">Badan Geologi</h6>
            <div class="tx-20">
                <a href="https://www.facebook.com/Badan-Geologi-401815270183848/" class="tx-primary mg-r-5"><i
                        class="fa fa-facebook"></i></a>
                <a href="https://twitter.com/kabargeologi" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                <a href="https://www.instagram.com/kabargeologi/" class="tx-pink mg-r-5"><i
                        class="fa fa-instagram"></i></a>
            </div>
            <hr>
            <h6 class="slim-card-title">Kementerian ESDM</h6>
            <div class="tx-20">
                <a href="https://www.facebook.com/kesdm/" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
                <a href="https://twitter.com/kementerianesdm" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                <a href="https://www.instagram.com/kesdm/" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
<script src="{{ asset('slim/lib/moment/js/moment.js') }}"></script>
<script src="{{ asset('slim/lib/jquery-ui/js/jquery-ui.js') }}"></script>
<script src="{{ asset('slim/lib/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('add-script')
<script>
    $(function(){
    'use strict'

    $('.fc-datepicker').datepicker({
        constrainInput: true,
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true,
        maxDate: 0,
        minDate: new Date(2018, 1 - 1, 1)
    });

    $('#start').datepicker({
        defaultDate: -7
    });

});
</script>
@endsection