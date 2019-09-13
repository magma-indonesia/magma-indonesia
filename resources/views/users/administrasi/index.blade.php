@extends('layouts.default')

@section('title')
    Administrasi Pegawai
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
                        <span>Pegawai</span>
                    </li>
                    <li class="active">
                        <span>Administrasi </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Administrasi Pegawai
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
                        Tabel Administrasi Pegawai
                    </div>

                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif

                    <div class="panel-body float-e-margins">
                        <a href="{{ route('chambers.users.index') }}" type="button" class="btn btn-magma btn-outline"> << Data Pegawai</a>
                    </div>


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
                                    <th>Golongan/Pangkat</th>
                                    @role('Super Admin')
                                    <th style="min-width: 15%;">Action</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nip }}</td>
                                    <td>{{ $user->administrasi->bidang->nama ?? '-' }}</td>
                                    <td>{{ $user->administrasi->kantor->nama ?? '-'}}</td>
                                    <td>{{ optional($user->administrasi)->jabatan ? $user->administrasi->jabatan->nama : '-' }}</td>
                                    <td>{{ optional($user->administrasi)->fungsional ? $user->administrasi->fungsional->nama : '-' }}</td>
                                    <td>{{ optional($user->administrasi)->golongan ? $user->administrasi->golongan->golongan.'/'.$user->administrasi->golongan->ruang.' - '.$user->administrasi->golongan->pangkat : '-'}}</td>
                                    @role('Super Admin')
                                    <td>
                                        <a href="{{ route('chambers.administratif.administrasi.edit',['id'=> $user->administrasi ]) }}" class="m-t-xs m-b-xs btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                        
                                    </td>
                                    @endrole
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