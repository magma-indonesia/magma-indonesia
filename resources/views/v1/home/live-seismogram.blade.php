@extends('layouts.slim') 

@section('title')
Live Seismogram Gunung Api
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
<li class="breadcrumb-item active" aria-current="page">Live Seismogram</li>
@endsection

@section('page-title')
Live Seismogram
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-12">
        <div class="card-columns column-count-3">

            @foreach ($gadds as $gadd)
            @foreach ($gadd->seismometers as $seismometer)

            @if ($seismometer->live_seismogram->image)
            <div class="card card-blog-overlay">
                <img class="img-fit-cover" src="{{ $seismometer->live_seismogram->image }}" alt="">
                <div class="card-footer">
                    <small class="mg-r-10"><a href="" class="view" data-uuid="{{ $seismometer->live_seismogram->id }}" data-url="{{ URL::temporarySignedRoute('v1.gunungapi.live-seismogram.show', now()->addMinutes(rand(10,13))) }}" style="cursor: pointer;">View</a></small>
                    <small class="text-right">{{ $gadd->name }} - {{ $seismometer->scnl }}</small>
                </div>
            </div>
            @endif

            @endforeach
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