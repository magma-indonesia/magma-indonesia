@extends('layouts.default')

@section('title')
    Gempa Bumi | Daftar Kejadian 
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
                        <li>
                            <a href="{{ route('chambers.index') }}">Chambers</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.gempabumi.index') }}">Gempa Bumi</a>
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
                        MAGMA ROQ - Gempa Bumi
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
                                        <th>Jarak (km) dengan Kota Terdekat</th>
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
                                        <td>{{ $roq->utc }}</td>
                                        <td>{{ $roq->wib }}</td>
                                        <td>{{ $roq->magnitude.' '.$roq->type }}</td>
                                        <td>{{ $roq->mmi ?? 'Belum ada data' }}</td>
                                        <td>{{ $roq->depth }}</td>
                                        <td>{{ $roq->latitude }}</td>
                                        <td>{{ $roq->longitude }}</td>
                                        <td>{{ $roq->distance ?? 'Belum ada data' }}</td>
                                        <td>{{ $roq->kota_terdekat ?? 'Belum ada data' }}</td>
                                        <td>{{ $roq->nearest_volcano }}</td>
                                        <td>{{ optional($roq->tanggapan)->nip_pelapor ? 'Ada' : 'Tidak ada' }}</td>
                                        <td>
                                            @if(optional($roq->tanggapan)->nip_pelapor)
                                            <a href="{{ route('chambers.gempabumi.tanggapan.edit',['noticenumber_id'=> $roq->noticenumber]) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>   
                                            @else
                                            <a href="{{ route('chambers.gempabumi.tanggapan.create') }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">Buat</a>   
                                            @endif
                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.gempabumi.destroy',['id' => $roq->id]) }}" accept-charset="UTF-8">
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