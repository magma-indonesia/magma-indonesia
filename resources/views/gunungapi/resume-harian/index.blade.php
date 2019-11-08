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

    @if ($pendahuluans == 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">   
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Data Pendahuluan Bencana Geologi belum ada. <a href="{{ route('chambers.bencana-geologi-pendahuluan.create') }}"><b>Mau buat baru?</b></a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($resumes->isEmpty() AND $pendahuluans>0)
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">   
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Data Bencana Geologi harian belum ada. <a href="{{ route('chambers.resume-harian.create') }}"><b>Generate Laporan?</b></a>
                </div>
            </div>
        </div>
    </div>
    @elseif ($resumes->isNotEmpty())

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Resume Harian Gunung Api
                </div>

                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endforeach
                @endif

                @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                </div>
                @endif

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.resume-harian.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Generate Resume Hari ini</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    {{ $resumes->links() }}
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="20%">Tanggal</th>
                                    <th>Resume</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resumes as $key => $resume)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $resume->tanggal->formatLocalized('%A, %d %B %Y') }}</td>
                                    <td>{{ $resume->truncated }}</td>
                                    <td>
                                        <a href="{{ route('chambers.resume-harian.show', $resume) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>

                                        <a href="{{ route('chambers.resume-harian.edit', $resume->tanggal) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Untuk menghapus data yang digunakan, silahkan kontak Admin</a>
                </div>

            </div>
        </div>
    </div>

    @endif
</div>
@endsection