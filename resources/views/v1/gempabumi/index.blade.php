@extends('layouts.default')

@section('title')
    v1 - Gempa Bumi
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>MAGMA v1</span>
                        </li>
                        <li class="active">
                            <span>Gempa Bumi </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Kejadian Gempa Bumi
                </h2>
                <small>Daftar data laporan Gempa Bumi berdasarkan data BMKG</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Kejadian Gempa Bumi dari MAGMA v1
                    </div>
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body">
                        {{ $roqs->links() }}
                        <div class="table-responsive">
                            <table id="table-ven" class="table  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu Kejadian (UTC)</th>
                                        <th>Waktu Kejadian (WIB)</th>
                                        <th>Magnitude</th>
                                        <th>MMI</th>
                                        <th>Kedalaman (km)</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Kota Terdekat</th>
                                        <th>Gunung Api Terdekat</th>
                                        <th>Tanggapan</th>
                                        <th style="min-width: 240px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roqs as $key => $roq)
                                    <tr>
                                        <td>{{ $roqs->firstItem()+$key }}</td>
                                        <td>{{ $roq->datetime_utc }}</td>
                                        <td>{{ $roq->datetime_wib }}</td>
                                        <td>{{ $roq->magnitude.' '.$roq->magtype }}</td>
                                        <td>{{ $roq->mmi ?? 'Belum ada data' }}</td>
                                        <td>{{ $roq->depth }}</td>
                                        <td>{{ $roq->lat_lima }}</td>
                                        <td>{{ $roq->lon_lima }}</td>
                                        <td>{{ $roq->area ?? 'Belum ada data' }}</td>
                                        <td>{{ $roq->nearest_volcano ?? 'Belum ada data' }}</td>
                                        <td>{{ $roq->roq_tanggapan }}</td>
                                        <td>
                                            @if($roq->roq_tanggapan == 'YA')
                                            <a href="{{ route('chambers.v1.gempabumi.edit',['id'=> $roq->no]) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                            @else
                                            <a href="{{ route('chambers.v1.gempabumi.create') }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">Buat</a>
                                            @endif
                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.v1.gempabumi.destroy',['id' => $roq->no]) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                            @endrole   
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
@endsection