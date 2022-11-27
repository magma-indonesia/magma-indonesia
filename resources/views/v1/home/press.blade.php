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
        {{ $presses->links('vendor.pagination.slim-simple') }}
        @foreach ($presses as $press)
        <div class="card card-blog-split mg-b-20">
            <div class="row no-gutters">
                <div class="col-md-5 col-lg-6 col-xl-5">
                    <figure>
                        <img src="{{ $press->fotolink }}" class="img-fit-cover" alt="">
                    </figure>
                </div>
                <div class="col-md-7 col-lg-6 col-xl-7">
                    <h5 class="blog-title"><a href="{{ route('v1.press.show.slug', ['id' => $press->id, 'slug' => $press->slug ]) }}">{{ $press->judul }}</a></h5>
                    <div class="blog-text">
                        {{ \Illuminate\Support\Str::limit(strip_tags($press->deskripsi), 280) }}
                    </div>
                    <span class="blog-date">{{ $press->datetime ? $press->datetime->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' : '' }}</span>
                    <a href="{{ route('v1.press.show.slug', ['id' => $press->id, 'slug' => $press->slug ]) }}" class="card-link mg-t-20"> Read more</a>
                </div>
            </div>
        </div>
        @endforeach
        {{ $presses->links('vendor.pagination.slim-simple') }}
    </div>

    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
        <div class="card pd-20">
            <label class="slim-card-title">Press Release</label>
            <div class="post-group">
                @foreach ($randoms as $press)
                <div class="post-item">
                    <span class="post-date">{{ $press->datetime ? $press->datetime->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB' :  $press->log->formatLocalized('%A, %d %B %Y %H:%M:%S'). ' WIB'}}</span>
                    <p class="post-title"><a href="{{ route('v1.press.show.slug', [$press->id, $press->slug ]) }}">{{ $press->judul }}</a></p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mg-t-20">
            @include('includes.slim-sosmed')
        </div>
    </div>
</div>
@endsection