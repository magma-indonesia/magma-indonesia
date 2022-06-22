@extends('layouts.slim')

@section('container', 'container-fluid')

@section('title')
CCTV Gunung Api
@endsection

@section('add-css')
<style>
.card-blog-overlay .card-footer {
	background-color: rgba(0,0,0,0.5);
}

.card-blog-overlay .card-footer {
    padding: 0.75rem 1.25rem;
}
.img-fit-cover {
	height: 300px;
}
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">CCTV</li>
@endsection

@section('page-title')
Kamera Gunung Api
@endsection

@section('main')
{{-- <div class="row row-sm">
    <div class="col-12">
        <div class="card pd-30 mg-b-20">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Ts4bUfsdBEA" title="PVMBG - CCTV"
                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div> --}}

<div class="row row-sm">
    <div class="col-12">
        <div class="card pd-30 mg-b-20">
            <label class="slim-card-title">Daftar CCTV</label>
            <div class="row row-xs">
                <div class="col-xs-12">
                    <a href="{{ route('v1.gunungapi.cctv') }}" type="button" class="btn btn-sm btn-primary mg-b-10">Semua Gunung Api</a>
                    @foreach ($gadds as $gadd => $cctvs)
                    <a href="{{ route('v1.gunungapi.cctv.filter', ['code' => $cctvs->first()->code]) }}" type="button" class="btn btn-sm btn-primary mg-b-10">{{ $gadd }} ({{ $cctvs->count() }})</a>
                    @endforeach
                </div>
            </div>

            <label class="slim-card-title">Regions</label>
            <div class="row row-xs">
                <div class="col-xs-12">
                    <a href="{{ route('v1.gunungapi.cctv') }}" type="button" class="btn btn-sm btn-primary mg-b-10">Semua Region</a>
                    @foreach ($regions as $codeRegion => $region)
                    <a href="{{ route('v1.gunungapi.cctv') }}?region={{ $codeRegion }}" type="button" class="btn btn-sm btn-primary mg-b-10">{{ $region }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($grouped as $group => $cctvs)
@if ($cctvs->isNotEmpty())
<div class="row row-sm">
    <div class="col-12">
        <div class="card pd-30 mg-b-20">
            <label class="slim-card-title">{{ $group }}</label>
            <div class="row row-xs">
                @foreach ($cctvs as $cctv)
                <div class="col-2">
                    <div class="card card-blog-overlay mg-b-10">
                        <img class="img-fit-cover" src="{{ $cctv->image }}" alt="">
                        <div class="card-footer">
                            <small class="mg-r-10"><a href="" class="view" data-uuid="{{ $cctv->uuid }}"
                                    data-url="{{ URL::temporarySignedRoute('v1.gunungapi.cctv.show', now()->addMinutes(rand(7,13)), ['code' => $cctv->gunungapi->code]) }}"
                                    style="cursor: pointer;">View</a></small>
                            <small class="text-right">{{ $cctv->gunungapi->name }} - {{ $cctv->lokasi }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<div class="pd-10">
<p xmlns:dct="http://purl.org/dc/terms/" xmlns:cc="http://creativecommons.org/ns#"><a rel="cc:attributionURL"
        property="dct:title" href="https://magma.esdm.go.id/v1/gunung-api/cctv">MAGMA Indonesia Web Camera/CCTV
        Images</a> by <span property="cc:attributionName">Center for Volcanology and Geological Hazard Mitigation of
        Indonesia</span> is licensed under <a rel="license"
        href="https://creativecommons.org/licenses/by-nc-nd/4.0?ref=chooser-v1" target="_blank"
        rel="license noopener noreferrer" style="display:inline-block;">CC BY-NC-ND 4.0<img
            style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
            src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1"><img
            style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
            src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1"><img
            style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
            src="https://mirrors.creativecommons.org/presskit/icons/nc.svg?ref=chooser-v1"><img
            style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
            src="https://mirrors.creativecommons.org/presskit/icons/nd.svg?ref=chooser-v1"></a></p>
    </div>
</div>

<form class="form-uuid" method="POST" action="#" style="display: none;">
    @csrf
    <input class="uuid" name="uuid" value="">
</form>
@endsection

@section('add-script')
<script>
$(document).ready(function() {

    $('.view').on('click', function(e) {
        e.preventDefault();
        var uuid = $(this).data('uuid');
        var url = $(this).data('url');
        $('.form-uuid').attr('action',url);
        $('.uuid').val(uuid);
        $('form').submit();
    });

});
</script>
@endsection