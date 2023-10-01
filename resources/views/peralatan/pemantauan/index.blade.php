@extends('layouts.default')

@section('title')
Peralatan Pemantauan
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
<div class="normalheader content-boxed">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-ribbon fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Peralatan Pemantauan</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li class="active">
                        <span>Peralatan Pemantauan</span>
                    </li>
                </ol>
            </div>

            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed no-top-padding">
    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Peralatan Pemantauan</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $jumlah }}</h1>
                        <p>
                            Jumlah peralatan pemantauan yang telah terdaftar. Gunakan menu <b>Create</b> untuk menambahkan peralatan pemantauan baru.
                        </p>
                        <a href="{{ route('chambers.peralatan.pemantauan.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Gunung Api</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $gunung_api }}</h1>
                        <p> Jumlah peralatan gunung api yang terpasang dan aktif.
                        </p>
                        <a href="{{ route('chambers.peralatan.pemantauan.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            @if (session()->has('message'))
            <div class="alert alert-success">
                <i class="fa fa-check"></i> {{ session()->get('message') }}.
                <a href="{{ session()->get('url') }}" target="_blank">Lihat Press Release</a>
            </div>
            @endif

            <div class="hpanel">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-peralatan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Peralatan</th>
                                    <th>Bidang</th>
                                    <th>Nama dan Kode Stasiun</th>
                                    <th>Jenis Transmisi</th>
                                    <th>Tanggal Instalasi</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Altitude</th>
                                    <th>Lokasi</th>
                                    <th>Aktif?</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection