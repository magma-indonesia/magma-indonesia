@extends('layouts.slim') 

@section('title')
Laporan Aktivitas Gunung Api
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/select2/css/select2.min.css') }}" rel="stylesheet">    
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
                        <p class="timeline-author">Tanggapan dibuat oleh <a href="#"> {{ $gertan->pelapor }} </a><span class="visible-md visible-lg">, pada {{ $gertan->updated_at->format('H:i:s').' WIB' }}</span></p>
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
                                    <p class="blog-text">{{ $gertan->rekomendasi }}</p>
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
</div>    
@endsection