@extends('layouts.slim')

@section('title')
Laporan Harian Gunung Api
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Gunung Api</li>
<li class="breadcrumb-item active" aria-current="page">Laporan Harian</li>
@endsection

@section('page-title')
Laporan Harian
@endsection

@section('main')
<div class="card card-table">
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
                    <td>Kegempaan</td>
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
@endsection