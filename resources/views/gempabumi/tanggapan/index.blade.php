@extends('layouts.default')

@section('title')
    Gempa Bumi | Daftar Tanggapan
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
                        <li>
                            <a href="{{ route('chambers.gempabumi.index') }}">Gempa Bumi</a>
                        </li>
                        <li class="active">
                                <a href="{{ route('chambers.gempabumi.tanggapan.index') }}">Tanggapan</a>
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