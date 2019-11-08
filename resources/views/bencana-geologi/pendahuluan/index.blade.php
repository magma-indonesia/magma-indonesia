@extends('layouts.default')

@section('title')
    Kebencanaan Geologi
@endsection

@section('add-vendor-css')
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
<div class="content animate-panel content-boxed normalheader">
    <div class="hpanel">
        <div class="panel-body">   
            <h2 class="font-light m-b-xs">
                Daftar Kebencanaan Geologi 
            </h2>
            <small class="font-light"> Digunakan dalam laporan Kebencanan Geologi Harian</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Kebencanaan Geologi 
                </div>

                @if ($bencanas->isEmpty())
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Data Pendahuluan Bencana Geologi belum ada. <a href="{{ route('chambers.bencana-geologi.create') }}"><b>Mau buat baru?</b></a>
                </div>
                @else

                @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                </div>
                @endif

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.bencana-geologi-pendahuluan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Buat Pendahuluan Baru</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="alert alert-info">
                        <i class="fa fa-bolt"></i> Pendahuluan gunung api bisa dibuat lebih dari satu. Menyesuaikan dengan kondisi dan hasil evaluasi terakhir. </a>
                    </div>
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Laporan Pendahuluan</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bencanas as $key => $bencana)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $bencana->gunungapi->name }}</td>
                                    <td>{{ $bencana->pendahuluan }}</td>
                                    <td>
                                        <a href="{{ route('chambers.bencana-geologi-pendahuluan.edit', $bencana) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-footer">
                    <div class="alert alert-danger">
                        <i class="fa fa-gears"></i> Untuk menghapus data yang digunakan, silahkan kontak Admin</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection