@extends('layouts.default')

@section('title')
Buat Informasi Letusan (VEN)
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li>
                        <a href="{{ route('chambers.index') }}">Chambers</a>
                    </li>
                    <li>
                        <a href="{{ route('chambers.datadasar.index') }}">Gunung Api</a>
                    </li>
                    <li>
                        <a href="{{ route('chambers.letusan.index') }}">Informasi Letusan</a>
                    </li>
                    <li class="active">
                        <span>Buat Laporan</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Form Laporan Letusan Gunung Api
            </h2>
            <small>VEN dan VONA</small>
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
                    Form Informasi Letusan (VEN dan VONA)
                </div>
                <div class="panel-body">
                    @if ($errors->any())
                    <div class="form-group col-sm-12">
                        <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                        </div>
                    </div>
                    @endif

                    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.letusan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">

                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Pilih gunung api dan statusnya. Gunung api dan status akan menentukan rekomendasi yang akan digunakan.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Pilih Jenis letusan --}}
                                        <div class="form-group col-sm-12">
                                            <label>Jenis</label>
                                            <select id="jenis" class="form-control" name="jenis">
                                                <option value="lts" {{ old('jenis') == 'lts' ? 'selected' : ''}}>Erupsi/Letusan</option>
                                                <option value="apg" {{ old('jenis') == 'apg' ? 'selected' : ''}}>Awan Panas Guguran</option>
                                            </select>
                                        </div>

                                        {{-- Nama Gunung Api --}}
                                        <div class="form-group col-sm-12">
                                            <label>Gunung Api</label>
                                            <select id="code" class="form-control" name="code">
                                                @foreach($gadds as $gadd)
                                                <option value="{{ $gadd->code }}" {{ old('code') == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}</option>
                                                @endforeach
                                            </select>
                                            @if( $errors->has('code'))
                                            <label class="error" for="code">{{ ucfirst($errors->first('code')) }}</label>
                                            @endif
                                        </div>

                                        {{-- Status --}}
                                        <div class="form-group col-sm-12">
                                            <label>Tingkat Aktivitas Gunung Api</label>
                                            <select id="status" class="form-control" name="status">
                                                <option value="1" {{ old('status') == '1' ? 'selected' : ''}}>Level I (Normal)</option>
                                                <option value="2" {{ old('status') == '2' ? 'selected' : ''}}>Level II (Waspada)</option>
                                                <option value="3" {{ old('status') == '3' ? 'selected' : ''}}>Level III (Siaga)</option>
                                                <option value="4" {{ old('status') == '4' ? 'selected' : ''}}>Level IV (Awas)</option>
                                            </select>
                                            @if( $errors->has('status'))
                                            <label class="error" for="status">{{ ucfirst($errors->first('status')) }}</label>
                                            @endif
                                        </div>

                                        {{-- Waktu Letusan --}}
                                        <div class="form-group col-sm-12">
                                            <label>Waktu Letusan (Waktu Lokal)</label>
                                            <input name="date" id="datepicker" class="form-control" type="text" value="{{ empty(old('date')) ? now()->format('Y-m-d H:i') : old('date') }}">
                                            @if( $errors->has('date'))
                                            <label class="error" for="date">{{ ucfirst($errors->first('date')) }}</label>
                                            @endif
                                        </div>

                                        {{-- Erupsi sedang berlangsung --}}
                                        <div class="form-group col-sm-12">
                                            <label>Apakah erupsi sedang berlangsung?</label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="erupsi_berlangsung" value="1" type="radio" class="i-checks draft"
                                                            {{ old('erupsi_berlangsung') == '1' ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="erupsi_berlangsung" value="0" type="radio" class="i-checks draft"
                                                            {{ (old('erupsi_berlangsung') == '0' OR empty(old('erupsi_berlangsung'))) ? 'checked' : ''}}> Tidak </label>
                                                    <span class="help-block m-b-none">Pilih Opsi ini jika ketika laporan dibuat, erupsi masih berlangsung.</span>
                                                    @if( $errors->has('erupsi_berlangsung'))
                                                    <label class="error" for="erupsi_berlangsung">{{ ucfirst($errors->first('erupsi_berlangsung')) }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                {{-- Letusan --}}
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-cloud-upload fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Masukkan parameter pemantauan visual <b>Letusan atau Awan Panas Guguran</b>.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Visual Kolom Abu --}}
                                        <div class="form-group col-sm-12">
                                            <label>Parameter Visual</label>
                                            <select id="visibility" class="form-control" name="visibility">
                                                <option value="1" {{ old('visibility') == '1' ? 'selected' : ''}}>Teramati</option>
                                                <option value="0" {{ old('visibility') == '0' ? 'selected' : ''}}>Tidak Teramati</option>
                                            </select>
                                            @if( $errors->has('visibility'))
                                            <label class="error" for="visibility">{{ ucfirst($errors->first('visibility')) }}</label>
                                            @endif
                                        </div>

                                        <div class="teramati" style="display: {{ old('visibility') == '0' ? 'none' :'block'}};">
                                            {{-- Tinggi Letusan --}}
                                            <div class="form-group col-sm-12">
                                                <label>Tinggi Letusan/Tinggi Abu akibat guguran</label>
                                                <div class="input-group">
                                                    <input placeholder="Maksimal 20000 meter" name="height" class="form-control" type="text" value="{{ empty(old('height')) ? '' : old('height') }}">
                                                    <span class="input-group-addon h-bg-red">meter, di atas puncak</span>
                                                </div>
                                                <span class="help-block m-b-none">Untuk <b>Awan Panas Guguran</b> masukkan nilai 0 jika tinggi abu akibat APG masih di bawah puncak</span>
                                                @if( $errors->has('height'))
                                                <label class="error" for="height">{{ ucfirst($errors->first('height')) }}</label>
                                                @endif

                                            </div>

                                            {{-- Warna Abu --}}
                                            <div class="form-group col-sm-12">
                                                <label>Warna Abu Letusan/Awan Panas Guguran</label>

                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label><input name="warna_asap[]" value="Putih" type="checkbox" class="i-checks wasap" {{ (is_array(old('warna_asap')) AND in_array('Putih',old('warna_asap'))) ? 'checked' : ''}}> Putih </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="warna_asap[]" value="Kelabu" type="checkbox" class="i-checks wasap" {{ (is_array(old('warna_asap')) AND in_array('Kelabu',old('warna_asap'))) ? 'checked' : ''}}> Kelabu </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label><input name="warna_asap[]" value="Coklat" type="checkbox" class="i-checks wasap" {{ (is_array(old('warna_asap')) AND in_array('Coklat',old('warna_asap'))) ? 'checked' : ''}}> Coklat </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="warna_asap[]" value="Hitam" type="checkbox" class="i-checks wasap" {{ (is_array(old('warna_asap')) AND in_array('Hitam',old('warna_asap'))) ? 'checked' : ''}}> Hitam </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($errors->has('warna_asap'))
                                                        <label class="error" for="warna_asap">{{  ucfirst($errors->first('warna_asap')) }}</label>
                                                    @endif
                                                    @if($errors->has('warna_asap.*'))
                                                    @foreach($errors->get('warna_asap.*') as $error)
                                                        <label class="error" for="warna_asap">{{ $error[0] }}</label>
                                                        @break
                                                    @endforeach
                                                    @endif
                                            </div>

                                            {{-- Intensitas --}}
                                            <div class="jenis-lts form-group col-sm-12" style="display: {{ old('jenis') == 'apg' ? 'none' :'apg'}};">
                                                <label>Intensitas</label>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label class="checkbox-inline"><input name="intensitas[]" value="Tipis" type="checkbox" class="i-checks intensitas" {{ (is_array(old('intensitas')) AND in_array('Tipis',old('intensitas'))) ? 'checked' : ''}}> Tipis </label>
                                                        <label class="checkbox-inline"><input name="intensitas[]" value="Sedang" type="checkbox" class="i-checks intensitas" {{ (is_array(old('intensitas')) AND in_array('Sedang',old('intensitas'))) ? 'checked' : ''}}> Sedang </label>
                                                        <label class="checkbox-inline"><input name="intensitas[]" value="Tebal" type="checkbox" class="i-checks intensitas" {{ (is_array(old('intensitas')) AND in_array('Tebal',old('intensitas'))) ? 'checked' : ''}}> Tebal </label>
                                                    </div>
                                                </div>
                                                @if( $errors->has('intensitas'))
                                                <label class="error" for="intensitas">{{ ucfirst($errors->first('intensitas')) }}</label>
                                                @endif
                                                @if($errors->has('intensitas.*'))
                                                @foreach($errors->get('intensitas.*') as $error)
                                                    <label class="error" for="intensitas">{{  $error[0] }}</label>
                                                    @break
                                                @endforeach
                                                @endif
                                            </div>

                                            {{-- Arah Abu --}}
                                            <div class="form-group col-sm-12">
                                                <label>Abu Mengarah ke</label>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Utara" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Utara',old('arah_abu'))) ? 'checked' : ''}}> Utara </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Timur Laut" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Timur Laut',old('arah_abu'))) ? 'checked' : ''}}> Timur Laut </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Timur" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Timur',old('arah_abu'))) ? 'checked' : ''}}> Timur </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Tenggara" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Tenggara',old('arah_abu'))) ? 'checked' : ''}}> Tenggara </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Selatan" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Selatan',old('arah_abu'))) ? 'checked' : ''}}> Selatan </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat Daya" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Barat Daya',old('arah_abu'))) ? 'checked' : ''}}> Barat Daya </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Barat',old('arah_abu'))) ? 'checked' : ''}}> Barat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat Laut" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('arah_abu')) AND in_array('Barat Laut',old('arah_abu'))) ? 'checked' : ''}}> Barat Laut </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if( $errors->has('arah_abu'))
                                                <label class="error" for="arah_abu">{{ ucfirst($errors->first('arah_abu')) }}</label>
                                                @endif
                                                @if($errors->has('arah_abu.*'))
                                                @foreach($errors->get('arah_abu.*') as $error)
                                                    <label class="error" for="arah_abu">{{  $error[0] }}</label>
                                                    @break
                                                @endforeach
                                                @endif
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Upload Foto Letusan</label>

                                                <div class="form-group col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <img class="img-responsive border-top border-bottom border-right border-left p-xs image-file-letusan" src="#" style="display:none;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label class="w-xs btn btn-outline btn-default btn-file">
                                                        <i class="fa fa-upload"></i>
                                                        <span class="label-file-letusan">Browse </span>
                                                        <input class="file-letusan" accept="image/jpeg" type="file" name="foto_letusan" style="display: none;">
                                                    </label>
                                                    @if( $errors->has('foto_letusan'))
                                                    <label class="error" for="foto_letusan">{{ ucfirst($errors->first('foto')) }}</label>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="hr-line-dashed jenis-apg teramati" style="display: {{ old('jenis') == 'apg' ? 'block' :'none'}};"></div>

                                {{-- Awan Panas Guguran --}}
                                <div class="row jenis-apg teramati" style="display: {{ old('jenis') == 'apg' ? 'block' :'none'}};">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-cloud-download fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Masukkan parameter pemantauan visual <b>Awan Panas Guguran.</b>
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        <div class="teramati" style="display: {{ old('visibility') == '0' ? 'none' :'block'}};">
                                            {{-- Jarak Guguran --}}
                                            <div class="form-group col-sm-12">
                                                <label>Jarak Guguran</label>

                                                <div class="input-group">
                                                    <input placeholder="Maksimal 20,0000 meter" name="distance" class="form-control" type="numeric" value="{{ empty(old('distance')) ? '' : old('distance') }}">
                                                    <span class="input-group-addon h-bg-red">meter, dari puncak</span>
                                                </div>
                                                @if( $errors->has('distance'))
                                                <label class="error" for="distance">{{ ucfirst($errors->first('distance')) }}</label>
                                                @endif
                                            </div>

                                            {{-- Arah Guguran --}}
                                            <div class="form-group col-sm-12">
                                                <label>Arah Guguran mengarah ke</label>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Utara" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Utara',old('arah_guguran'))) ? 'checked' : ''}}> Utara </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Timur Laut" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Timur Laut',old('arah_guguran'))) ? 'checked' : ''}}> Timur Laut </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Timur" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Timur',old('arah_guguran'))) ? 'checked' : ''}}> Timur </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Tenggara" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Tenggara',old('arah_guguran'))) ? 'checked' : ''}}> Tenggara </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Selatan" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Selatan',old('arah_guguran'))) ? 'checked' : ''}}> Selatan </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Barat Daya" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Barat Daya',old('arah_guguran'))) ? 'checked' : ''}}> Barat Daya </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Barat" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Barat',old('arah_guguran'))) ? 'checked' : ''}}> Barat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_guguran[]" value="Barat Laut" type="checkbox" class="i-checks arah-abu-guguran" {{ (is_array(old('arah_guguran')) AND in_array('Barat Laut',old('arah_guguran'))) ? 'checked' : ''}}> Barat Laut </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if( $errors->has('arah_guguran'))
                                                <label class="error" for="arah_guguran">{{ ucfirst($errors->first('arah_guguran')) }}</label>
                                                @endif
                                                @if($errors->has('arah_guguran.*'))
                                                @foreach($errors->get('arah_guguran.*') as $error)
                                                    <label class="error" for="arah_guguran">{{  $error[0] }}</label>
                                                    @break
                                                @endforeach
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                {{-- Seismik --}}
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-graph3 fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Masukkan nilai amplitudo maksimum dan lama durasi kejadian erupsi
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        {{-- Rekaman Seismik --}}
                                        <div class="form-group col-sm-12">
                                            <label>Amplitudo Maksimum</label>
                                            <div class="input-group">
                                                <span class="input-group-addon" style="min-width: 100px;">Amplitudo</span>
                                                <input id="amplitudo" name="amplitudo" class="form-control" type="text" value="{{ empty(old('amplitudo')) ? '' : old('amplitudo') }}">
                                                <span class="input-group-addon" style="min-width: 75px;">mm</span>
                                            </div>
                                            @if( $errors->has('amplitudo'))
                                            <label class="error" for="amplitudo">{{ ucfirst($errors->first('amplitudo')) }}</label>
                                            @endif
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label>Durasi</label>
                                            <div class="input-group">
                                                <span class="input-group-addon" style="min-width: 100px;">Durasi</span>
                                                <input name="durasi" class="form-control" type="text" value="{{ empty(old('durasi')) ? '' : old('durasi') }}">
                                                <span class="input-group-addon" style="min-width: 75px;">detik</span>
                                            </div>
                                            <span class="help-block m-b-none">Jika <b>erupsi sedang berlangsung</b>, isi dengan waktu durasi saat pelaporan, tidak perlu menunggu kejadian erupsi selesai.</span>
                                            @if( $errors->has('durasi'))
                                            <label class="error" for="durasi">{{ ucfirst($errors->first('durasi')) }}</label>
                                            @endif
                                        </div>

                                        {{-- Seismometer --}}
                                        <div class="form-group col-sm-12">
                                            <label>Pilih stasiun</label>
                                            <div class="input-group">
                                                <select id="seismometer_id" class="form-control" name="seismometer_id">
                                                    <option value="9999">-- Pilih Stasiun --</option>
                                                    @foreach ($gadds as $gadd)
                                                        @foreach ($gadd->seismometers as $seismometer)
                                                        <option value="{{ $seismometer->id }}" {{ old('seismometer_id') == $seismometer->id ? 'selected' : ''}}>{{ $gadd->name }} - {{ $seismometer->scnl }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select><span class="input-group-btn">
                                                    <button id="load-seismometer" type="button" class="btn btn-primary">Load</button></span>
                                            </div>

                                            <a href="{{ route('chambers.seismometer.create') }}" class="btn btn-info m-t-md" target="_blank">Tambah Seismometer</a>
                                            @if( $errors->has('seismometer_id'))
                                            <label class="error" for="seismometer_id">{{ ucfirst($errors->first('seismometer_id')) }}</label>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                {{-- Rekomendasi --}}
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-ribbon fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Pilih rekomendasi dan informasi lainnya. Pilih opsi Draft VONA jika ingin membuat VONA sekaligus.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Rekomendasi --}}
                                        <div class="form-group col-sm-12">
                                            <label>Pilih Rekomendasi</label>

                                            @if( $errors->has('rekomendasi_text'))
                                            <label class="error" for="rekomendasi_text">{{ ucfirst($errors->first('rekomendasi_text')) }}</label>
                                            @endif

                                            <div class="refresh-rekomendasi">
                                                @foreach ($rekomendasis as $key => $rekomendasi)
                                                <div class="hpanel hblue rekomendasi-{{ $rekomendasi->id }} {{ $key != 0 ? 'collapsed' : ''}}">
                                                    <div class="panel-heading hbuilt">
                                                        <div class="panel-tools">
                                                        <a class="showhide-rekomendasi"><i class="fa {{ $key != 0 ? 'fa-chevron-circle-down' : 'fa-chevron-circle-up'}} fa-2x"></i></a>
                                                        </div>
                                                        <div class="p-xs" style="max-width: 50%;">
                                                            <div class="checkbox">
                                                                <label class="checkbox-inline">
                                                                <input name="rekomendasi" value="{{ $rekomendasi->id }}" type="radio" {{ $key == 0 ? 'checked' : '' }} required>
                                                                    Pilih Rekomendasi {{ $key+1 }} {!! $key == 0 ? '<span class="label label-magma">default</span>' : '' !!}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="p-sm">
                                                            <p style="line-height: 1.6;">{!! nl2br($rekomendasi->rekomendasi) !!}</p>
                                                        </div>
                                                    </div>
                                                    @role('Super Admin')
                                                    <div class="panel-footer text-right">
                                                        <div class="btn-group">
                                                            <button class="btn btn-danger delete-rekomendasi" type="button" rekomendasi-id="{{ $rekomendasi->id }}" value="{{ route('chambers.laporan.destroy.var.rekomendasi',['id' => $rekomendasi->id]) }}"><i class="fa fa-trash"></i> Delete</button>
                                                        </div>
                                                    </div>
                                                    @endrole
                                                </div>
                                                @endforeach
                                            </div>

                                            <div class="hpanel hred collapsed">
                                                <div class="panel-heading hbuilt">
                                                    <div class="panel-tools">
                                                        <a class="showhide-rekomendasi"><i class="fa fa-chevron-circle-down fa-2x"></i></a>
                                                    </div>
                                                    <div class="p-xs" style="max-width: 50%;">
                                                        <div class="checkbox">
                                                            <label class="checkbox-inline">
                                                            <input id="create-rekomendasi" name="rekomendasi" value="9999" type="radio" {{ count($rekomendasis)  ? '' : 'checked'}} required>
                                                                Buat Rekomendasi Baru
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <textarea id="rekomendasi_text" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="rekomendasi_text" class="form-control p-m" rows="4"></textarea>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Keterangan Lainnya --}}
                                        <div class="form-group col-sm-12">
                                            <label>Keterangan Lainnya (opsional)</label>
                                            <textarea placeholder="Kosongi jika tidak ada" name="lainnya"class="form-control" rows="3">{{ old('lainnya')}}</textarea>
                                        </div>

                                        {{-- Draft VONA --}}
                                        <div class="form-group col-sm-12">
                                            <label>Apakah mau dibuatkan Draft VONA?</label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="draft" value="1" type="radio" class="i-checks draft"
                                                            {{ old('draft') == '1' ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="draft" value="0" type="radio" class="i-checks draft"
                                                            {{ (old('draft') == '0' OR empty(old('draft'))) ? 'checked' : ''}}> Tidak </label>
                                                    <span class="help-block m-b-none">Pilih Opsi ini jika ingin memasukkan laporan letusan ke dalam <label><a
                                                                class="text-magma" href="{{ route('chambers.vona.draft')}}" target="_blank">Draft
                                                                VONA</a></label>. VONA akan dibuat berdasarkan data-data pengamatan di atas.</span>
                                                    @if( $errors->has('draft'))
                                                    <label class="error" for="draft">{{ ucfirst($errors->first('draft')) }}</label>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                        {{-- SMS Blast --}}
                                        <div class="form-group col-sm-12">
                                            <label>Apakah akan dikirimkan SMS Blast?</label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="is_blasted" value="1" type="radio" class="i-checks is-blasted"
                                                            {{ old('is_blasted') == '1' ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="is_blasted" value="0" type="radio" class="i-checks is-blasted"
                                                            {{ (old('is_blasted') == '0' OR empty(old('is_blasted'))) ? 'checked' : ''}}> Tidak </label>
                                                    <span class="help-block m-b-none">Pilih Opsi ini jika ingin mengirimkan notifikasi ke masyarakat sekitar terkait informasi erupsi yang dibuat. Jika ragu, pilih <b>Tidak.</b> Bisa dikirim nanti.</span>
                                                    @if( $errors->has('is_blasted'))
                                                    <label class="error" for="is_blasted">{{ ucfirst($errors->first('is_blasted')) }}</label>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Submit --}}
                                        <div class="form-group col-sm-12">
                                            <div class="hr-line-dashed"></div>
                                            <button class="btn btn-primary" type="submit">Buat Laporan</button>
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
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/moment/locale/id.js') }}"></script>
<script src="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function getRekomendasis($code, $status) {
    let $url = '{{ route("chambers.partial.rekomendasi") }}/'+$code+'/'+$status;

    $.ajax({
        url: $url,
        type: 'POST',
        success: function(data) {
            $('.refresh-rekomendasi').html(data);
        },
        error: function(data){
            console.log(data);
        }
    });
};

function getSeismometers($code, $id = 0) {
    let $url = '{{ route("chambers.partial.seismometer") }}/'+$code+'/'+$id;

    $.ajax({
        url: $url,
        type: 'POST',
        success: function(data) {
            let optional = '<option value="9999">-- Pilih Stasiun --</option>'+data
            $('#seismometer_id').html(optional);
        },
        error: function(data){
            console.log(data);
        }
    });
};

function showLetusan() {
    $('.jenis-apg').hide();
    $('.jenis-lts').show();
};

function hideLetusan() {
    if ($('#visibility').val() == '1') {
        $('.jenis-apg').show();
    }

    $('.jenis-lts').hide();
};

$(document).ready(function () {
    let $code = $('#code').val();
    let $status = $('#status').val();
    getRekomendasis($code, $status);
    getSeismometers($code, {{ empty(old('seismometer_id')) ? 0 : old('seismometer_id') }});

    $('#datepicker').datetimepicker({
        minDate: '2015-05-01',
        maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
        sideBySide: true,
        locale: 'id',
        format: 'YYYY-MM-DD HH:mm:ss',
    });

    $('#jenis').on('change',function(){
        let $val = $(this).val();
        $val == 'lts' ? showLetusan() : hideLetusan();
    });

    $('#visibility').on('change',function(){
        let $val = $(this).val();
        $val == '0' ? $('.teramati').hide() : $('.teramati').show();
    });

    $('input.file-letusan').on('change', function(e) {
        let input = $(this),
            label = input.val()
                        .replace(/\\/g, '/')
                        .replace(/.*\//, ''),
            reader = new FileReader();

        $('.label-file-letusan').html(label);
        reader.onload = function (e) {
            $('.image-file-letusan')
                .show()
                .attr('src',e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    });

    $('#code').on('change', function() {
        let $code = $('#code').val();
        let $status = $('#status').val();
        getRekomendasis($code, $status);
        getSeismometers($code);
    });

    $('#status').on('change', function() {
        let $code = $('#code').val();
        let $status = $('#status').val();
        getRekomendasis($code, $status);
    });

    $('#load-seismometer').on('click', function() {
        let $code = $('#code').val();
        getSeismometers($code);
    });
});

$(document).on('click','.showhide-rekomendasi', function (event) {
    event.preventDefault();
    let hpanel = $(this).closest('div.hpanel');
    let icon = $(this).find('i:first');
    let body = hpanel.find('div.panel-body');
    let footer = hpanel.find('div.panel-footer');
    body.slideToggle(300);
    footer.slideToggle(200);

    // Toggle icon from up to down
    icon.toggleClass('fa-chevron-circle-up').toggleClass('fa-chevron-circle-down');
    hpanel.toggleClass('').toggleClass('panel-collapse');
    setTimeout(function () {
        hpanel.resize();
        hpanel.find('[id^=map-]').resize();
    }, 50);
});

@role('Super Admin')
$('.delete-rekomendasi').on('click', function(e) {
    let $url = $(this).val();
    let $id = $(this).attr('rekomendasi-id');
    let $rekomendasi = $('.rekomendasi-'+$id);

    swal({
        title: "Anda yakin?",
        text: "Data yang telah dihapus tidak bisa dikembalikan",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, hapus!",
        cancelButtonText: "Gak jadi deh!",
        closeOnConfirm: false,
        closeOnCancel: true },
    function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: $url,
                type: 'POST',
                success: function(data) {
                    if (data.success){
                        swal("Berhasil!", data.message, "success");
                        $rekomendasi.remove();
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
    });

});
@endrole
</script>
@endsection