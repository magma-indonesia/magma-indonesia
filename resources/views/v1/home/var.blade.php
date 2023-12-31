@extends('layouts.slim')

@section('title')
Laporan Aktivitas Gunung Api
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">Laporan Aktivitas</li>
@endsection

@section('page-title')
Laporan Aktivitas Gunung Api (Volcanic Activity Report)
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-8">

        @if (!$grouped->isEmpty())
        <div class="card pd-30 mg-b-30">

            {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-simple') }}

            <div class="timeline-group mg-t-20 mg-b-20">
                @foreach ($grouped as $date => $grouped_vars)

                @if ($date != now()->format('Y-m-d'))
                <div class="timeline-item timeline-day">
                    <div class="timeline-time"></div>
                    <div class="timeline-body">
                        <p class="timeline-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y').' - '.\Carbon\Carbon::createFromFormat('Y-m-d', $date)->diffForHumans() }}</p>
                     </div>
                </div>
                @else
                <div class="timeline-item timeline-day">
                    <div class="timeline-time">&nbsp;</div>
                    <div class="timeline-body">
                        <p class="timeline-date">Hari ini, {{ now()->formatLocalized('%A, %d %B %Y') }}</p>
                    </div>
                </div>
                @endif

                @foreach ($grouped_vars as $var)
                <div class="timeline-item">
                    <div class="timeline-time">
                        <small>Periode {{ $var->periode }}</small>
                    </div>
                    <div class="timeline-body">
                        <p class="timeline-title"><a href="#">{{ $var->gunungapi }}</a>
                        @switch($var->status)
                            @case('1')
                            <span class="badge badge-success">Level I (Normal)</span>
                            @break
                            @case('2')
                            <span class="badge badge-warning tx-white">Level II (Waspada)</span>
                            @break
                            @case('3')
                            <span class="badge bg-orange tx-white">Level III (Siaga)</span>
                            @break
                            @default
                            <span class="badge badge-danger">Level IV (Awas)</span>
                            @break
                        @endswitch</p>
                        <p class="timeline-author">Dibuat oleh <span class="tx-primary">{{ $var->pelapor }}</span> - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y') }}</p>
                        <div class="card bd-0 bd-b">
                            <div class="row no-gutters">
                                <div class="col-xs-12 col-md-12">
                                    <p>{{ $var->visual }}</p>
                                    <a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $var->id]) }}" class="card-link">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @endforeach
            </div>

            {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-simple') }}

        </div>
        @else
        <div class="alert alert-danger pd-30 mg-b-30" role="alert">
            <strong>Hasil pencarian tidak ditemukan!</strong> Silahkan ulangi dan ganti parameter pencarian Anda.
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card card-connection">
            <label class="slim-card-title">Filter Data Laporan</label>
            <p>Cari data laporan gunung api</p>
            <form class="form-layout" role="form" method="GET" action="{{ route('v1.gunungapi.var.search',['q' => 'q']) }}">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mg-t-10">
                            <label class="form-control-label">Gunung Api: <span class="tx-danger">*</span></label>
                            <select name="code" class="form-control select2-show-search" data-placeholder="Pilih Gunung Api">
                                <option label="Choose one"></option>
                                @foreach ($gadds as $gadd)
                                @if ($loop->first)
                                <option value="{{ $gadd->ga_code }}" selected>{{ $gadd->ga_nama_gapi }}</option>
                                @else
                                <option value="{{ $gadd->ga_code }}">{{ $gadd->ga_nama_gapi }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Pilih Tanggal Awal: <span class="tx-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input id="start" value="{{ now()->subDays(7)->format('Y-m-d') }}" name="start" type="text" class="form-control fc-datepicker" placeholder="{{ now()->subDays(7)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Pilih Tanggal Akhir: <span class="tx-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input id="end" value="{{ now()->format('Y-m-d') }}" name="end" type="text" class="form-control fc-datepicker" placeholder="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary btn-block mg-b-10">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="mg-t-20">
            @include('includes.slim-sosmed')
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('slim/lib/moment/js/moment.js') }}"></script>
<script src="{{ asset('slim/lib/jquery-ui/js/jquery-ui.js') }}"></script>
<script src="{{ asset('slim/lib/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(function(){
    'use strict'

    // Datepicker
    $('.fc-datepicker').datepicker({
        constrainInput: true,
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true,
        maxDate: 0,
        minDate: new Date(2018, 1 - 1, 1)
    });

    $('#start').datepicker({
        defaultDate: -7
    });

});
</script>
@endsection