@extends('layouts.slim') 

@section('title')
Tanggapan Kejadian Gerakan Tanah
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item"><a>Gerakan Tanah</a></li>
<li class="breadcrumb-item active" aria-current="page">Tanggapan Kejadian</li>
@endsection

@section('page-title')
Tanggapan Kejadian
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-8">
        @if (!$grouped->isEmpty())
        <div class="card pd-30 mg-b-30">
            {{ $gertans->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-simple') }}

            <div class="timeline-group mg-t-20 mg-b-20">
                @foreach ($grouped as $date => $grouped_gertans)

                @if ($date != now()->format('Y-m-d'))
                <div class="timeline-item timeline-day">
                    <div class="timeline-time d-none d-md-block"></div>
                    <div class="timeline-body">
                        <p class="timeline-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y').' - '.\Carbon\Carbon::createFromFormat('Y-m-d', $date)->diffForHumans() }}</p>
                     </div>
                </div>
                @else
                <div class="timeline-item timeline-day">
                    <div class="timeline-time d-none d-md-block">&nbsp;</div>
                    <div class="timeline-body">
                        <p class="timeline-date">Hari ini, {{ now()->formatLocalized('%A, %d %B %Y') }}</p>
                    </div>
                </div>
                @endif

                @foreach ($grouped_gertans as $gertan)
                <div class="timeline-item">
                    <div class="timeline-time d-none d-md-block">
                        <small>{{ $gertan->updated_at->format('H:i:s').' WIB' }}</small>
                    </div>
                    <div class="timeline-body">
                        <p class="timeline-title"><a>{{ $gertan->judul }}</a></p>
                        <p class="timeline-author">Tanggapan dibuat oleh <a href="#">{{ $gertan->pelapor }}</a><span class="visible-md visible-lg">, {{ $gertan->updated_at->formatLocalized('%d %B %Y').' pukul '.$gertan->updated_at->format('H:i:s').' WIB' }}</span></p>
                        <div class="card pd-30">
                            <div class="row no-gutters">
                                <div class="col-xs-12 col-md-10">
                                    <label class="slim-card-title">Lokasi dan Waktu Kejadian:</label>
                                    <p class="blog-text">{{ $gertan->deskripsi }}</p>
                                    @if ($gertan->kerentanan)
                                    <p class="blog-text">{{ $gertan->kerentanan }}</p>
                                    @endif
                                    @if ($gertan->rekomendasi)
                                    <label class="slim-card-title">Rekomendasi:</label>
                                    <p class="blog-text">{!! $gertan->rekomendasi !!}</p>
                                    @endif
                                    <a href="{{ URL::signedRoute('v1.gertan.sigertan.show', ['id' => $gertan->id]) }}" class="card-link">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @endforeach
            </div>
            {{ $gertans->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-simple') }}
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
            <p>Cari data laporan dan tanggapan kejadian gerakan tanah</p>
            <form class="form-layout" role="form" method="GET" action="{{ route('v1.gertan.sigertan.search',['q' => 'q'])}}">
                <div class="row">
                    <div class="col-lg-12">
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