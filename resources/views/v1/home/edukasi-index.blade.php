@extends('layouts.slim')

@section('title')
Informasi Publik
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Edukasi</a></li>
<li class="breadcrumb-item active" aria-current="page">Informasi Publik</li>
@endsection

@section('page-title')
Informasi Publik
@endsection

@section('main')
<div class="section-wrapper">
    <div class="row">
        <div class="col-12">
            {{ $edukasis->links('vendor.pagination.slim-simple') }}
        </div>
    </div>

    <div class="row">
        @foreach ($edukasis as $edukasi)
        <div class="col-lg-6 col-12 mg-t-20">
            <div class="media media-demo">
                <img src="{{ optional($edukasi->edukasi_files)->first()->thumbnail ?? 'https://via.placeholder.com/1000x667' }}" class="d-flex mg-r-40 wd-150" alt="Image">
                <div class="media-body mg-t-20 mg-sm-t-0">
                    <h5 class="tx-inverse mg-b-20">{{ $edukasi->judul }}</h5>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($edukasi->deskripsi), 100) }}</p>
                    <a href="{{ route('v1.edukasi.show', $edukasi->slug) }}" class="card-link">Detail</a>
                </div><!-- media-body -->
            </div><!-- media -->
        </div><!-- col-4 -->
        @endforeach
    </div><!-- row -->
</div><!-- section-wrapper -->
@endsection