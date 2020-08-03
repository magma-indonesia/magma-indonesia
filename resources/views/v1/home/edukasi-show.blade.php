@extends('layouts.slim')

@section('title')
{{ $edukasi->judul }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Edukasi</a></li>
<li class="breadcrumb-item active" aria-current="page">Informasi Publik</li>
@endsection

@section('page-title')
{{ $edukasi->judul }}
@endsection

@section('main')
<div class="row">
    <div class="col-lg-9">
        <div class="card pd-20">
            <div class="card-body">
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $edukasi->judul }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">Diperbarui pada 
                    {{ $edukasi->updated_at ? $edukasi->updated_at->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' :  $edukasi->created_at->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB'}}
                </p>
                @if (count($edukasi->edukasi_files))
                <img class="img-fluid" src="{{ $edukasi->edukasi_files->first()->url }}" alt="Image">
                @endif
                <div class="mg-t-30 card-text">
                    {!! htmlspecialchars_decode($edukasi->deskripsi) !!}
                </div>
            </div>
        </div>

        @if (count($edukasi->edukasi_files))
        <div class="card pd-20 mg-t-20">
            <label class="slim-card-title">Download File(s)</label>
            <div class="post-group">
                <div class="row">
                    @foreach ($edukasi->edukasi_files as $index => $file)
                    <div class="col-lg-2 col-md-3 col-sm-6 col-6 mg-t-20">
                        <div class="card bd-0">
                            <img class="img-fluid" src="{{ $file->thumbnail }}" alt="Image">
                            <div class="card-body bd bd-t-0">
                                <a href="{{ $file->url }}" target="_blank" download="{{ $edukasi->judul.'_'.($index+1) }}">File {{ $index+1 }}</a>
                            </div>
                        </div><!-- card -->
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
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