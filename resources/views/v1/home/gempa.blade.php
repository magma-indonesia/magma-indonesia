@extends('layouts.slim') 

@section('title')
Tanggapan Kejadian Gempa Bumi
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item"><a>Gempa Bumi dan Tsunami</a></li>
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
            {{ $gempas->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-simple') }}

            <div class="timeline-group mg-t-20 mg-b-20">
                @foreach ($grouped as $date => $grouped_gempas)

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

                @foreach ($grouped_gempas as $roq)
                <div class="timeline-item">
                    <div class="timeline-time d-none d-md-block">
                        <small>{{ $roq->waktu->format('H:i:s').' WIB' }}</small>
                    </div>
                    <div class="timeline-body">
                        <p class="timeline-title"><a>{{ $roq->judul }}</a></p>
                        <p class="timeline-author">Tanggapan dibuat oleh <a href="#">{{ $roq->pelapor }}</a><span class="visible-md visible-lg">, {{ $roq->waktu->formatLocalized('%d %B %Y').' pukul '.$roq->waktu->format('H:i:s').' WIB' }}</span></p>
                        <div class="card pd-30">
                            <div class="row no-gutters">
                                <div class="col-xs-12 col-md-10">
                                    <label class="slim-card-title">Deskripsi Kejadian:</label>
                                    <p class="blog-text">{{ $roq->pendahuluan }}</p>
                                    <label class="slim-card-title">Rekomendasi:</label>
                                    <p class="blog-text">{!! $roq->rekomendasi !!}</p>
                                    <p class="blog-text">
                                        <span class="badge badge-warning pd-10 mg-t-10">{{ $roq->magnitude }}</span>
                                        @if ($roq->intensitas AND ($roq->intensitas != '-belum ada keterangan-'))
                                        <span class="badge badge-primary pd-10 mg-t-10">{{ $roq->intensitas }}</span>
                                        @endif
                                        <span class="badge badge-primary pd-10 mg-t-10">{{ $roq->tsunami }}</span>
                                    </p>
                                    <p class="blog-text"><a class="mg-t-20" href="{{ URL::signedRoute('v1.gempabumi.roq.show', ['id' => $roq->id]) }}" class="card-link">Lihat Detail</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                    
                @endforeach
            </div>
            
            {{ $gempas->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-simple') }}
        </div>
        @else

        @endif
    </div>

    <div class="col-lg-4">
        <div class="card card-connection">
            <label class="slim-card-title">Filter Data Laporan</label>
            <p>Cari data laporan dan tanggapan kejadian gempa bumi dan tsunami</p>
            <form class="form-layout" role="form" method="GET" action="{{ route('v1.gempabumi.roq.search',['q' => 'q'])}}">
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