@extends('layouts.default')

@section('title')
    Update Kegiatan Bidang MGA
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>Administratif</span>
                        </li>
                        <li>
                            <span>MGA</span>
                        </li>
                        <li class="active">
                            <span>Buat Kegiatan </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Tambahkan jenis kegatan bidang MGA
                </h2>
                <small>Meliputi seluruh jenis kegiatan yang sedang, pernah, atau akan dilakukan (perencanaan) </small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Form Buat Jenis Kegiatan Baru MGA
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('chambers.administratif.mga.jenis-kegiatan.update', $jenisKegiatan) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="tab-content">
                                <div class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-4 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan data jenis kegiatan baru.
                                            </p>
                                        </div>

                                        <div class="col-lg-8">
                                            <div class="row p-md">

                                                @if ($errors->any())
                                                <div class="row m-b-md">
                                                    <div class="col-lg-12">
                                                        <div class="alert alert-danger">
                                                            <p>Nama Kegiatan telah digunakan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="form-group col-sm-12">
                                                    <label>Nama Kegiatan</label>
                                                    <input class="form-control" name="name" value="{{ $jenisKegiatan->nama }}" placeholder="Nama Kegiatan" required>
                                                    <small class="help-block m-b text-danger">*required</small>
                                                </div>

                                                <div class="form-group col-sm-12">
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