@extends('layouts.slim') 

@section('title')
CCTV Gunung Api {{ $cctv->gunungapi->name }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item">CCTV</li>
<li class="breadcrumb-item active" aria-current="page">{{ $cctv->gunungapi->name }}</li>
@endsection

@section('page-title')
{{ $cctv->gunungapi->name }}
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        <img style="height: auto;width: 100%;" src="{{ $cctv->image }}">
    </div>
</div>
@endsection