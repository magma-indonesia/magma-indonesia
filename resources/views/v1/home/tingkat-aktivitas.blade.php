@extends('layouts.slim')

@section('title')
Tingkat Aktivitas Gunung Api
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">Tingkat Aktivitas</li>
@endsection

@section('page-title')
Tingkat Aktivitas Gunung Api
@endsection

@section('main')
<div class="row row-xs">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-status">
            <div class="media">
                <i class="icon icon-volcano-warning tx-danger"></i>
                <div class="media-body">
                    <h1>{{ $gadds->where('ga_status',4)->count() }}</h1>
                    <p>Level IV (Awas)</p>
                </div><!-- media-body -->
            </div><!-- media -->
        </div><!-- card -->
    </div><!-- col-3 -->
    <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
        <div class="card card-status">
            <div class="media">
                <i class="icon icon-volcano-warning tx-orange"></i>
                <div class="media-body">
                    <h1>{{ $gadds->where('ga_status',3)->count() }}</h1>
                    <p>Level III (Siaga)</p>
                </div><!-- media-body -->
            </div><!-- media -->
        </div><!-- card -->
    </div><!-- col-3 -->
    <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
        <div class="card card-status">
            <div class="media">
                <i class="icon icon-volcano-warning tx-warning"></i>
                <div class="media-body">
                    <h1>{{ $gadds->where('ga_status',2)->count() }}</h1>
                    <p>Level II (Waspada)</p>
                </div><!-- media-body -->
            </div><!-- media -->
        </div><!-- card -->
    </div><!-- col-3 -->
    <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
        <div class="card card-status">
            <div class="media">
                <i class="icon icon-volcano-warning tx-success"></i>
                <div class="media-body">
                    <h1>{{ $gadds->where('ga_status',1)->count() }}</h1>
                    <p>Level I (Normal)</p>
                </div><!-- media-body -->
            </div><!-- media -->
        </div><!-- card -->
    </div><!-- col-3 -->
</div>

<div class="row row-xs mg-t-10">
    <div class="col-lg-12">
        <div class="card card-table">
            <div class="card-header">
                <h6 class="slim-card-title">Daftar Tingkat Aktivitas Gunung Api</h6>
            </div><!-- card-header -->
            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                        <tr class="tx-10">
                            <th class="pd-y-5 wd-30p">Tingkat Aktivitas</th>
                            <th class="pd-y-5">Jumlah</th>
                            <th class="pd-y-5">Gunung Api</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Awas --}}
                        <tr>
                            <td rowspan="{{ $gadds->where('ga_status',4)->count()+1 }}">
                                <a href="" class="tx-inverse tx-14 tx-medium d-block">Level IV (Awas)</a>
                                <span class="tx-11 d-block">Hasil pengamatan visual dan instrumental teramati mengalami peningkatan aktivitas yang semakin nyata atau gunung api mengalami erupsi</span>
                            </td>
                            <td class="tx-12" rowspan="{{ $gadds->where('ga_status',4)->count()+1 }}">
                                {{ $gadds->where('ga_status',4)->count() }}
                            </td>
                            @if($gadds->where('ga_status',4)->count() == 0)
                            <td>
                                Tidak ada gunung api Level IV (Awas)
                            </td>
                            @endif
                        </tr>
                        @if($gadds->where('ga_status',4)->count())
                            @foreach ($gadds->where('ga_status',4)->all() as $key => $gadd)
                            <tr>
                                <td>
                                {{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}} <a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $gadd->var_no]) }}"><i class="fa fa-angle-right mg-r-5"
                                        target="_blank"></i>Lihat laporan</a><br>
                                </td>
                            </tr>
                            @endforeach
                        @endif

                        {{-- Siaga --}}
                        <tr>
                            <td rowspan="{{ $gadds->where('ga_status',3)->count()+1 }}">
                                <a href="" class="tx-inverse tx-14 tx-medium d-block">Level III (Siaga)</a>
                                <span class="tx-11 d-block">Hasil pengamatan visual dan instrumental memperlihatkan peningkatan aktivitas yang semakin nyata atau gunung api mengalami erupsi</span>
                            </td>
                            <td class="tx-12" rowspan="{{ $gadds->where('ga_status',3)->count()+1 }}">
                                {{ $gadds->where('ga_status',3)->count() }}
                            </td>
                            @if($gadds->where('ga_status',3)->count() == 0)
                            <td>
                                Tidak ada gunung api Level III (Siaga)
                            </td>
                            @endif
                        </tr>
                        @if($gadds->where('ga_status',3)->count())
                            @foreach ($gadds->where('ga_status',3)->all() as $key => $gadd)
                            <tr>
                                <td>
                                {{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}} <a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $gadd->var_no]) }}"><i class="fa fa-angle-right mg-r-5"
                                        target="_blank"></i>Lihat laporan</a><br>
                                </td>
                            </tr>
                            @endforeach
                        @endif

                        {{-- Waspada --}}
                        <tr>
                            <td rowspan="{{ $gadds->where('ga_status',2)->count()+1 }}">
                                <a href="" class="tx-inverse tx-14 tx-medium d-block">Level II (Waspada)</a>
                                <span class="tx-11 d-block">Hasil pengamatan visual dan instrumental mulai memperlihatkan peningkatan aktivitas. Pada beberapa gunung api dapat terjadi erupsi</span>
                            </td>
                            <td class="tx-12" rowspan="{{ $gadds->where('ga_status',2)->count()+1 }}">
                                {{ $gadds->where('ga_status',2)->count() }}
                            </td>
                            @if($gadds->where('ga_status',2)->count() == 0)
                            <td>
                                Tidak ada gunung api Level II (Waspada)
                            </td>
                            @endif
                        </tr>
                        @if($gadds->where('ga_status',2)->count())
                            @foreach ($gadds->where('ga_status',2)->all() as $key => $gadd)
                            <tr>
                                <td>
                                {{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}} <a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $gadd->var_no]) }}"><i class="fa fa-angle-right mg-r-5"
                                        target="_blank"></i>Lihat laporan</a><br>
                                </td>
                            </tr>
                            @endforeach
                        @endif

                        {{-- Normal --}}
                        <tr>
                            <td rowspan="{{ $gadds->where('ga_status',1)->count()+1 }}">
                                <a href="" class="tx-inverse tx-14 tx-medium d-block">Level I (Normal)</a>
                                <span class="tx-11 d-block">Hasil pengamatan visual dan instrumental fluktuatif, tetapi tidak memperlihatkan peningkatan aktivitas yang signifikan</span>
                            </td>
                            <td class="tx-12" rowspan="{{ $gadds->where('ga_status',1)->count()+1 }}">
                                {{ $gadds->where('ga_status',1)->count() }}
                            </td>
                            @if($gadds->where('ga_status',1)->count() == 0)
                            <td>
                                Tidak ada gunung api Level II (Waspada)
                            </td>
                            @endif
                        </tr>
                        @if($gadds->where('ga_status',1)->count())
                            @foreach ($gadds->where('ga_status',1)->all() as $key => $gadd)
                            <tr>
                                <td>
                                {{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}} <a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $gadd->var_no]) }}"><i class="fa fa-angle-right mg-r-5"
                                        target="_blank"></i>Lihat laporan</a><br>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div><!-- table-responsive -->
            <div class="card-footer tx-12 pd-y-15 bg-transparent">
                <a href="{{ route('v1.gunungapi.var') }}"><i class="fa fa-angle-down mg-r-5"></i>Lihat Laporan Aktivitas</a>
            </div><!-- card-footer -->
        </div>
    </div>
</div>
@endsection