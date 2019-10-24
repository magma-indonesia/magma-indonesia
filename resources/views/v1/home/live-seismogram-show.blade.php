@extends('layouts.slim') 

@section('title')
Live Seismogram Gunung Api {{ $live->gunungapi->name }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item">Live Seismogram</li>
<li class="breadcrumb-item active" aria-current="page">{{ $live->gunungapi->name }}</li>
@endsection

@section('page-title')
{{ $live->gunungapi->name }} - {{ $live->seismometer->scnl }}
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        <img style="height: auto;width: 100%;" src="{{ $live->image }}">
    </div>
</div>
@endsection