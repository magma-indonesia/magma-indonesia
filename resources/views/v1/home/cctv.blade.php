@extends('layouts.slim') 

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
<div class="row row-sm">
    <div class="col-12">
        <div class="card pd-30 mg-b-20">
            <label class="slim-card-title">Daftar CCTV</label>
            <div class="row row-xs">
                <div class="col-xs-12">
                    <a href="{{ route('v1.gunungapi.cctv') }}" type="button" class="btn btn-sm btn-primary mg-b-10">Semua Gunung Api</a>
                    @foreach ($gadds as $gadd)
                    <a href="{{ route('v1.gunungapi.cctv',$gadd->code) }}" type="button" class="btn btn-sm btn-primary mg-b-10">{{ $gadd->name }} ({{ $gadd->cctv_count }})</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-sm">
    <div class="col-12">
        <div class="card-columns column-count-4">

            @foreach ($cctvs as $cctv)
            
            @if ($cctv->image)
            <div class="card card-blog-overlay">
                <img class="img-fit-cover" src="{{ $cctv->image }}" alt="">
                <div class="card-footer">
                    <small class="mg-r-10"><a href="" class="view" data-uuid="{{ $cctv->uuid }}" data-url="{{ URL::temporarySignedRoute('v1.gunungapi.cctv.show', now()->addMinutes(rand(7,13))) }}" style="cursor: pointer;">View</a></small>
                    <small class="text-right">{{ $cctv->gunungapi->name }} - {{ $cctv->lokasi }}</small>
                </div>
            </div>
            @endif

            @endforeach

        </div>  
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