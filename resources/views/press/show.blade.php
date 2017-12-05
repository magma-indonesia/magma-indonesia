@extends('layouts.default') 

@section('title') 
    Press Release 
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel blog-article-box">
                <div class="panel-heading">
                    <h4>{{ $press->title }}</h4>
                    <div class="text-muted small">
                        Dibuat oleh: <span class="font-bold">{{ $press->user->name }}</span>
                        {{ $press->created_at }}
                    </div>
                </div>
                <div class="panel-body">
                    {!! $press->body !!}
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