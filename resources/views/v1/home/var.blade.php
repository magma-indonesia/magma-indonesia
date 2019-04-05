@extends('layouts.slim') 

@section('title')
Laporan Aktivitas Gunung Api
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

        <div class="card pd-30">

            {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}

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

            {{ $vars->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}

        </div>
    </div>
</div>
@endsection