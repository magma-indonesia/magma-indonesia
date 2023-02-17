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
    <div class="col-lg-10">
        <div class="card card-blog">
            <img class="img-fit-cover" src="{{ $cover }}" alt="Image" style="height: 300px">
            <div class="card-body">
                <p class="blog-category">Finance</p>
                <h5 class="blog-title"><a href="">Your Finances Don't Have to Be Perfect to Work</a></h5>
                <p class="blog-text">It is a long established fact that a reader will be distracted by the readable
                    content of a page when looking at its layout.</p>
                <p class="blog-links">
                    <a href="">12 Likes</a>
                    <a href="">23 Comments</a>
                    <a href=""><i class="icon ion-more"></i></a>
                </p>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@endsection
