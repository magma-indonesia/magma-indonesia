@extends('layouts.slim') 

@section('title') 
{{ $roq->laporan->title }}
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Gempa Bumi</a></li>
<li class="breadcrumb-item active" aria-current="page">Tanggapan Kejadian</li>
@endsection
 
@section('page-title')
Tanggapan Kejadian
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-8">
        <div class="card pd-30 mg-b-30">
            <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $roq->laporan->title }}</h5>
            <p class="card-subtitle tx-normal mg-b-15">This is the card subtitle</p>
        </div>
    </div>

    <div class="col-lg-4">
        @include('includes.slim-sosmed')
    </div>

</div>
@endsection