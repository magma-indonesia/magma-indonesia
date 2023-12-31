@extends('layouts.slim')

@section('title')
{{ $press->judul }}
@endsection

@section('thumbnail')
{{ $press->fotolink }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Press Release</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $press->id }}</li>
@endsection

@section('page-title')
Press Release
@endsection

@section('main')
<div class="row">
    <div class="col-lg-9">
        <div class="card pd-20">
            <div class="card-body">
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $press->judul }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">{{ $press->datetime ? $press->datetime->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' :  $press->log->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB'}}</p>
                <img class="img-fluid" src="{{ $press->fotolink }}" alt="Image">
                <div class="mg-t-30 card-text">
                    {!! htmlspecialchars_decode($press->deskripsi) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
        <div class="card pd-20 mg-t-0">
            <label class="slim-card-title">Press Release</label>
            <div class="post-group">
                @foreach ($randoms as $press)
                <div class="post-item">
                    <span class="post-date">{{ $press->datetime ? $press->datetime->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' :  $press->log->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB'}}</span>
                    <p class="post-title"><a href="{{ URL::signedRoute('v1.press.show', ['id' => $press->id ]) }}">{{ $press->judul }}</a></p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card pd-20 mg-t-20">
            <h6 class="slim-card-title">Follow Kami</h6>
            <p>Pilih salah satu akun sosial media kami untuk mendapatkan update terkini tentang bahaya geologi di Indonesia.</p>
            <hr>
            <h6 class="slim-card-title">Pusat Vulkanologi dan Mitigasi Bencana Geologi</h6>
            <div class="tx-20">
              <a href="https://www.facebook.com/pvmbg_" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
              <a href="https://twitter.com/pvmbg_" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
              <a href="https://www.instagram.com/pvmbg_" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
            </div>
            <hr>
            <h6 class="slim-card-title">Badan Geologi</h6>
            <div class="tx-20">
              <a href="https://www.facebook.com/Badan-Geologi-401815270183848/" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
              <a href="https://twitter.com/kabargeologi" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
              <a href="https://www.instagram.com/kabargeologi/" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
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