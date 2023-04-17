@extends('layouts.slim')

@section('title')
Laporan Harian Gunung Api
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Gunung Api</li>
<li class="breadcrumb-item active" aria-current="page">Laporan Harian</li>
@endsection

@section('page-title')
Laporan Harian - {{ $date->formatLocalized('%A, %d %B %Y') }}
@role('Super Admin')
    <span class="badge badge-pill badge-danger">{{ $is_cached ? 'Cached' : 'Not Cached' }}</span>
@endrole
@endsection

@section('main')
<div class="nav-statistics-wrapper mg-b-20">
    <nav class="nav">
        @foreach ($groupedByStatus as $status => $gadds)
            @if ($loop->first)
                <a href="#tab-{{ $loop->index }}" id="home-tab-{{ $loop->index }}" class="nav-link active"
                    data-toggle="tab" role="tab" aria-controls="home-{{ $loop->index }}"
                    aria-selected="true">{{ $status }} - {{ $gadds->count() }}</a>
            @else
                <a href="#tab-{{ $loop->index }}" id="home-tab-{{ $loop->index }}" class="nav-link"
                    data-toggle="tab" role="tab" aria-controls="home-{{ $loop->index }}"
                    aria-selected="true">{{ $status }} - {{ $gadds->count() }}</a>
            @endif
        @endforeach
    </nav>
</div>

<div class="tab-content" id="myTabContent">

    <form id="form-date" method="GET" data-action="{{ route('v1.gunungapi.laporan-harian') }}">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                    <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                    </div>
                </div>
                <input id="date" value="{{ now()->format('Y-m-d') }}" name="date" type="text" class="form-control fc-datepicker" placeholder="{{ now()->format('Y-m-d') }}">
                <button class="btn btn-primary submit-date mg-l-10" type="submit">Lihat</button>
            </div>
        </div>
    </form>

    @foreach ($groupedByStatus as $status => $gadds)
        <div id="tab-{{ $loop->index }}" role="tabpanel" aria-labelledby="home-tab-{{ $loop->index }}"
            class="card card-table mg-b-20 tab-pane {{ $loop->first ? 'show active' : '' }}">
            <div class="card-header">
                <h6 class="slim-card-title">{{ $status }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                        <tr class="tx-10">
                            <th class="pd-y-5">No</th>
                            <th class="wd-10p pd-y-5">Gunung Api</th>
                            <th class="pd-y-5">Visual</th>
                            <th class="pd-y-5">Kegempaan</th>
                            <th class="pd-y-5">Rekomendasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($gadds as $key => $gadd)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $gadd['name'] }}</td>
                                <td>
                                    <p>{!! $gadd['visual']['gunung_api'] !!}</p>
                                    @if (!empty($gadd['visual']['letusan']))
                                        <p>{{ $gadd['visual']['letusan'] }}</p>
                                    @endif
                                    @if ($gadd['visual']['guguran']->isNotEmpty())
                                        @foreach ($gadd['visual']['guguran'] as $guguran)
                                            <p>{{ $guguran }}</p>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($gadd['kegempaan']->isNotEmpty())
                                        <ul>
                                            @foreach ($gadd['kegempaan'] as $gempa)
                                                <li>{{ $gempa }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        Nihil
                                    @endif
                                </td>
                                <td>
                                    @if ($gadd['rekomendasi']->count() > 1)
                                        <ol>
                                            @foreach ($gadd['rekomendasi'] as $rekomendasi)
                                                <li>{{ $rekomendasi }}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        {{ $gadd['rekomendasi']->first() }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('slim/lib/moment/js/moment.js') }}"></script>
<script src="{{ asset('slim/lib/jquery-ui/js/jquery-ui.js') }}"></script>
<script src="{{ asset('slim/lib/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    'use strict'

    $('body').on('click','.submit-date',function (e) {
        e.preventDefault();

        var $value = $('#date').val(),
            $url = $('#form-date').data('action')+'/'+$value;

        window.open($url, '_self');
    });

    // Datepicker
    $('.fc-datepicker').datepicker({
        constrainInput: true,
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true,
        maxDate: '{{ now()->format("Y-m-d")}}',
    });

});
</script>
@endsection