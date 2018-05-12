@extends('layouts.default')

@section('title')
    VONA | Volcano Observatory Notice for Aviation
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.vona.index') }}">VONA</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar VONA
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
                        VONA
                    </div>
                    <div class="panel-body float-e-margins m-b">
                        <div class="row text-center">
                            <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                                <a href="{{ route('chambers.vona.create') }}" class="btn btn-outline btn-block btn-success" type="button">Buat VONA Baru</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ $vonas->links() }}
                        <div class="table-responsive">
                            <table id="table-jabatan" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano</th>
                                        <th>Issued</th>
                                        <th>Current Aviation Colour Code</th>
                                        <th>Volcano Cloud Height (ASL)</th>
                                        <th>Sender</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vonas as $key => $vona)
                                    <tr>
                                        <td>{{ $vonas->firstItem()+$key }}</td>
                                        <td>{{ $vona->gunungapi->name }}</td>
                                        <td>{!! $vona->issued.'<b> UTC</b>' !!}</td>
                                        <td>{{ title_case($vona->cu_code) }}</td>
                                        <td>{{ $vona->vch_asl.' meter' }}</td>
                                        <td>{{ $vona->user->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $vonas->links() }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection