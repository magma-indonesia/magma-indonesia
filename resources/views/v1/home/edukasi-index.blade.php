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
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 mg-t-20">
            <div class="card bd-0">
                <img class="img-fluid" src="http://via.placeholder.com/1000x667" alt="Image">
                <div class="card-body bd bd-t-0">
                    <p class="card-text">@json($edukasis)</p>
                </div>
            </div><!-- card -->
        </div><!-- col-4 -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 mg-t-20">
            <div class="card bd-0">
                <img class="img-fluid" src="http://via.placeholder.com/1000x667" alt="Image">
                <div class="card-body bd bd-t-0">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                </div>
            </div><!-- card -->
        </div><!-- col-4 -->
    </div><!-- row -->
</div><!-- section-wrapper -->  
@endsection