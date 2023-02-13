@extends('layouts.slim')

@section('title')
{{ $pressRelease->judul }}
@endsection

@if ($thumbnail)
@section('thumbnail')
{{ $thumbnail }}
@endsection
@endif
