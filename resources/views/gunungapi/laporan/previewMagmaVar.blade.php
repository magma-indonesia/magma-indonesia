@extends('layouts.default')

@section('title')
    Draft {{ $var['noticenumber'] }}
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
                Preview Laporan Magma VAR - {{ $var['noticenumber'] }}
                </h2>
                <small>Preview Laporan tanggal {{ $var['var_data_date'] }} periode {{ $var['periode'] }}</small>
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
                                        
                                        {{-- Pengamatan Visual --}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="hpanel">
                                                    <div class="panel-body list">
                                                        <div class="pull-left">
                                                            <h4>Pengamatan Visual</h4>
                                                        </div>
                                                        <div class="stats-icon pull-right">
                                                            <i class="pe-7s-camera fa-4x"></i>
                                                        </div>
                                                        <div class="m-t-xl">
                                                            <span class="font-bold no-margins">
                                                                Data-data Visual
                                                            </span>
                                                            <br/>
                                                            <p>Data-data pengamatan visual gunung api, melingkupi foto visual, asap, letusan dan visual kawah.
                                                            </p>
                                                        </div>
                                                        <div class="row m-t-md">
                                                            <div class="col-lg-12">
                                                                <img class="img-responsive m-t-md" src="images/p2.jpg" alt="">
                                                                <div class="font-bold"><i class="fa pe-7s-camera text-success"></i>Foto Visual </div>
                                                            </div>
                                                        </div>
                                                        <div class="row m-t-md">
                                                            <div class="col-lg-6">
                                                                <h3 class="no-margins font-extra-bold ">120,108</h3>
                                
                                                                <div class="font-bold">38% <i class="fa fa-level-down"></i></div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <h3 class="no-margins font-extra-bold ">450,600</h3>
                                
                                                                <div class="font-bold">28% <i class="fa fa-level-down"></i></div>
                                                            </div>
                                
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer text-right">
                                                        <button class="btn w-xs btn-magma" type="button">Edit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Klimatologi --}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="hpanel">
                                                    <div class="panel-body list">
                                                        <div class="pull-left">
                                                            <h4>Pengamatan Klimatologi</h4>
                                                        </div>
                                                        <div class="stats-icon pull-right">
                                                            <i class="pe-7s-cloud fa-4x"></i>
                                                        </div>
                                                        <div class="m-t-xl">
                                                            <span class="font-bold no-margins">
                                                                Social users
                                                            </span>
                                                            <br/>
                                                            <p>
                                                                Lorem Ipsum is simply dummy text of the printing simply all dummy text. Lorem Ipsum is
                                                                simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.
                                                            </p>
                                                            
                                                        </div>
                                                        <div class="row m-t-md">
                                                            <div class="col-lg-6">
                                                                <h3 class="no-margins font-extra-bold text-success">300,102</h3>
                                
                                                                <div class="font-bold">98% <i class="fa fa-level-up text-success"></i></div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <h3 class="no-margins font-extra-bold text-success">280,200</h3>
                                
                                                                <div class="font-bold">98% <i class="fa fa-level-up text-success"></i></div>
                                                            </div>
                                                        </div>
                                                        <div class="row m-t-md">
                                                            <div class="col-lg-6">
                                                                <h3 class="no-margins font-extra-bold ">120,108</h3>
                                
                                                                <div class="font-bold">38% <i class="fa fa-level-down"></i></div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <h3 class="no-margins font-extra-bold ">450,600</h3>
                                
                                                                <div class="font-bold">28% <i class="fa fa-level-down"></i></div>
                                                            </div>
                                
                                                        </div>
                                                    </div>
                                                    <div class="panel-footer text-right">
                                                        <button class="btn w-xs btn-magma" type="button">Edit</button>
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
            </div>
        </div>
    </div>
@endsection