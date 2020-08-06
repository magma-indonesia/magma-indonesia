@extends('layouts.slim')

@section('title')
Glossary
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('v1.edukasi.index') }}">Edukasi</a></li>
<li class="breadcrumb-item active" aria-current="page">Glossary</li>
@endsection

@section('page-title')
Glossary
@endsection