@extends('layouts.default')

@section('title')
Rekap Laporan Gunung Api
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
<div class="normalheader">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-ribbon fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Rekap Laporan Gunung Api</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index') }}">Rekap Laporan</a></li>
                    <li class="active"><a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index.gunung-api', ['year' => $selected_year]) }}">Gunung Api ({{ $selected_year }})</a></li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Menu ini digunakan untuk memberikan gambaran rekapitulasi laporan yang dibuat oleh pengamat gunung api
                maupun staff gunung api setiap bulannya. Distribusi sebaran pembuatan laporan dapat dilihat dalam bentuk
                tabel di bawah ini.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>

            @if (session('message'))
            <div class="alert alert-success">
                <i class="fa fa-check"></i> {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content no-top-padding">
    <div class="row">
        <div class="col-lg-4 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Rekap Laporan</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>Tahun {{ $selected_year }}</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh pengamat gunung api. Pilih
                            tahun laporan yang ingin dilihat.
                        </p>
                        @foreach ($years as $year)
                        <a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index', $year->format('Y')) }}"
                            class="btn btn-outline btn-danger m-t-xs">{{
                            $year->format('Y') }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Rekap Laporan</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>Gunung Api</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh pengamat gunung api yang
                            telah <b>dikelompokkan berdasarkan gunung api</b>.
                        </p>

                        @foreach ($years as $year)
                        <a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index.gunung-api', ['year' => $year->format('Y')]) }}"
                            class="btn btn-outline btn-danger m-t-xs {{ $selected_year == $year->format('Y') ? 'active disabled' : ''}}">{{ $year->format('Y') }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Rekap Laporan tahun {{ $selected_year }}
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">

                            @if ($pengamat_only)
                                <a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index.gunung-api', ['year' => $selected_year, 'pengamatOnly' => 'false']) }}"
                                    class="btn btn-outline btn-block btn-magma" type="button">Tampilkan Semua</a>
                            @else
                                <a href="{{ route('chambers.v1.gunungapi.rekap-laporan.index.gunung-api', ['year' => $selected_year, 'pengamatOnly' => 'true']) }}"
                                    class="btn btn-outline btn-block btn-magma" type="button">Tampilkan Hanya Pengamat</a>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-rekap" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Gunung Api (Jumlah Laporan)</th>
                                    <th>Pembuat Laporan (Jumlah)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vars as $var)
                                <tr>
                                    <td class="border-right">
                                        <a href="{{ $var['link_gunungapi'] }}"
                                            style="color: #337ab7; text-decoration: none;">{{ $var['gunungapi'] }} ({{ $var['total_laporan'] }})</a>
                                    </td>

                                    <td>
                                    @foreach ($var['pelapors'] as $pelapor)
                                        <a href="{{ $pelapor['link_user'] }}" style="color: #337ab7; text-decoration: none;">{{ $pelapor['nama'] }} ({{ $pelapor['total_laporan'] }})</a>

                                        @if (!$loop->last)
                                            <span> | </span>
                                        @endif
                                    @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection