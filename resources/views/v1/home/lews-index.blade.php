@extends('layouts.slim')

@section('title')
Landslide Early Warning System (LEWS)
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gerakan Tanah</a></li>
<li class="breadcrumb-item active" aria-current="page">Landslide Early Warning System</li>
@endsection

@section('page-title')
Landslide Early Warning System (LEWS)
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card card-table mg-t-20 mg-sm-t-30">
            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                        <tr class="tx-10">
                            <th class="wd-20p pd-y-5">Nama Stasiun</th>
                            <th class="pd-y-5">Lokasi</th>
                            <th class="pd-y-5">Jmlah Data</th>
                            <th class="pd-y-5"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stations as $station)
                            @if ($station->nama_dusun)
                            <tr>
                                <td>{{ $station->nama_sta }}</td>
                                <td>{{ "$station->nama_kabupaten, $station->nama_kecamatan, $station->nama_desa, $station->nama_dusun" }}</td>
                                <th>{{ $station->data_count }}</th>
                                <td><a href="{{ route('v1.gertan.lews.show', $station) }}" target="_blank"> Lihat Data</a></td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection