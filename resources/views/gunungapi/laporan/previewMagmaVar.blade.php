@extends('layouts.default')

@section('title')
    Draft {{ $var->noticenumber }}
@endsection

@section('add-vendor-css')
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
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
                            <a href="{{ route('chambers.laporan.preview.magma.var') }}">Preview</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                Preview Laporan Magma VAR - {{ $var->noticenumber }}
                </h2>
                <small>Preview Laporan tanggal {{ $var->var_data_date }} periode {{ $var->periode }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                @role('Super Admin')
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent
                @endrole
                <div class="hpanel">
                    <div class="panel-heading">
                        Preview Laporan MAGMA-VAR
                    </div>
                    <div class="panel-body">
                        <div class="text-center m-b-md" id="wizardControl">
                            <a class="btn btn-primary m-b" href="#">Preview Laporan</a>
                        </div>
                        <hr>
                        <div class="tab-content">
                            <div id="step1" clas="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-3 text-center">
                                        <i class="pe-7s-browser fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Preview Laporan MAGMA</strong>, perhatikan data-data yang telah dimasukkan ke dalam MAGMA. Pastikan data yang diinput benar terutama <b>Tingkat Aktivitas</b> dan <b>Rekomendasi.</b>
                                            <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                        </p>
                                    </div>
                                    
                                    <div class="col-lg-9">
                                        <hr class="hidden-lg hidden-md">
                                        
                                        {{-- Data Laporan Gunung Api --}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="hpanel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-8 col-xs-8">
                                                                <div class="stats-title pull-left">
                                                                    <h3>Data Laporan Gunung Api</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-xs-4">
                                                                <div class="stats-icon pull-right">
                                                                    <i class="pe-7s-server fa-4x"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-top">
                                                            <h4 class="font-bold">Gunung Api</h4>
                                                            <p class="no-margins">{{ $var->gunung_api->name }} - {{ $var->gunung_api->elevation }} mdpl</p>
                                                            <p>{{ $var->gunung_api->district }}, {{ $var->gunung_api->province }}</p>
                                                            <h4 class="font-bold">Tingkat Aktivitas</h4>
                                                            <h5>
                                                            @if($var->status == '1') 
                                                            <span class="label label-normal">Level I (Normal)</span>
                                                            @elseif($var->status == '2')
                                                            <span class="label label-waspada">Level II (Waspada)</span>
                                                            @elseif($var->status == '3')
                                                            <span class="label label-siaga">Level III (Siaga)</span>
                                                            @else
                                                            <span class="label label-awas">Level IV (Awas)</span>
                                                            @endif
                                                            </h5>
                                                            <h4 class="font-bold">Tanggal Laporan</h4>
                                                            <p>{{ $var->var_data_date }}</p>
                                                            <h4 class="font-bold">Periode Laporan</h4>
                                                            <p>{{ $var->var_perwkt }} Jam, Pukul {{ $var->periode }}</p>
                                                            <h4 class="font-bold">Pembuat Laporan</h4>
                                                            <img alt="logo" class="img-circle m-b m-t-md" src="{{ auth()->user()->photo ? '/images/user/photo/'.auth()->user()->id : Storage::url('thumb/user.png') }}">
                                                            <p>{{ auth()->user()->name }}</p>
                                                        </div>
                                                        <div class="m-t-md border-top">
                                                            <a href="{{ route('chambers.laporan.create.var') }}" type="button" class="m-t-md btn w-xs btn-magma"><i class="fa fa-paste"></i> Edit Laporan</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Pengamatan Visual dan Klimatologi --}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="hpanel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-8 col-xs-8">
                                                                <div class="stats-title pull-left">
                                                                    <h3>Visual dan Meteorologi</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-xs-4">
                                                                <div class="stats-icon pull-right">
                                                                    <i class="pe-7s-look fa-4x"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-top">
                                                            <img class="img-responsive m-t-md" src="{{ '/images/var/'.$var->noticenumber.'/draft' }}">
                                                        </div>
                                                        <div class="m-t-md border-top">
                                                            <h4 class="font-bold">Visual</h4>
                                                            <p>{{ $visual }}</p>
                                                            @if(!empty($var_visual->visual_kawah))
                                                            <h4 class="font-bold">Keterangan Visual Lainnya</h4>
                                                            <p>{{ $var_visual->visual_kawah }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="m-t-md">
                                                            <a href="{{ route('chambers.laporan.create.var.visual') }}" type="button" class="btn w-xs btn-magma"><i class="fa fa-paste"></i> Edit Visual</a>
                                                        </div>
                                                        <div class="border-top m-t-md">
                                                            <div class="row m-t-md">
                                                                <div class="col-lg-6">
                                                                    <h4 class="no-margins font-extra-bold text-success">Tinggi Asap</h4>
                                                                    <h5>
                                                                        @if($var_visual->tasap_min == 0)
                                                                        Tidak teramati
                                                                        @elseif($var_visual->tasap_min == $var_visual->tasap_max)
                                                                        {{ $var_visual->tasap_min }} meter di atas puncak
                                                                        @else
                                                                        {{ $var_visual->tasap_min }} - {{ $var_visual->tasap_max }} meter di atas puncak
                                                                        @endif
                                                                    </h5>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <h4 class="no-margins font-extra-bold text-success">Suhu Udara</h4>
                                                                    <h5>
                                                                        @if($var_klimatologi->suhu_min == 0)
                                                                        Tidak teramati
                                                                        @elseif($var_klimatologi->suhu_min == $var_klimatologi->suhu_max)
                                                                        {{ $var_klimatologi->suhu_min }}&deg;C
                                                                        @else
                                                                        {{ $var_klimatologi->suhu_min }} - {{ $var_klimatologi->suhu_max }}&deg;C
                                                                        @endif
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="row m-t-md">
                                                                <div class="col-lg-6">
                                                                    <h4 class="no-margins font-extra-bold text-success">Tekanan Udara</h4>
                                                                    <h5>
                                                                        @if($var_klimatologi->tekanan_min == 0)
                                                                        Tidak teramati
                                                                        @elseif($var_klimatologi->tekanan_min == $var_klimatologi->tekanan_max)
                                                                        {{ $var_klimatologi->tekanan_min }} mmHg
                                                                        @else
                                                                        {{ $var_klimatologi->tekanan_min }} - {{ $var_klimatologi->tekanan_max }} mmHg
                                                                        @endif
                                                                    </h5>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <h4 class="no-margins font-extra-bold text-success">Kelembaban</h4>
                                                                    <h5>
                                                                        @if($var_klimatologi->kelembaban_min == 0)
                                                                        Tidak teramati
                                                                        @elseif($var_klimatologi->kelembaban_min == $var_klimatologi->kelembaban_max)
                                                                        {{ $var_klimatologi->kelembaban_min }} %
                                                                        @else
                                                                        {{ $var_klimatologi->kelembaban_min }} - {{ $var_klimatologi->kelembaban_max }} %
                                                                        @endif
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="m-t-md border-top">
                                                                <a href="{{ route('chambers.laporan.create.var.klimatologi') }}" type="button" class="m-t-md btn w-xs btn-magma"><i class="fa fa-paste"></i> Edit Klimatologi</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Kegempaan --}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="hpanel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-8 col-xs-8">
                                                                <div class="stats-title pull-left">
                                                                    <h3>Data Kegempaan</h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-xs-4">
                                                                <div class="stats-icon pull-right">
                                                                    <i class="pe-7s-graph3 fa-4x"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-top">
                                                            <div class="list-item-container">
                                                            @foreach ($var_gempa->tipe_gempa as $code)
                                                                <div class="list-item m-b border-top">
                                                                    <h4 class="text-color3">{{ $var_gempa->data[$code]['nama'] }}</h4>
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Jumlah Gempa</p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['jumlah'] }}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Amplitudo</p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['amin'] }}-{{ $var_gempa->data[$code]['amax'] }} mm</div>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Amplitudo Dominan --}}
                                                                    @isset($var_gempa->data[$code]['adom'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Amplitudo Dominan</p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['adom'] }} mm</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- SP --}}
                                                                    @isset($var_gempa->data[$code]['spmin'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>SP</p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['spmin'] }}-{{ $var_gempa->data[$code]['spmax'] }} detik</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- Durasi --}}
                                                                    @isset($var_gempa->data[$code]['dmin'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Durasi Gempa</p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['dmin'] }}-{{ $var_gempa->data[$code]['dmax'] }} detik</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- Jarak Guguran --}}
                                                                    @isset($var_gempa->data[$code]['rmin'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Jarak Guguran</p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['rmin'] }}-{{ $var_gempa->data[$code]['rmax'] }} meter</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- Arah Luncuran --}}
                                                                    @isset($var_gempa->data[$code]['alun'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Arah Guguran</p>
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <div class="font-bold">{{ \Illuminate\Support\Str::replaceLast(', ',', dan ',implode(', ',$var_gempa->data[$code]['alun'])) }}</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- Tinggi Letusan --}}
                                                                    @isset($var_gempa->data[$code]['tmin'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Tinggi Letusan</p>
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['tmin'] }}-{{ $var_gempa->data[$code]['tmax'] }} meter di atas puncak</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- Warna Asap --}}
                                                                    @isset($var_gempa->data[$code]['wasap'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Warna Abu</p>
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <div class="font-bold">{{ \Illuminate\Support\Str::replaceLast(', ',', dan ',implode(', ',$var_gempa->data[$code]['wasap'])) }}</div>
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                    {{-- Skala --}}
                                                                    @isset($var_gempa->data[$code]['skala'])
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <p>Skala MMI</p>
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            @if($var_gempa->data[$code]['skala'][0] != $var_gempa->data[$code]['skala'][1])
                                                                            <div class="font-bold">{{ \Illuminate\Support\Str::replaceLast(', ',' - ',implode(', ',$var_gempa->data[$code]['skala'])) }}</div>
                                                                            @else
                                                                            <div class="font-bold">{{ $var_gempa->data[$code]['skala'][0] }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @endisset

                                                                </div>
                                                            @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="m-t-md border-top">
                                                            <a href="{{ route('chambers.laporan.create.var.gempa') }}" type="button" class="m-t-md btn w-xs btn-magma"><i class="fa fa-paste"></i> Edit Kegempaan</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Button Footer --}}
                                        <hr>
                                        <div class="text-left m-t-xs">
                                            <a href="{{ route('chambers.laporan.create.var') }}" type="button" class="btn btn-default">Data Laporan <i class="text-success fa fa-check"></i></a>
                                            <a href="{{ route('chambers.laporan.select.var.rekomendasi') }}" type="button" class="btn btn-default">Rekomendasi <i class="text-success fa fa-check"></i></a>
                                            <a href="{{ route('chambers.laporan.create.var.visual') }}" type="button" class="btn btn-default">Visual <i class="text-success fa fa-check"></i></a>
                                            <a href="{{ route('chambers.laporan.create.var.klimatologi') }}" type="button" class="btn btn-default">Klimatologi <i class="text-success fa fa-check"></i></a>
                                            <a href="{{ route('chambers.laporan.create.var.gempa') }}" type="button" class="btn btn-default">Kegempaan <i class="text-success fa fa-check"></i></a>
                                            <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.magma.var')}}" enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" class="submit btn btn-primary" href="#"> Submit <i class="fa fa-angle-double-right"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            @role('Super Admin')
            $('#json-renderer').jsonViewer(@json(session()->all()), {collapsed: true});
            @endrole
        });
    </script>
@endsection