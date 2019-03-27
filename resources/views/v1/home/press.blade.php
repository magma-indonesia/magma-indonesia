@extends('layouts.slim') 

@section('title')
Press Release
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">Press Release</li>
@endsection

@section('page-title')
Press Release
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-9">
        <div class="tx-center">
        {{ $presses->links('vendor.pagination.slim-simple') }}
        </div>
        @foreach ($presses as $press)
        <div class="card card-blog-split mg-b-20">
            <div class="row no-gutters">
                <div class="col-md-5 col-lg-6 col-xl-5">
                    <figure>
                        <img src="{{ $press->fotolink }}" class="img-fit-cover" alt="">
                    </figure>
                </div>
                <div class="col-md-7 col-lg-6 col-xl-7">
                    <h5 class="blog-title"><a href="#">{{ $press->judul }}</a></h5>
                    <div class="blog-text">
                        {{ \Illuminate\Support\Str::limit(strip_tags($press->deskripsi), 280) }}
                    </div>
                    <span class="blog-date">{{ $press->datetime ? $press->datetime->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' : '' }}</span>
                    <a href="{{ URL::signedRoute('v1.press.show', ['id' => $press->id ]) }}" class="card-link mg-t-20"> Read more</a>
                </div>
            </div>
        </div>
        @endforeach
        {{ $presses->links('vendor.pagination.slim-simple') }}
    </div>

    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
        <div class="card pd-20 mg-t-10">
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
              <a href="https://www.facebook.com/pvmbg" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
              <a href="https://twitter.com/vulkanologi_mbg" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
              <a href="https://www.instagram.com/pvmbg_kesdm/" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
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