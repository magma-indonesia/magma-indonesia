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
Laporan Aktivitas
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-9">

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
                        <p class="timeline-title"><a href="#">{{ $var->gunungapi }}</a></p>
                        <p class="timeline-author">Dibuat oleh <span class="tx-primary">{{ $var->pelapor }}</span> - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y') }}</p>
                        <div class="card card-blog-split">
                            <div class="row no-gutters">
                                <div class="col-md-5 col-lg-6 col-xl-5">
                                    <figure><img src="{{ $var->foto }}" class="img-fit-cover" alt=""></figure>
                                </div>
                                <div class="col-md-7 col-lg-6 col-xl-7">
                                    @switch($var->status)
                                        @case('1')
                                            <h5><span class="badge badge-success">Level I (Normal)</span></h5>
                                            @break
                                        @case('2')
                                            <h5><span class="badge badge-warning tx-white">Level II (Waspada)</span></h5>
                                            @break
                                        @case('3')
                                            <h5><span class="badge bg-orange tx-white">Level III (Siaga)</span></h5>
                                            @break
                                        @default
                                            <h5><span class="badge badge-danger">Level IV (Awas)</span></h5>
                                            @break
                                    @endswitch
                                    <h5 class="blog-title">Pengamatan Visual</h5>
                                    <p class="blog-text">{{ $var->visual }}</p>
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
    </div>

    <div class="col-lg-3">
        <div class="card card-connection">
            <label class="slim-card-title">Filter Data Laporan</label>
            <p>Cari data laporan gunung api</p>
            <form class="form-layout" role="form" method="GET" action="{{ route('v1.gunungapi.var.search',['q' => 'q']) }}">
                <div class="row mg-b-25">
                    <div class="col-lg-12">
                        <div class="form-group mg-t-10">
                            <label class="form-control-label">Gunung Api: <span class="tx-danger">*</span></label>
                            <select name="code" class="form-control select2-show-search" data-placeholder="Pilih Gunung Api">
                                <option label="Choose one"></option>
                                <option value="GJ">Gak jelas</option>  
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

    $('.select2-show-search').select2({
        minimumResultsForSearch: ''
    });

});
</script>
@endsection