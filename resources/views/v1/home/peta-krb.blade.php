@extends('layouts.slim')

@section('title')
Peta Kawasan Rawan Bencana (KRB) Gunung Api
@endsection

@section('description')
Download Peta Kawasan Rawan Bencana (KRB) Gunung Api
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">Peta KRB Gunung Api</li>
@endsection

@section('page-title')
Peta Kawasan Rawan Bencana (KRB) Gunung Api
@endsection

@section('main')
<div class="card card-table">
    <div class="table-responsive">
        <table class="table mg-b-0 tx-13">
            <thead>
                <tr class="tx-10">
                    <th class="wd-10p pd-y-5">Nama Gunung Api</th>
                    <th class="pd-y-5">Preview</th>
                    <th class="pd-y-5">Tahun Publish</th>
                    <th class="pd-y-5">Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gadds as $gadd)
                    @foreach ($gadd->peta_krbs as $key => $krb)
                    <tr>
                        <td class=""> {{ $gadd->name }} </td>
                        <td class="">
                            <a href="{{ $krb->medium_url }}" data-lightbox="file-set-{{ $gadd->code }}-{{ $key }}" data-title="{{ $krb->gunungapi->name.'_'.($key+1) }}"><img class="img-fluid" src="{{ $krb->thumbnail }}" alt="" /></a>
                        </td>
                        <td class="valign-middle">{{ $krb->tahun }}</td>
                        <td class="valign-middle">
                            <a href="{{ $krb->url }}" class="btn btn-primary mg-b-10" type="button" download="Original">Original ({{ $krb->size_mb }})</a>
                            <a href="{{ $krb->large_url }}" class="btn btn-primary mg-b-10" type="button" download="Large">Medium ({{ $krb->medium_size_mb }})</a>
                            <a href="{{ $krb->medium_url }}" class="btn btn-primary mg-b-10" type="button" download="Medium">Large ({{ $krb->large_size_mb }})</a>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div><!-- table-responsive -->
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
@endsection
