@extends('layouts.default')

@section('title')
    v1 - Laporan Gunung Api (VAR)
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
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Laporan (VAR) </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Laporan harian gunung api (VAR)
                </h2>
                <small>Meliputi data laporan per 6 jam maupun harian (24 jam)</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            @if(Session::has('flash_message'))
            <div class="alert alert-success">
                <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
            </div>
            @endif

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Laporan MAGMA-VAR 
                </div>
                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.v1.gunungapi.laporan.filter') }}"" class="btn btn-outline btn-block btn-magma" type="button">Filter Laporan</a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.v1.gunungapi.laporan.filter.gempa') }}"" class="btn btn-outline btn-block btn-magma" type="button">Filter Berdasarkan Gempa</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar 30 Laporan MAGMA-VAR terakhir
                </div>
                <div class="panel-body">
                    {{ $vars->links() }}
                    <div class="table-responsive">
                        <table id="table-var" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Pelapor</th>
                                    <th style="min-width: 240px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vars as $key => $var)
                                <tr>
                                    <td>{{ $vars->firstItem()+$key }}</td>
                                    <td>{{ $var->ga_nama_gapi }}</td>
                                    <td>{{ $var->var_data_date->format('Y-m-d') }}</td>
                                    <td>{{ $var->periode }}</td>
                                    <td>
                                        @if($var->cu_status == '1') 
                                        Level I (Normal)
                                        @elseif($var->cu_status == '2')
                                        Level II (Waspada)
                                        @elseif($var->cu_status == '3')
                                        Level III (Siaga)
                                        @else
                                        Level IV (Awas)
                                        @endif
                                    </td>
                                    <td>{{ $var->var_nama_pelapor }}</td>
                                    <td>
                                        <a href="{{ route('chambers.v1.gunungapi.laporan.show',['id'=> $var->no]) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $vars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection