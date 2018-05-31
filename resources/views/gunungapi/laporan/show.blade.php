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
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
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
            <div class="hpanel">
                <div class="panel-heading hbuilt">
                    <div class="p-xs h4">
                        <small class="pull-right">
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
                        </small>
                        Laporan Aktivitas Gunung Api {{ $var->gunungapi->name }}
                    </div>
                </div>
                <div class="border-top border-left border-right bg-light">
                    <div class="row">
                        <div class="col-sm-2 hidden-xs">
                            <div class="profile-picture">
                                <img alt="logo" class="img-circle p-m" src="{{ route('user.photo',['id' => $var->user->id]) }}">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div class="p-m">
                                <div>
                                    <span class="font-extra-bold">Pengirim: </span>
                                    <a href="#">{{ $var->user->name }}</a>
                                </div>
                                <div>
                                    <span class="font-extra-bold">Pos: </span>
                                    <a href="#">{{ $var->pos->observatory }}</a>
                                </div>
                                <div>
                                    <span class="font-extra-bold">Tanggal: </span>
                                    {{ $var->var_data_date->format('Y-m-d')}}
                                </div>
                                <div>
                                    <span class="font-extra-bold">Periode: </span>
                                    {{ $var->var_perwkt.' Jam, Pukul '.$var->periode }}
                                </div>
                                <div>
                                    <span class="font-extra-bold">Validasi Penanggung Jawab: </span>
                                    {{ $var->pj->nip_id ?? 'Belum divalidasi' }}
                                </div>
                                <div>
                                    <span class="font-extra-bold">Verifikator: </span>
                                    {{ $var->verifikator->nip_id ?? 'Belum diverifikasi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 border-right">
                            <h4>Pengamatan Visual </h4>
                            <p>{!! $visual !!}</p>
                            <h4>Keterangan Visual Lainnya </h4>
                            <p>{{ $var->visual->visual_kawah }}</p>
                        </div>
                        <div class="col-lg-6">
                            <h4>Kegempaan </h4>
                            <p>{{ $gempa }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-bottom border-left border-right bg-white p-m">
                    <p class="m-b-md">
                        <span><i class="fa fa-paperclip"></i> 3 attachments - </span>
                        <a href="#" class="btn btn-default btn-xs">Download all in zip format <i class="fa fa-file-zip-o"></i> </a>
                    </p>

                    <div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="hpanel">
                                    <div class="panel-body file-body">
                                        <i class="fa fa-file-pdf-o text-info"></i>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="#">Document_2016.doc</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="hpanel">
                                    <div class="panel-body file-body">
                                        <i class="fa fa-file-audio-o text-warning"></i>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="#">Audio_2016.doc</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="hpanel">
                                    <div class="panel-body file-body">
                                        <i class="fa fa-file-excel-o text-success"></i>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="#">Sheets_2016.doc</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="panel-footer text-right">
                    <div class="btn-group">
                        <button class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                        <button class="btn btn-default"><i class="fa fa-arrow-right"></i> Forward</button>
                        <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                        <button class="btn btn-default"><i class="fa fa-trash-o"></i> Remove</button>
                    </div>
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