@extends('layouts.slim')

@section('title')
Gallery Photo Gunung Api
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
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
    <div class="col-lg-9">
        <div class="card pd-20">
            <div class="card-body">
                <div class="mg-b-30">
                    {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
                </div>
                <div class="row">
                    @foreach ($vars as $index => $var)
                    <div class="col-3 col-lg-3 col-md-3 mg-b-10">
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
        </div>
    </div>

    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
        <div class="card pd-20 mg-t-0">
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
@endsection