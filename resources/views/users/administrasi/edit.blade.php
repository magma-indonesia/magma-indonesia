@extends('layouts.default')

@section('title')
    Edit Data Administrasi
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
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
					<li>
						<span>Administrasi</span>
					</li>
					<li class="active">
						<span>Edit </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Edit Data Administrasi Pegawai
			</h2>
			<small>Menu ini untuk digunakan untuk merubah informasi pengguna MAGMA Indonesia</small>
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
                    Edit Data Administrasi
                </div>

                <div class="panel-body float-e-margins">
                    <a href="{{ route('chambers.administratif.administrasi.index') }}" type="button" class="btn btn-magma btn-outline"> << Data Administrasi</a>
                    <a href="{{ route('chambers.users.index') }}" type="button" class="btn btn-magma btn-outline"> Data Pegawai</a>
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.administratif.administrasi.update', $userAdministratif) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk merubah data administrasi.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

                                            @if ($errors->any())
                                            <div class="row m-b-md">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <p>{{ $error }}</p>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12">
                                                <h4>{{ $userAdministratif->user->name }}</h4>
                                                <div class="hr-line-dashed"></div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Bidang</label>
                                                <select name="bidang" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($bidangs as $bidang)
                                                    <option value="{{ $bidang->id }}" {{ $bidang->id == auth()->user()->administrasi->bidang->id ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Penempatan</label>
                                                <select name="kantor" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($kantors as $kantor)
                                                    <option value="{{ $kantor->code }}" {{ $kantor->code == auth()->user()->administrasi->kantor->code ? 'selected' : '' }}>{{ $kantor->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Jabatan</label>
                                                <select name="jabatan" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($jabatans as $jabatan)
                                                    <option value="{{ $jabatan->id }}" {{ $jabatan->id == auth()->user()->administrasi->jabatan ? 'selected' : '' }}>{{ $jabatan->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Fungsional</label>
                                                <select name="fungsional" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($fungsionals as $fungsional)
                                                    <option value="{{ $fungsional->id }}" {{ $fungsional->id == auth()->user()->administrasi->fungsional ? 'selected' : '' }}>{{ $fungsional->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Golongan/Pangkat</label>
                                                <select name="golongan" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($golongans as $golongan)
                                                    <option value="{{ $golongan->id }}" {{ $golongan->id == auth()->user()->administrasi->golongan ? 'selected' : '' }}>{{ $golongan->golongan.'/'.$golongan->ruang.' - '.$golongan->pangkat }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="hr-line-dashed"></div>
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Save</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/bootstrap/bootstrap-select.min.js') }}"></script>
@endsection