@extends('layouts.default')

@section('title')
    Import MAGMAv1
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/ladda/dist/ladda-themeless.min.css') }}" />
@endsection

@section('content-body')   
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Selamat Datang di Import Data MAGMA
                </h2>

                <p>
                    Pusat kontrol untuk melakukan 
                    <strong>Import data MAGMA v1 ke MAGMA v2</strong> 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Users
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class=" pe-7s-add-user fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-users">{{ $users }}</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            Jumlah User
                        </h3>
                        <small>Jumlah aktif dan terdaftar</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="users" action="{{ route('chambers.import.users') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Users</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Administrasi
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class="pe-7s-shuffle fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-bidang">{{ $bidang }}</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            Administrasi User
                        </h3>
                        <small>Jumlah yang terdata</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="administrasi" action="{{ route('chambers.import.administrasi') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Administrasi</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Absensi Pegawai
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class="pe-7s-look fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-absensi">{{ $absensi }}</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            Absensi Pegawai
                        </h3>
                        <small>Jumlah Absen</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="absensi" action="{{ route('chambers.import.absensi') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Absensi</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Pengajuan
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class="pe-7s-help1 fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-pengajuan">{{ $pengajuan }}</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            Data Pengajuan
                        </h3>
                        <small>Jumlah Pengajuan</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="pengajuan" action="{{ route('chambers.import.pengajuan') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Pengajuan</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Dasar Gunung Api
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class=" pe-7s-server fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-gadds">{{ $gadds }}</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            Jumlah Gunung Api
                        </h3>
                        <small>Data dasar Gunung Api yang terdaftar</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="gadds" action="{{ route('chambers.import.gadds') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Dasar</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Var
                    </div>
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Data Utama VAR</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-share fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h3 class="m-b-xs jumlah-vars">{{ number_format($vars,0,',','.') }}</h3>
                            <span class="font-bold no-margins">
                                Jumlah data VAR
                            </span>

                            <div class="progress m-t-xs full progress-small">
                                <div style="width: {{ $vars/$varsv1*100 }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ $vars/$varsv1*100 }}" role="progressbar" class=" progress-bar progress-bar-magma">
                                    <span class="sr-only">{{ $vars/$varsv1*100 }}% Complete</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stats-label">Jumlah VAR Magma v1</small>
                                    <h4>{{ number_format($varsv1,0,',','.') }}</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stats-label">% Data</small>
                                    <h4 class="persentase-vars">{{ round($vars/$varsv1*100, 2) }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="vars" action="{{ route('chambers.import.vars') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Vars</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Var Visual
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Visual</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class=" pe-7s-camera fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data VAR Visual
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data visual dari MAGMA-VAR, sekaligus menormalisasi data ke versi yang lebih baru.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-visuals">{{ number_format($visuals,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data terkini</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="visuals" action="{{ route('chambers.import.visuals') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Visual</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Var
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Klimatologi</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-cloud fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data Klimatologi
                            </span>
                            <br/>
                            <small>
                                Import data pengamatan klimatologi dari MAGMA-VAR, sekaligus normalisasi data ke versi yang lebih baru.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-klimatologis">{{ number_format($klimatologis,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data terkini</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="klimatologis" action="{{ route('chambers.import.klimatologi') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Klimatologi</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Gempa Gunung Api
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Jumlah Data Gempa</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-download fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data Gempa Gunung Api
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk import data Gempa Gunung Api dari MAGMA-VAR, sekaligus normalisasi data ke versi yang lebih baru.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-gempa">{{ number_format($gempa,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah seluruh gempa</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="gempa" action="{{ route('chambers.import.gempa') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Gempa Gunung Api</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>    
            <div class="col-lg-3">
                <div class="hpanel stats">
                        <div class="panel-heading">
                            Data Rekomendasi Gunung Api
                        </div>
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Var Rekomendasi</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-ribbon fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-magma jumlah-rekomendasi">{{ $rekomendasi }}</h1>
                            <span class="font-bold no-margins">
                                Rekomendasi Gunung Api
                            </span>
                            <br/>
                            <small>
                                Panel ini memberikan informasi tentang
                                <strong>rekomendasi Gunung Api
                                </strong>, berdasarkan status aktivitasnya.
                            </small>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="rekomendasi" action="{{ route('chambers.import.rekomendasi') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Rekomendasi</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div> 
            <div class="col-lg-3">
                <div class="hpanel stats">
                        <div class="panel-heading">
                            Data VAR Harian
                        </div>
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Var Daily</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-monitor fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-magma jumlah-dailies">{{ $vardailies }}</h1>
                            <span class="font-bold no-margins">
                                Volcanic Activity Report
                            </span>
                            <br/>
                            <small>
                                Panel ini memberikan informasi tentang
                                <strong>update VAR terkini dari MAGMA v1
                                </strong>. Digunakan untuk melakukan updating manual.
                            </small>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="dailies" action="{{ route('chambers.import.dailies') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Harian</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                            Volcanic Eruption Notice (VEN)
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Informasi Letusan</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data Informasi Letusan
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data Informasi Letusan Gunung Api
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-vens">{{ number_format($vens,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Informasi Letusan</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="vens" action="{{ route('chambers.import.vens') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Informasi Letusan</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data VONA
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>VONA</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-plane fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data VONA
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data VONA v1
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-vona">{{ number_format($vona,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data VONA</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="vona" action="{{ route('chambers.import.vona') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data VONA</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data VONA Subscribers
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>VONA Subscribers</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class=" pe-7s-mail fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data VONA Subscribers
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data VONA Subscribers
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-subscribers">{{ number_format($subs,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data Subscribers</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="subscribers" action="{{ route('chambers.import.subscribers') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Subscribers</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data CRS
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data CRS</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-note fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data CRS
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data CRS v1
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-crs">{{ number_format($crs,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data CRS</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="crs" action="{{ route('chambers.import.crs') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data CRS</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Gempa Bumi - Magma ROQ
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data ROQ</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-more fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data ROQ
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data ROQ v1
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-roq">{{ number_format($roq,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data ROQ</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="roq" action="{{ route('chambers.import.roq') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data ROQ</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Gerakan Tanah - Magma Sigertan (QLS)
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Gerakan Tanah</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-world fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data MAGMA Sigertan (QLS)
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data MAGMA-SIGERTAN v1
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma jumlah-sigertan">{{ number_format($sigertan,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-magma"></i> Jumlah data Sigertan</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="sigertan" action="{{ route('chambers.import.sigertan') }}">
                            @csrf
                            <button type="submit" id="form-submit" class="ladda-button btn btn-magma btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Sigertan</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')

    <script src="{{ asset('vendor/ladda/dist/spin.min.js') }}"></script>
    <script src="{{ asset('vendor/ladda/dist/ladda.min.js') }}"></script>
    <script src="{{ asset('vendor/ladda/dist/ladda.jquery.min.js') }}"></script>
    
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            function commafy(num) {
                var $num = num.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1.');
                return $num;
            }

            $('button#form-submit').on('click',function(e) {
                e.preventDefault();

                var l = Ladda.create(this),
                    $button = $(this),
                    $label = $(this).children('.ladda-label');

                l.start();

                var $url = $(this).parent().attr('action'),
                    $data = $(this).parent().serialize(),
                    $import = $(this).parent().attr('data-import');

                $.ajax({
                    url: $url,
                    data: $data,
                    type: 'POST',
                    success: function(data){
                        console.log(data);
                        if (data.success==1){
                            setTimeout(function(){
                                l.stop();
                                $jumlah = data.count;
                                $button.attr('disabled', 'disabled');
                                $label.html(data.message);
                                $('.jumlah-'+$import).html(commafy($jumlah));
                                if ($import=='vars') {
                                    $persentase = $jumlah/{{ $varsv1 }}*100;
                                    $('.persentase-'+$import).html($persentase.toFixed(2)+'%');
                                }
                            },1000)
                        } else {
                            l.stop();
                            $button.removeAttr('disabled');
                        }
                    },
                    error: function(response){
                        console.log('response : '+response);
                        var $errors ={
                            'status': response.status+', '+response.statusText,
                            'exception': response.responseJSON.exception,
                            'file': response.responseJSON.file,
                            'line': response.responseJSON.line,
                            'message': response.responseJSON.message,
                        };
                        console.log($errors);
                        l.stop();
                        $label.html('Error. Coba lagi?');
                    }
                });
                
            });

        });

    </script>
@endsection