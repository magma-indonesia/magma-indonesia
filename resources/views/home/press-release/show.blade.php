@extends('layouts.slim')

@section('title')
    {{ $pressRelease->judul }}
@endsection

@if ($thumbnail)
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
        <div class="card card-recent-messages">
            <div class="card-header">
                <span>Recent Messages</span>
                <a href=""><i class="icon ion-more"></i></a>
            </div><!-- card-header -->
            <div class="list-group list-group-flush">
                <div class="list-group-item">
                    <div class="media">
                        <img src="../img/img1.jpg" alt="">
                        <div class="media-body">
                            <h6>Katherine Lumaad</h6>
                            <p>an hour ago</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                    <p class="msg">The European languages are members of the same family. Their separate existence is
                        a myth...</p>
                </div><!-- list-group-item -->
                <div class="list-group-item">
                    <div class="media">
                        <img src="../img/img2.jpg" alt="">
                        <div class="media-body">
                            <h6>Mary Grace Ceballos</h6>
                            <p>2 hours ago</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                    <p class="msg">The European languages are members of the same family. Their separate existence is
                        a myth...</p>
                </div><!-- list-group-item -->
                <div class="list-group-item">
                    <div class="media">
                        <img src="../img/img4.jpg" alt="">
                        <div class="media-body">
                            <h6>Rowella Sombrio</h6>
                            <p>3 hours ago</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                    <p class="msg">The European languages are members of the same family. Their separate existence is
                        a myth...</p>
                </div><!-- list-group-item -->
            </div><!-- list-group -->
            <div class="card-footer">
                <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Show all messages</a>
            </div><!-- card-footer -->
        </div>
    </div>
</div>
@endsection
