@extends('layouts.slim')

@section('title')
Statistik
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Statistik</li>
@endsection

@section('page-title')
Statistik
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-12">
        <div class="card pd-30 mg-b-20">
            <label class="slim-card-title">Tahun</label>
            <div class="row row-xs">
                <div class="col-xs-12">
                    @foreach ($years as $year)
                    <a href="{{ route('v1.statistik.index', ['year' => $year->format('Y')]) }}" type="button"
                        class="btn btn-sm btn-primary mg-b-10">{{ $year->format('Y') }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-table mg-t-20 mg-sm-t-30">
    <div class="table-responsive">
        <table class="table mg-b-0 tx-13">
            <thead>
                <tr class="tx-10">
                    <th class="wd-10p pd-y-5 tx-center">Bulan</th>
                    <th class="pd-y-5">Jumlah Laporan</th>
                    <th class="pd-y-5">Jumlah Gempa Vulkanik</th>
                    <th class="pd-y-5">Jumlah Gempa Letusan</th>
                    <th class="pd-y-5">Jumlah Awan Panas Guguran</th>
                    <th class="pd-y-5">Jumlah Gunung Meletus</th>
                    <th class="pd-y-5">Jumlah VONA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gunung_apis as $gunung_api)
                <tr>
                    <td>{{ $gunung_api['date'] }}</td>
                    <td>{{ $gunung_api['jumlah']['laporan'] }}</td>
                    <td>{{ $gunung_api['jumlah']['gempa_vulkanik'] }}</td>
                    <td>{{ $gunung_api['jumlah']['gempa_letusan'] }}</td>
                    <td>{{ $gunung_api['jumlah']['awan_panas_guguran'] }}</td>
                    <td>{{ $gunung_api['jumlah']['gunung_meletus'] }}</td>
                    <td>{{ $gunung_api['jumlah']['vona'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection