@extends('layouts.default')

@section('title')
    Laporan | {{ $var->gunungapi->name.' | '.$var->noticenumber }}
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chamber') }}">Chamber</a></li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li>
                            <span>{{ $var->gunungapi->name }}</span>
                        </li>
                        <li class="active">
                            <span>{{ $var->noticenumber }}</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Gunungapi {{ $var->gunungapi->name }}
                </h2>
                Laporan Hari {{ $var->var_data_date->formatLocalized('%A, %d %B %Y') }}
            </div>
        </div>
    </div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel blog-article-box">
                <div class="panel-heading">
                    <h4>Laporan Aktivitas</h4>
                    <h4>Gunung Api {{ $var->gunungapi->name }}</h4>
                    <div class="text-muted">
                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $var->var_data_date)->formatLocalized('%A, %d %B %Y') }}, Periode {{ $var->var_perwkt.', '.$var->periode }} 
                    </div>
                    <div class="text-muted small">
                        Dibuat oleh <span class="font-bold">{{ $var->user->name }}</span>
                    </div>
                    <div class="text-muted">
                    @switch($var->statuses_desc_id)
                        @case(1)
                        <span class="label label-normal">{{ $var->status->status }}</span>
                        @break
                        @case(2)
                        <span class="label label-waspada">{{ $var->status->status }}</span>
                        @break
                        @case(3)
                        <span class="label label-siaga">{{ $var->status->status }}</span>
                        @break
                        @default
                        <span class="label label-awas">{{ $var->status->status }}</span>
                    @endswitch
                    </div>
                </div>
                <div class="panel-body">
                    <pre id="json-renderer" style="padding: 2em;"></pre>
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

@section('add-vendor-script')
    <!-- Json Viewer -->
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {
            $('#json-renderer').jsonViewer(@json($var), {collapsed: true});
        });
    
    </script>
@endsection