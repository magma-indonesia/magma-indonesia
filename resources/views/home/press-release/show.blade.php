@extends('layouts.slim')

@section('title')
    {{ $pressRelease->judul }}
@endsection

@if (!is_null($thumbnail))
    @section('thumbnail')
        {{ $thumbnail }}
    @endsection
@endif

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('press-release.index') }}">Press Release</a>
    </li>
@endsection

@section('page-title')
    Press Release
@endsection

@section('main')
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card card-blog pd-20">
                <img class="img-fit-cover pd-20" src="{{ $cover }}" alt="{{ $pressRelease->judul }}">
                <div class="card-body" style="border: 0">
                    <p class="blog-category">{{ $pressRelease->datetime->formatLocalized('%A, %d %B %Y') }}</p>
                    <h5 class="blog-title tx-medium">
                        <p class="tx-black tx-normal tx-20">{{ $pressRelease->judul }}</p>
                    </h5>
                    <div class="tx-20">
                        <a href="https://www.facebook.com/pvmbg_" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
                        <a href="https://twitter.com/pvmbg_" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                        <a href="https://github.com/magma-indonesia" class="tx-inverse mg-r-5"><i class="fa fa-github"></i></a>
                        <a href="https://www.instagram.com/pvmbg_" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
                    </div>
                    <hr>
                    <div class="mg-b-10">

                        @foreach ($pressRelease->tags as $tag)
                            <a href="{{ route('press-release.index', ['tag' => $tag->slug]) }}" class="badge badge-pill badge-danger tx-14" target="_blank">{{ $tag->name }}</a>
                        @endforeach

                        @if ($pressRelease->gunung_api)
                            <a href="#" class="badge badge-pill badge-primary tx-14">{{ "Gunung Api {$pressRelease->gunungApi->name}" }}</a>
                        @endif

                        @if ($pressRelease->gerakan_tanah)
                            <a href="#" class="badge badge-pill badge-warning tx-14">Gerakan Tanah</a>
                        @endif

                        @if ($pressRelease->gempa_bumi)
                            <a href="#" class="badge badge-pill badge-warning tx-14">Gempa Bumi</a>
                        @endif

                        @if ($pressRelease->tsunami)
                            <a href="#" class="badge badge-pill badge-warning tx-14">Tsunami</a>
                        @endif

                        @if ($pressRelease->lainnya)
                            <a href="#" class="badge badge-pill badge-warning tx-14">{{ $pressRelease->lainnya }}</a>
                        @endif

                    </div>

                    <p class="blog-text lh-8" style="text-align: justify">{!! $pressRelease->deskripsi !!}</p>
                </div>
                <div class="card-footer bd-t">
                    <div class="tx-20">
                        <a href="https://www.facebook.com/pvmbg_" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
                        <a href="https://twitter.com/pvmbg_" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                        <a href="https://github.com/magma-indonesia" class="tx-inverse mg-r-5"><i class="fa fa-github"></i></a>
                        <a href="https://www.instagram.com/pvmbg_" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
