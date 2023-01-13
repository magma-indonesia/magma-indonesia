@extends('layouts.slim')

@section('title')
{{ $vona->cu_avcode }} | {{ strtoupper($vona->ga_nama_gapi) }} | {{ $vona->issued }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">VONA</li>
<li class="breadcrumb-item active" aria-current="page">{{ $vona->ga_code }} {{ $vona->issued }}</li>
@endsection

@section('page-title')
{{ $vona->ga_code }} {{ $vona->issued }}
@endsection