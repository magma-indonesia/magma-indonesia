@extends('layouts.slim')

@section('title')
{{ $glossary->judul }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Edukasi</a></li>
<li class="breadcrumb-item active" aria-current="page">Informasi Publik</li>
@endsection

@section('page-title')
{{ $glossary->judul }}
@endsection

@section('main')
<div class="row">
    <div class="col-lg-9">
        <div class="card pd-20">
            <div class="card-body">
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $glossary->judul }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">Diperbarui pada
                    {{ $glossary->updated_at ? $glossary->updated_at->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' :
                    $glossary->created_at->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB'}}
                </p>
                <div class="mg-t-30 card-text">
                    {!! htmlspecialchars_decode($glossary->deskripsi) !!}
                </div>
                @if (count($glossary->glossary_files))
                <div class="bd pd-10 mg-b-10">
                    <div class="row">
                        @foreach ($glossary->glossary_files as $index => $file)
                        <div class="col-4 col-lg-2 col-md-3 mg-b-10">
                            <a href="{{ $file->url }}" data-lightbox="file-set"
                                data-title="{{ $glossary->judul.'_'.($index+1) }}">
                                <img class="img-fluid" src="{{ $file->thumbnail }}" alt="" />
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
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