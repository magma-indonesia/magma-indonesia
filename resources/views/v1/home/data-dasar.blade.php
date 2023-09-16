@extends('layouts.slim')

@section('title')
Data Dasar Gunung Api
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">Data Dasar</li>
@endsection

@section('page-title')
Data Dasar Gunung Api
@endsection

@section('main')
<div class="card">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card card-table">
                <div class="table-responsive">
                    <table class="table mg-b-0 tx-13">
                        <thead>
                            <tr class="tx-10">
                                <th class="pd-y-5">Gunung Api</th>
                                <th class="pd-y-5">Latitude (LU)</th>
                                <th class="pd-y-5">Longitude (BT)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($gadds as $gadd)
                            <tr>
                                <td>
                                    <a href="{{ route('v1.gunungapi.show', ['name' => $gadd->slug ]) }}" class="tx-inverse tx-14 tx-medium d-block">{{ $gadd->ga_nama_gapi }}</a>
                                </td>
                                <td>{{ $gadd->ga_lat_gapi }}</td>
                                <td>{{ $gadd->ga_lon_gapi }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
