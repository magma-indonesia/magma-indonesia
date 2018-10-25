@extends('layouts.default') 

@section('title') 
    Tambah Pos 
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
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
                            <span>Tambah Pos</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    {{ $pgas[0]->gunungapi->name }}
                </h2>
                <small>Daftar Pos Pengamatan Gunung Api Indonesia</small>
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
                        Tambahkan Pos Pengamatan Gunung Api {{ $pgas[0]->gunungapi->name }}
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.pos.store') }}" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="code" value="{{ $pgas[0]->code_id }}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Pos Pengamatan</label>
                                <div class="col-sm-10">
                                    <input name="name" type="text" placeholder="Masukkan Nama Pos" class="form-control" value="{{ old('name') }}" required>
                                    @if( $errors->has('name'))
                                    <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea name="alamat" type="text" placeholder="Alamat Pos Pengamatan" class="form-control" required>{{ old('alamat') }}</textarea>
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
                                        <input name="ketinggian" type="text" placeholder="Dalam meter" class="form-control" value="{{ old('ketinggian') }}" required>
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
                                        <input name="latitude" type="text" placeholder="Latitude" class="form-control" value="{{ old('latitude') }}" required>
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
                                        <input name="longitude" type="text" placeholder="Longitude" class="form-control" value="{{ old('longitude') }}" required>
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
                                    <textarea name="keterangan" type="text" placeholder="Keterangan tambahan tentang pos, seperti sejarah, lokasi, menuju ke pos, dll" class="form-control" style="height: 150px;">{{ old('keterangan') }}</textarea>
                                    @if( $errors->has('keterangan'))
                                    <label id="keterangan-error" class="error" for="keterangan">{{ ucfirst($errors->first('keterangan')) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>                           
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button class="btn btn-default" onclick="window.location='{{ route('chambers.pos.index') }}'">Cancel</button>
                                    <button class="btn btn-primary" type="submit">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                        Daftar Pos Pengamatan Gunung Api {{ $pgas[0]->gunungapi->name }}
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="table-pos" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Pos Pengamatan</th>
                                    <th>Alamat</th>
                                    <th>Ketinggian (mdpl)</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Google Map</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pgas as $pga)
                                <tr>
                                    <td>{{ $pga->observatory }}</td>
                                    <td>{{ $pga->address }}</td>
                                    <td>{{ $pga->elevation }}</td>
                                    <td>{{ $pga->latitude }}</td>
                                    <td>{{ $pga->longitude }}</td>
                                    <td><a class="btn btn-sm btn-magma btn-outline" href="http://maps.google.com/maps?q={{ $pga->latitude }},{{ $pga->longitude }}" target="_blank">Link</a></td> 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection 

@section('add-script')
<script>
    $(document).ready(function(){
        $("#form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 6
                },
                alamat: {
                    required: true,
                    minlength: 6
                },
                ketinggian: {
                    required: false,
                    number: true,
                    maxlength: 4
                },
                latitude: {
                    required: false,
                    number: true,
                    maxlength: 9
                },
                longitude: {
                    required: false,
                    number: true,
                    maxlength: 9
                },
            },
            messages: {
                name: {
                    required: 'Harap Masukkan Nama Pos Pengamatan',
                    minlength: 'Minimal 6 karakter'
                },
                alamat: {
                    required: 'Harap Masukkan Alamat Pos Pengamatan',
                    minlength: 'Panjang karakter minimal 8 karakter',
                },
                ketinggian: {
                    required: 'Format masih belum benar',
                    digits: 'Format dalam numeric (angka)',
                    maxlength: 'Panjang karakter maksimal adalah 9 digit'
                },
                latitude: {
                    required: 'Format masih belum benar',
                    digits: 'Format dalam numeric (angka)',
                    maxlength: 'Panjang karakter maksimal adalah 9 digit'
                },
                longitude: {
                    required: 'Format masih belum benar',
                    digits: 'Format dalam numeric (angka)',
                    maxlength: 'Panjang karakter maksimal adalah 9 digit'
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection