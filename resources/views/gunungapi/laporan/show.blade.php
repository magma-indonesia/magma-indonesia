@extends('layouts.default')

@section('title')
    {{ $var->gunungapi->name.' | '.$var->noticenumber }}
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
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
                    @switch($var->status)
                        @case(1)
                        <span class="label label-normal">{{ $var->status_deskripsi }}</span>
                        @break
                        @case(2)
                        <span class="label label-waspada">{{ $var->status_deskripsi }}</span>
                        @break
                        @case(3)
                        <span class="label label-siaga">{{ $var->status_deskripsi }}</span>
                        @break
                        @default
                        <span class="label label-awas">{{ $var->status_deskripsi }}</span>
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
                    <i class="fa fa-eye"> </i> {{ $var->getViews() }} views
                </div>
            </div>
            <div class="hpanel">
                <div class="panel-heading hbuilt">
                    <div class="p-xs h4">
                        <small class="pull-right">
                        @switch($var->status)
                            @case(1)
                            <span class="label label-normal">{{ $var->status_deskripsi }}</span>
                            @break
                            @case(2)
                            <span class="label label-waspada">{{ $var->status_deskripsi }}</span>
                            @break
                            @case(3)
                            <span class="label label-siaga">{{ $var->status_deskripsi }}</span>
                            @break
                            @default
                            <span class="label label-awas">{{ $var->status_deskripsi }}</span>
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
                                    <span class="font-extra-bold">Hari/Tanggal: </span>
                                    {{ $var->var_data_date->formatLocalized('%A, %d %B %Y')}}
                                </div>
                                <div>
                                    <span class="font-extra-bold">Periode: </span>
                                    {{ $var->var_perwkt.' Jam, Pukul '.$var->periode }}
                                </div>
                                <div>
                                    <span class="font-extra-bold">Validasi Penanggung Jawab: </span>
                                    @if($var->pj->isEmpty())
                                        Belum Divalidasi
                                    @else
                                        {{ $var->pj->implode('user.name',', ') }}
                                    @endif
                                </div>
                                <div>
                                    <span class="font-extra-bold">Verifikator: </span>
                                    {{ optional($var->verifikator)->user->name ?? 'Belum diverifikasi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Pengamatan Visual </h4>
                            <p>{!! $visual !!}</p>
                            <h4>Keterangan Visual Lainnya </h4>
                            <p>{{ $var->visual->visual_kawah }}</p>
                        </div>
                        <div class="col-lg-6">
                            <h4>Kegempaan </h4>
                            <p>{{ empty($gempa) ? 'Kegempaan nihil.' : $gempa }}</p>
                            <h4>Keterangan Lainnya</h4>
                            <p>{{ optional($var->keterangan)->deskripsi ? $var->keterangan->deskripsi : 'Nihil' }}</p>
                        </div>
                    </div>
                    <div class="row border-bottom">
                        <div class="col-lg-12">
                            <h4>Rekomendasi</h4>
                            <p>{!! nl2br($var->rekomendasi) !!}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <form class="m-t" id="validasi" method="POST" action="{{ route('chambers.laporan.validasi') }}" accept-charset="UTF-8">
                                @csrf
                                <input name="noticenumber" value="{{ $var->noticenumber }}" type="hidden">
                                <button type="submit" id="form-submit" class="btn btn-outline btn-magma"><i class="fa fa-check"></i> Validasi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="border-bottom border-left border-right bg-white p-m">
                    <h4 class="font-bold">Validasi untuk MAGMA v1 </h4>
                    <form id="validasiv1" method="POST" action="{{ route('chambers.laporan.verifikasiv1') }}" accept-charset="UTF-8">
                        @csrf
                        <input name="ga_code" value="{{ $var->code_id }}" type="hidden">
                        <input name="noticenumber" value="{{ substr($var->noticenumber,4) }}" type="hidden">
                        <button type="submit" id="form-submit" class="btn btn-outline btn-danger"><i class="fa fa-check"></i> Validasi Magma v1</button>
                    </form>
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
                                        <i class="fa fa-file-excel-o text-magma"></i>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="#">Sheets_2016.doc</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    
                        <button class="btn btn-outline btn-magma pull-right"><i class="fa fa-arrow-right"></i> Verifikasi Magma v1</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
    <!-- Json Viewer -->
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>

@endsection

@section('add-script')
    <script>

        $(document).ready(function () {
            $('#json-renderer').jsonViewer(@json($var), {collapsed: true});

            $('body').on('submit','#validasi',function (e) {
                e.preventDefault();                

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();
                
                    swal({
                    title: "Verifikasi Data?",
                    text: "Pastikan datanya sudah benar semua",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, lanjut!",
                    cancelButtonText: "Gak jadi deh!",
                    closeOnConfirm: false,
                    closeOnCancel: true },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: $url,
                            data: $data,
                            type: 'POST',
                            success: function(data){
                                console.log(data);
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    setTimeout(function(){
                                        location.reload();
                                    },2000);
                                }
                            },
                            error: function(data){
                                var $errors = {
                                    'status': data.status,
                                    'exception': data.responseJSON.exception,
                                    'file': data.responseJSON.file,
                                    'line': data.responseJSON.line
                                };
                                console.log($errors);
                                console.log($errors);
                                swal("Gagal!", data.responseJSON.exception, "error");
                            }
                        });
                    }
                });

                return false;
            });

            $('body').on('submit','#validasiv1',function (e) {
                e.preventDefault();                

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();
                
                    swal({
                    title: "Verifikasi Data?",
                    text: "Pastikan datanya sudah benar semua",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, lanjut!",
                    cancelButtonText: "Gak jadi deh!",
                    closeOnConfirm: false,
                    closeOnCancel: true },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: $url,
                            data: $data,
                            type: 'POST',
                            success: function(data){
                                console.log(data);
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    setTimeout(function(){
                                        location.reload();
                                    },2000);
                                }
                            },
                            error: function(data){
                                var $errors = {
                                    'status': data.status,
                                    'exception': data.responseJSON.exception,
                                    'file': data.responseJSON.file,
                                    'line': data.responseJSON.line
                                };
                                console.log($errors);
                                console.log($errors);
                                swal("Gagal!", data.responseJSON.exception, "error");
                            }
                        });
                    }
                });

                return false;
            });
        });
    
    </script>
@endsection