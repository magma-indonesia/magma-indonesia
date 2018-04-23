@extends('layouts.default') 

@section('title') 
    Edit Pos Pengamatan Gunung Api
@endsection

@section('nav-edit-pos')
    <li class="{{ active('pos.*') }}">
        <a href="{{ route('pos.edit',$pga->id) }}">Edit Pos</a>
    </li>
@endsection

@section('content-header')
<div class="small-header">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
                    <li><a href="{{ route('chamber') }}">Chamber</a></li>
                    <li>
                        <span>Data Dasar</span>
                    </li>
                    <li>
                        <span>Gunung Api</span>
                    </li>
                    <li>
                        <span>Pos Pengamatan Gunung Api</span>
                    </li>
                    <li class="active">
                        <span>Edit Pos</span>
                    </li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Edit Pos Pengamatan Gunung Api
			</h2>
			<small>Menu ini untuk digunakan untuk merubah informasi Pos Pengamatan Gunung Api</small>
		</div>
	</div>
</div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-6">
                <div class="hpanel">
                    <div class="panel-heading">
                        Edit Pos Pengamatan Gunung Api {{ $pga->gunungapi->name }}
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('pos.update',$pga->id) }}" class="form-horizontal" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="code" value="{{ $pga->code_id }}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Pos Pengamatan</label>
                                <div class="col-sm-10">
                                    <input name="name" type="text" placeholder="Masukkan Nama Pos" class="form-control" value="{{ $pga->observatory }}" required>
                                    @if( $errors->has('name'))
                                    <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea name="alamat" type="text" placeholder="Alamat Pos Pengamatan" class="form-control" required>{{ $pga->address }}</textarea>
                                    @if( $errors->has('alamat'))
                                    <label id="alamat-error" class="error" for="alamat">{{ ucfirst($errors->first('alamat')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ketinggian</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b">
                                        <input name="ketinggian" type="text" placeholder="Dalam meter" class="form-control" value="{{ $pga->elevation }}" required>
                                        <span class="input-group-addon">mdpl</span>
                                    </div>
                                    @if( $errors->has('keinggian'))
                                    <label id="ketinggian-error" class="error" for="ketinggian">{{ ucfirst($errors->first('ketinggian')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Latitude</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b">
                                        <input name="latitude" type="text" placeholder="Latitude" class="form-control" value="{{ $pga->latitude }}" required>
                                        <span class="input-group-addon">&deg;BT</span>
                                    </div>
                                    @if( $errors->has('latitude'))
                                    <label id="latitude-error" class="error" for="latitude">{{ ucfirst($errors->first('latitude')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Longitude</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b">
                                        <input name="longitude" type="text" placeholder="Longitude" class="form-control" value="{{ $pga->longitude }}" required>
                                        <span class="input-group-addon">&deg;LU</span>
                                    </div>
                                    @if( $errors->has('longitude'))
                                    <label id="longitude-error" class="error" for="longitude">{{ ucfirst($errors->first('longitude')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Keterangan Lain (opsional)</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan" type="text" placeholder="Keterangan tambahan tentang pos, seperti sejarah, lokasi, menuju ke pos, dll" class="form-control" style="height: 150px;">{{ $pga->keterangan }}</textarea>
                                    @if( $errors->has('keterangan'))
                                    <label id="keterangan-error" class="error" for="keterangan">{{ ucfirst($errors->first('keterangan')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>                           
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button class="btn btn-default" onclick="window.location='{{ route('pos.index') }}'">Cancel</button>
                                    <button class="btn btn-primary" type="submit">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection