@extends('layouts.default')

@section('title')
    Gunung Api | Laporan Letusan 
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
                        <li><a href="{{ route('chambers.index') }}">Chambers</a></li>
                        <li>
                            <span>Gunung Api </span>
                        </li>
                        <li class="active">
                            <span>Laporan Letusan</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Laporan Letusan Gunung Api
                </h2>
                <small>Daftar data laporan letusan Gunung Api</small>
            </div>
        </div>
    </div>
@endsection