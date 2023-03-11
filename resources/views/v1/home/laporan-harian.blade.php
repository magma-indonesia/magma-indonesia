@extends('layouts.slim')

@section('title')
Laporan Harian Gunung Api
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
            <a
                href="#tab-{{ $loop->index }}"
                id="home-tab-{{ $loop->index }}"
                class="nav-link active"
                data-toggle="tab"
                role="tab"
                aria-controls="home-{{ $loop->index }}"
                aria-selected="true"
            >{{ $status }} - {{ $gadds->count() }}</a>
        @else
            <a
                href="#tab-{{ $loop->index }}"
                id="home-tab-{{ $loop->index }}"
                class="nav-link"
                data-toggle="tab"
                role="tab"
                aria-controls="home-{{ $loop->index }}"
                aria-selected="true"
            >{{ $status }} - {{ $gadds->count() }}</a>
        @endif
        @endforeach
    </nav>
</div>

<div class="tab-content" id="myTabContent">
    @foreach ($groupedByStatus as $status => $gadds)
    <div id="tab-{{ $loop->index }}" role="tabpanel" aria-labelledby="home-tab-{{ $loop->index }}" class="card card-table mg-b-20 tab-pane {{ $loop->first ? 'show active' : '' }}">
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
                        <td>{{ $key+1 }}</td>
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