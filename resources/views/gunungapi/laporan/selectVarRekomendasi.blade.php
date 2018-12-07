@extends('layouts.default')

@section('title')
Step 2 - Rekomendasi
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.laporan.select.var.rekomendasi') }}">Step 2 - Rekomendasi</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Pilih Rekomendasi Gunung Api sesuai dengan tingkat aktivitas terkini.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Form MAGMA-VAR pilih Rekomendasi
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.var.rekomendasi')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-default hidden-xs m-b" href="{{ route('chambers.laporan.create.var') }}">Step 1 - <span class="hidden-xs">Data Laporan</span></a>
                                <a class="btn btn-primary m-b" href="#">Step 2 - Rekomendasi</a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 3 - Visual</a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 4 - Klimatologi</a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 5 - Kegempaan</a>
                            </div>
                            <hr>
                            <div class="tab-content">
                                <div id="step2" class="p-m active">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class="pe-7s-camera fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Input Laporan Visual MAGMA-VAR</strong>, form ini digunakan untuk memilih rekomendasi Gunung Api sesuai dengan Tingkat Aktivitasnya. Pergunakan tatanan Bahasa Indonesia yang baik dan benar.
                                                <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-9">
                                            
                                            @if ($errors->any())
                                            <div class="row m-b-md">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <p>{!! $error !!}</p>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pembuat</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Pilih Rekomendasi</label>
                                                    @foreach ($rekomendasi as $key => $item)
                                                    <div class="hpanel hblue {{ $key != 0 ? 'collapsed' : ''}}">
                                                        <div class="panel-heading hbuilt">
                                                            <div class="panel-tools">
                                                            <a class="showhide-rekomendasi"><i class="fa {{ $key != 0 ? 'fa-chevron-circle-down' : 'fa-chevron-circle-up'}} fa-4x"></i></a>
                                                            </div>
                                                            <div class="p-xs" style="max-width: 50%;">
                                                                <div class="checkbox">
                                                                    <label class="checkbox-inline">
                                                                    <input name="rekomendasi" value="{{ $item->id }}" type="radio" class="i-checks" {{ $key == 0 ? 'checked' : '' }}>
                                                                        Pilih Rekomendasi {{ $key+1 }} {!! $key == 0 ? '<span class="label label-magma">default</span>' : '' !!}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="p-sm">
                                                                <p style="line-height: 1.6;">{!! nl2br($item->rekomendasi) !!}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                    <div class="hpanel hred">
                                                        <div class="panel-heading hbuilt">
                                                            <div class="panel-tools">
                                                                <a class="showhide-rekomendasi"><i class="fa fa-chevron-circle-up fa-4x"></i></a>
                                                            </div>
                                                            <div class="p-xs" style="max-width: 50%;">
                                                                <div class="checkbox">
                                                                    <label class="checkbox-inline">
                                                                    <input name="rekomendasi" value="9999" type="radio" class="i-checks" {{ count($rekomendasi)  ? '' : 'checked'}}>
                                                                        Buat Rekomendasi Baru
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <textarea placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="rekomendasi_text" class="form-control p-m" rows="4"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <a href="{{ route('chambers.laporan.create.var') }}" type="button" class="btn btn-default">Data Laporan <i class="text-success fa fa-check"></i></a>
                                                <button type="submit" class="submit btn btn-primary" href="#"> Berikutnya <i class="fa fa-angle-double-right"></i></button>
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-script')
    <script>
        $(document).ready(function() {
            $('.showhide-rekomendasi').on('click', function (event) {
                event.preventDefault();
                var hpanel = $(this).closest('div.hpanel');
                var icon = $(this).find('i:first');
                var body = hpanel.find('div.panel-body');
                var footer = hpanel.find('div.panel-footer');
                body.slideToggle(300);
                footer.slideToggle(200);

                // Toggle icon from up to down
                icon.toggleClass('fa-chevron-circle-up').toggleClass('fa-chevron-circle-down');
                hpanel.toggleClass('').toggleClass('panel-collapse');
                setTimeout(function () {
                    hpanel.resize();
                    hpanel.find('[id^=map-]').resize();
                }, 50);
            });
        });
    </script>
@endsection