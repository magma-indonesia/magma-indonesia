@extends('layouts.default') 

@section('title') 
MAGMA | Edit - {{ $gadd->name }}
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote-bs3.css') }}" />
@endsection

@section('nav-edit-volcano')
<li class="{{ active('volcanoes.*') }}">
    <a href="{{ route('volcanoes.edit',$gadd->id) }}">Edit {{ $gadd->name }}</a>
</li>
@endsection

@section('content-header')
<div class="small-header">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li>
						<a href="{{ route('chamber') }}">Chamber</a>
					</li>
					<li>
						<span>Gunung Api</span>
					</li>
					<li class="active">
						<span>{{ $gadd->name }} </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Edit {{ $gadd->name }}
			</h2>
			<small>Menu ini untuk digunakan untuk merubah informasi data dasar Gunung Api {{ $gadd->name }}</small>
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
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>
                    Edit Data Dasar Gunung Api {{ $gadd->name }}
                </div>
                <div class="panel-body">
                    <p>
                        Perubahan data dilakukan jika memang ada data yang tidak valid atau data memang perlu untuk diperbarui.
                    </p>

                    <form role="form" id="form" method="POST" action="{{ route('volcanoes.update',$gadd->id) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input name="_type" value="base" type="hidden">

                        <div class="form-group">
                            <label>Nama</label> 
                            <input name="name" type="text" placeholder="Masukkan Nama Gunung Api" class="form-control" value="{{ $gadd->name }}" required>
                            @if( $errors->has('name'))
                            <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Sejarah Gunung Api</label> 
                            <textarea name="body" class="summernote">{{ $gadd->history->body }}</textarea>
                            @if( $errors->has('name'))
                            <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tipe Gunung Api</label>
                            <select class="form-control" name="tipe">
                                <option value="A" {{ $gadd->volc_type == 'A' ? 'selected' : ''}}>A</option>
                                <option value="B" {{ $gadd->volc_type == 'B' ? 'selected' : ''}}>B</option>
                                <option value="C" {{ $gadd->volc_type == 'C' ? 'selected' : ''}}>C</option>
                            </select>
                            @if( $errors->has('tipe'))
                            <label id="tipe-error" class="error" for="tipe">{{ ucfirst($errors->first('tipe')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Ketinggian</label>
                            <div class="input-group m-b">
                                <input class="form-control" name="ketinggian" placeholder="Tinggi Gunung Api" type="text" value="{{ $gadd->elevation }}" required> 
                                <span class="input-group-addon">mdpl</span>
                            </div>
                            @if( $errors->has('ketinggian'))
                            <label id="ketinggian-error" class="error" for="ketinggian">{{ ucfirst($errors->first('ketinggian')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Posisi Geografis</label>
                            <div class="input-group m-b">
                                <input class="form-control" name="latitude" placeholder="Latitude" type="text" value="{{ $gadd->latitude }}" required> 
                                <span class="input-group-addon">&deg;BT</span>
                            </div>
                            @if( $errors->has('latitude'))
                            <label id="latitude-error" class="error" for="latitude">{{ ucfirst($errors->first('latitude')) }}</label>
                            @endif
                            <div class="input-group m-b">
                                <input class="form-control" name="longitude" placeholder="Longitude" type="text" value="{{ $gadd->longitude }}" required> 
                                <span class="input-group-addon">&deg;LU</span>
                            </div>
                            @if( $errors->has('longitude'))                            
                            <label id="longitude-error" class="error" for="longitude">{{ ucfirst($errors->first('longitude')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label>
                            <input name="provinsi" type="text" placeholder="Masukkan Nama Provinsi Gunung Api" class="form-control" value="{{ $gadd->province }}" required>
                            @if( $errors->has('provinsi'))
                            <label id="provinsi-error" class="error" for="provinsi">{{ ucfirst($errors->first('provinsi')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Kota/Kabupaten</label> 
                            <input name="kota" type="text" placeholder="Masukkan Nama Kota/Kabupaten Gunung Api" class="form-control" value="{{ $gadd->district }}" required>
                            @if( $errors->has('kota'))
                            <label id="kota-error" class="error" for="kota">{{ ucfirst($errors->first('kota')) }}</label>
                            @endif
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
