@extends('layouts.default')

@section('title')
    Daftar Peta KRB Gunung Api
@endsection

@section('add-vendor-css')

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
                        <span>Peta KRB</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Upload Peta KRB Gunung Api
            </h2>
            <small>Upload Peta KRB Gunung Api Terkini</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">
    <div class="row">
        <div class="col-lg-12 text-center m-t-md">
            <h2>Upload Peta KRB</h2>
        </div>
    </div>

    @if ($errors->any())
    <div class="row m-t-lg">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row m-t-md">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="hpanel">
                <div class="panel-body">
                    <form action="{{ route('chambers.krb-gunungapi.store') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Gunung Api</label>
                            <div class="col-sm-9">
                                <select id="gunungapi" class="form-control m-b" name="code">
                                    @foreach ($gadds as $gadd)
                                    <option value="{{ $gadd->code }}" {{ old('gunungapi') == $gadd->code ? 'selected' : '' }}>{{ $gadd->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Upload File</label>
                            <div class="col-sm-9">
                                <label class="w-xs btn btn-outline btn-default btn-file">
                                    <i class="fa fa-upload"></i>
                                    <span class="label-krb">Browse </span>
                                    <input class="file" type="file" name="krb" style="display: none;">
                                </label>
                                <button type="button" class="w-xs btn btn-danger clear-krb"><i class="fa fa-trash"></i> Clear</button>
                                <small class="help-block m-b text-danger">Maksimal 80MB</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tahun Terbit</label>
                            <div class="col-sm-9">
                                <input type="number" value="{{ now()->format('Y') }}" class="form-control" name="tahun">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea placeholder="Kosongi jika tidak ada keterangan yg perlu ditambahakn" name="keterangan" class="form-control p-m" rows="4"></textarea>
                                <small class="help-block m-b text-danger">Optional</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tayangkan KRB?</label>
                            <div>
                                <label class="checkbox-inline">
                                <input name="published" class="i-checks" type="radio" value="1" id="published"> Ya </label>
                                <label class="checkbox-inline">
                                <input name="published" class="i-checks" type="radio" value="0" id="published" checked> Tidak </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-script')
<script>
    $(document).ready(function () {

        $('input.file').on('change', function(e) {
            var input = $(this),
                label = input.val()
                            .replace(/\\/g, '/')
                            .replace(/.*\//, '');

            $('.label-krb').html(label);
        });

        $('.clear-krb').on('click', function(e) {
            $('.file').val(null);
            $('.label-krb').html('Browse');
        });

    });
</script>
@endsection