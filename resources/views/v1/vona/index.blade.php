@extends('layouts.default')

@section('title')
    v1 - VONA
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
                            <span>VONA </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Laporan VONA
                </h2>
                <small>Daftar VONA yang pernah dibuat dan dikirim kepada stakeholder terkait.</small>
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
                        Menu VONA
                    </div>
                    <div class="panel-body float-e-margins">
                        <div class="col-md-3 col-lg-2 col-xs-12">
                            <a href="{{ route('chambers.v1.vona.filter') }}" class="btn btn-outline btn-block btn-magma" type="button">Filter</a>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xs-12">
                            <a href="{{ route('chambers.v1.subscribers.index') }}" class="btn btn-outline btn-block btn-magma" type="button">Subscribers</a>
                        </div>
                    </div>
                </div>
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar VONA Terkirim
                    </div>
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body">
                        {{ $vonas->links() }}
                        <div class="table-responsive">
                            <table id="table-jabatan" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano</th>
                                        <th>Issued (UTC)</th>
                                        <th>Current Code</th>
                                        <th>Previous Code</th>
                                        <th>Cloud Height (ASL)</th>
                                        <th>Sender</th>
                                        <th style="min-width: 180px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vonas as $key => $vona)
                                    <tr>
                                        <td>{{ $vonas->firstItem()+$key }}</td>
                                        <td>{{ $vona->ga_nama_gapi }}</td>
                                        <td>{{ $vona->issued_time }}</td>
                                        <td>{{ $vona->cu_avcode }}</td>
                                        <td>{{ strtolower($vona->pre_avcode) }}</td>
                                        <td>{{ $vona->vc_height ? intval($vona->vc_height/3.2).' m' : 'Not observed' }}</td>
                                        <td>{{ $vona->nama }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-magma btn-outline" href="{{ route('chambers.v1.vona.show',['no' => $vona->no])}}" target="_blank">View</a>
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