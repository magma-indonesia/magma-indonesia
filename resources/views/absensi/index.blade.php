@extends('layouts.default')

@section('title')
    Absensi Pegawai 
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
                        <li>
                            <a href="{{ route('chambers.users.index') }}">Users</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.absensi.index') }}">Absensi</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Absensi Pegawai PVMBG
                </h2>
                <small>Data absensi Pegawai PVMBG, Badan Geologi, ESDM</small>
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
                        Absensi Pegawai PVMBG
                    </div>
                    @role('Super Admin')
                    <div class="panel-body">
                        <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                            <a href="{{ route('chambers.absensi.create') }}" class="btn btn-outline btn-block btn-success" type="button">Buat Absensi Pegawai</a>
                        </div>
                    </div>
                    @endrole
                </div>
                <div class="hpanel">
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body">
                        {{ $absensis->links() }}
                        <div class="table-responsive">
                            <table id="table-absensi" class="table  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nip</th>
                                        <th>Nama</th>
                                        <th>Checkin</th>
                                        <th>Checkout</th>
                                        <th>Durasi Kerja</th>
                                        <th>Jarak (radius)</th>
                                        <th>Verifikator</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensis as $key => $absensi)
                                    <tr>
                                        <td>{{ $absensis->firstItem()+$key }}</td>
                                        <td>{{ $absensi->nip_id }}</td>
                                        <td>{{ $absensi->user->name }}</td>
                                        <td>{{ optional($absensi->checkin)->formatLocalized('%d %B %Y, %H:%M:%S') ?? 'Manual'}}</td>
                                        <td>{{ optional($absensi->checkout)->formatLocalized('%d %B %Y, %H:%M:%S') ?? '-'}}</td>
                                        <td>{{ optional($absensi->checkout)->diffInHours($absensi->checkin) ?? '-'}} </td>
                                        <td>{{ $absensi->distance }} m</td>
                                        <td>{{ $absensi->verifikator->name ?? 'Belum diverifikasi'}}</td>
                                        <td>{{ $absensi->keterangan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $absensis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection