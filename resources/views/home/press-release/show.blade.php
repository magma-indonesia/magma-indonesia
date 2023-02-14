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
