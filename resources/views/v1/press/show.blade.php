@extends('layouts.default') 

@section('title') 
    Press Release 
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel blog-box blog-article-box">
                <div class="panel-heading">
                    <h4>{{ $press->judul }}</h4>
                    <div class="text-muted small">
                        Dibuat oleh: <span class="font-bold">{{ $press->press_pelapor }}</span>
                        {{ $press->log->formatLocalized('%A, %d %B %Y, %H:%M').' WIB' }}
                    </div>
                </div>
                <div class="panel-image">
                    <img class="img-responsive" src="{{ $press->fotolink}}" style="margin: auto;max-height: 360px;">
                </div>
                <div class="panel-body press-release" style="text-align: justify;">
                    {!! htmlspecialchars_decode($press->deskripsi) !!}
                </div>
                <div class="panel-footer">
                    <span class="pull-right">
                        <i class="fa fa-comments-o"> </i> 22 comments
                    </span>
                    <i class="fa fa-eye"> </i> 142 views
                </div>
            </div>
        </div>
    </div>
</div>
@endsection