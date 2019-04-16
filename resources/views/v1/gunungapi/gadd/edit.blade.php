@extends('layouts.default') 

@section('title') 
v1 - Gunungapi | Edit - {{ $gadd->name }}
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote-bs3.css') }}" />
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
                        <span>Magma v1</span>
                    </li>
                    <li>
                        <span>Gunung Api</span>
                    </li>
                    <li>
                        <span>Data Dasar</span>
                    </li>
                    <li class="active">
                        <span>Edit</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Edit {{ $gadd->ga_nama_gapi }}
            </h2>
            <small>Menu ini untuk digunakan untuk merubah informasi data dasar Gunung Api {{ $gadd->ga_nama_gapi }}</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-offset-3 col-md-12 col-lg-6">
            <div class="hpanel">
                <div class="panel-heading">
                    Edit Data Dasar Gunung Api {{ $gadd->name }}
                </div>
                <div class="panel-body">
                    <p>Perubahan data dilakukan jika memang ada data yang tidak valid atau data memang perlu untuk diperbarui.</p>

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

                    <form role="form" id="form" method="POST" action="{{ route('chambers.v1.gunungapi.data-dasar.update',$gadd->ga_code) }}">
                        @method('PUT')
                        @csrf

                        <div class="form-group">
                            <label>Nama</label>
                            <input name="code" type="hidden" value="{{ $gadd->ga_code }}">
                            <input name="name" type="text" placeholder="Perbaiki Nama Gunung Api" class="form-control" value="{{ $gadd->ga_nama_gapi }}" required>
                        </div>

                        <div class="form-group">
                            <label>Tentang Gunung Api</label> 
                            <textarea name="intro" class="summernote">{{ !empty($gadd->history->intro) ? $gadd->history->intro : '' }}</textarea>
                            <label class="m-t-20">Tampilkan VONA atau VEN ke dalam informasi Gunung Api</label>
                            <div class="checkbox">
                                <label><input name="ven" value="1" type="checkbox" class="i-checks"> Sertakan VEN </label>
                            </div>
                            <div class="checkbox">
                                <label><input name="vona" value="1" type="checkbox" class="i-checks"> Sertakan VONA </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tipe Gunung Api</label>
                            <select class="form-control" name="tipe">
                                <option value="A" {{ $gadd->ga_tipe_gapi == 'A' ? 'selected' : ''}}>Tipe A</option>
                                <option value="B" {{ $gadd->ga_tipe_gapi == 'B' ? 'selected' : ''}}>Tipe B</option>
                                <option value="C" {{ $gadd->ga_tipe_gapi == 'C' ? 'selected' : ''}}>Tipe C</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Ketinggian</label>
                            <div class="input-group m-b">
                                <input class="form-control" name="ketinggian" placeholder="Tinggi Gunung Api" type="text" value="{{ $gadd->ga_elev_gapi }}" required> 
                                <span class="input-group-addon">mdpl</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Posisi Geografis</label>
                            <div class="input-group m-b">
                                <input class="form-control" name="latitude" placeholder="Latitude" type="text" value="{{ $gadd->ga_lat_gapi }}" required> 
                                <span class="input-group-addon">&deg;BT</span>
                            </div>
                            <div class="input-group m-b">
                                <input class="form-control" name="longitude" placeholder="Longitude" type="text" value="{{ $gadd->ga_lon_gapi }}" required> 
                                <span class="input-group-addon">&deg;LU</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Provinsi</label>
                            <input name="provinsi" type="text" placeholder="Masukkan Nama Provinsi Gunung Api" class="form-control" value="{{ $gadd->ga_prov_gapi }}" required>
                        </div>

                        <div class="form-group">
                            <label>Kota/Kabupaten</label> 
                            <input name="kota" type="text" placeholder="Masukkan Nama Kota/Kabupaten Gunung Api" class="form-control" value="{{ $gadd->ga_kab_gapi }}" required>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div>
                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>Submit</strong></button>
                            <a href="{{ url()->previous() }}" type="button" class="btn btn-sm btn-default m-t-n-xs">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/summernote/dist/summernote.min.js') }}"></script>
@endsection

@section('add-script')
<script>

    $(document).ready(function () {

        // Initialize summernote plugin
        $('.summernote').summernote({
            height: '300px',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['hr']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ]
        });

    });

</script>
@endsection