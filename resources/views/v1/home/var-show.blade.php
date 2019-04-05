@extends('layouts.slim') 

@section('title')
Laporan Aktivitas - {{ $var->gunungapi }}
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/chartist/css/chartist.css') }}" rel="stylesheet">
@endsection
    
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Laporan Aktivitas</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}</li>
@endsection

@section('page-title')
Laporan Aktivitas
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-9">

        <div class="card card-blog bd-0">
            <img class="img-fluid" src="{{ $var->foto }}" alt="{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}">
            <div class="card-body pd-30 bd bd-t-0">
                <h5 class="blog-category">
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
                </h5>
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}, periode {{ $var->periode }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">Dibuat oleh,  {{ $var->pelapor }}</p>
            </div>
        </div>

        <div class="card-columns column-count-2 mg-t-20">
                <div class="card card-activities">
                    <h6 class="slim-card-title">Pengamatan Visual</h6>
                    <p>{{ $var->visual }}</p>
                    <h6 class="slim-card-title">Keterangan Visual Lainnya</h6>
                    <p>{{ $var->visual_lainnya }}</p>
                </div>

                <div class="card card-activities">
                    <h6 class="slim-card-title">Pengamatan Kegempaan</h6>
                    <ul class="list-group">
                        @if (empty($var->gempa))
                        Nihil
                        @else
                        @foreach ($var->gempa as $gempa)
                        <li class="list-group-item">
                            <p class="mg-b-0">{{ $gempa }}</p>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>

                <div class="card card-activities">
                    <h6 class="slim-card-title">Rekomendasi</h6>
                    <p>{!! nl2br($var->rekomendasi) !!}</p>
                </div>
        </div>

        {{-- <div class="card mg-t-20">
            <div class="pd-20">
                <div id="chartBar5" class="slim-chartist ht-200 ht-sm-300"></div>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('slim/lib/chartist/js/chartist.js') }}"></script>
@endsection

@section('add-script')
<script>
//   var bar5 = new Chartist.Bar('#chartBar5', {
//     labels: ['Q1', 'Q2', 'Q3', 'Q4'],
//     series: [
//       [0, 1200000, 1400000, 1300000],
//       [0, 400000, 500000, 300000],
//       [0, 200000, 400000, 600000]
//     ]
//   }, {
//     stackBars: true,
//     axisY: {
//       labelInterpolationFnc: function(value) {
//         return (value / 1000) + 'k';
//       }
//     }
//   }).on('draw', function(data) {
//     if(data.type === 'bar') {
//       data.element.attr({
//         style: 'stroke-width: 30px'
//       });
//     }
//   });

</script>
@endsection

