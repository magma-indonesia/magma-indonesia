@extends('layouts.default')

@section('title')
    Administrasi Users
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
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>Users</span>
                        </li>
                        <li class="active">
                            <span>Administrasi </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Administrasi Users
                </h2>
                <small>Daftar Administrasi pengguna MAGMA Indonesia -  Pusat Vulkanologi dan Mitigasi Bencana Geologi</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <form id="form" role="search" method="post" action="#" enctype="multipart/form-data">
                            <div class="input-group">
                                <input class="form-control" placeholder="Cari nama, nip, bidang, jabatan .." type="text">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Tabel Administrasi Users
                    </div>
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body table-responsive">
                        {{-- {{ $users->links() }} --}}
                        <table id="table-administrasi"class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Bidang</th>
                                    <th>Penempatan</th>
                                    <th>Jabatan</th>
                                    <th>Fungsional</th>
                                    <th>Golongan</th>
                                    <th>Pangkat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nip }}</td>
                                    <td>{{ $user->bidang->deskriptif->nama }}</td>
                                    <td>null</td>
                                    <td>null</td>
                                    <td>null</td>
                                    <td>null</td>
                                    <td>null</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- {{ $users->links() }}                         --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection