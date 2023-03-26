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
<div class="normalheader content-boxed">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-attention fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Buat Laporan Letusan</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
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

            <p class="m-b-lg tx-16">
                Gunakan menu ini untuk membuat Informasi Laporan Letusan Gunung Api (Volcano Eruption Notice). Laporan yang dibuat pada menu ini akan dipublikasikan di MAGMA v1 dan langsung dibuatkan <b>VONA</b>. VONA yang dibuat tidak langsung dikirim, akan tetapi masih berbentuk Draft.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed no-top-padding">
    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.letusan.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Informasi Umum --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-sm">

                                <div class="col-lg-4 text-center">
                                    <i class="pe-7s-ribbon fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Informasi Umum</strong>
                                    </p>
                                    <p>
                                        Pilih nama gunung api, jenis letusan, dan waktu kejadian letusan dalam waktu lokal.
                                    </p>
                                </div>

                                <div class="col-lg-8">
                                    {{-- Gunung Api --}}
                                    <div class="row">
                                        <div class="form-group col-lg-12">
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
                                    </div>

                                    {{-- Status --}}
                                    <div class="row">
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
                                    </div>

                                    {{-- Waktu Letusan --}}
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Waktu Letusan (Waktu Lokal)</label>
                                            <input name="datetime" id="datetime" class="form-control" type="text" value="{{ empty(old('datetime')) ? now()->format('Y-m-d H:i:s') : old('datetime') }}">
                                            @if( $errors->has('datetime'))
                                            <label class="error" for="datetime">{{ ucfirst($errors->first('datetime')) }}</label>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Pilih Jenis letusan --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Jenis</label>
                                            <select id="type" class="form-control" name="type">
                                                <option value="lts" {{ old('type') == 'lts' ? 'selected' : ''}}>Erupsi/Letusan Kolom</option>
                                                <option value="apg" {{ old('type') == 'apg' ? 'selected' : ''}}>Awan Panas Guguran</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Visual erupsi teramati --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Apakah visual letusan teramati?</label>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="is_visible" value="1" type="radio" class="i-checks is_visible"
                                                            {{ (old('is_visible') == '1' OR empty(old('is_visible'))) ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline is_visible"><input name="is_visible" value="0" type="radio" class="i-checks is_visible"
                                                            {{ old('is_visible') == '0' ? 'checked' : ''}}> Tidak </label>

                                                    @if( $errors->has('is_visible'))
                                                    <label class="error" for="is_visible">{{ ucfirst($errors->first('is_visible')) }}</label>
                                                    @endif
                                                </div>
                                            </div>

                                            @if( $errors->has('is_visible'))
                                            <label class="error" for="is_visible">{{ ucfirst($errors->first('is_visible')) }}</label>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Eurpsi apakah sedang berlangsung --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Apakah erupsi sedang berlangsung?</label>
                                            <span class="help-block m-t-none">Pilih opsi ini jika pada saat laporan dibuat, erupsi masih berlangsung.</span>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="is_continuing" value="1" type="radio" class="i-checks is_continuing"
                                                            {{ old('is_continuing') == '1' ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="is_continuing" value="0" type="radio" class="i-checks is_continuing"
                                                            {{ (old('is_continuing') == '0' OR empty(old('is_continuing'))) ? 'checked' : ''}}> Tidak </label>

                                                    @if( $errors->has('is_continuing'))
                                                    <label class="error" for="is_continuing">{{ ucfirst($errors->first('is_continuing')) }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Visual --}}
        <div class="row is-visible" style="display: {{ old('is_visible') == '0' ? 'none' :'block'}};">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-sm">

                                <div class="col-lg-4 text-center">
                                    <i class="pe-7s-cloud-upload fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Informasi Visual</strong>
                                    </p>
                                    <p>
                                        Masukkan paramater visual yang dibutuhkan untuk melengkapi informasi letusan
                                    </p>
                                </div>

                                <div class="col-lg-8">

                                    {{-- Tinggi Letusan --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Tinggi Abu Letusan</label>
                                            <span class="help-block m-t-none">Untuk <b>Awan Panas Guguran</b> masukkan nilai 0 jika tinggi abu akibat APG masih di bawah puncak. <b>Maksimal</b> 20.000 meter</span>
                                            <div class="input-group">
                                                <input placeholder="Maksimal 20000 meter" name="ash_height" class="form-control" type="text" value="{{ empty(old('ash_height')) ? 0 : old('ash_height') }}">
                                                <span class="input-group-addon h-bg-red">meter, di atas puncak</span>
                                            </div>

                                            @if( $errors->has('ash_height'))
                                            <label class="error" for="ash_height">{{ ucfirst($errors->first('ash_height')) }}</label>
                                            @endif

                                        </div>
                                    </div>

                                    {{-- Warna Abu --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Warna Abu</label>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="ash_color[]" value="Putih" type="checkbox" class="i-checks wasap" {{ (is_array(old('ash_color')) AND in_array('Putih',old('ash_color'))) ? 'checked' : ''}}> Putih </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="ash_color[]" value="Kelabu" type="checkbox" class="i-checks wasap" {{ (is_array(old('ash_color')) AND in_array('Kelabu',old('ash_color'))) ? 'checked' : ''}}> Kelabu </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="ash_color[]" value="Coklat" type="checkbox" class="i-checks wasap" {{ (is_array(old('ash_color')) AND in_array('Coklat',old('ash_color'))) ? 'checked' : ''}}> Coklat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="ash_color[]" value="Hitam" type="checkbox" class="i-checks wasap" {{ (is_array(old('ash_color')) AND in_array('Hitam',old('ash_color'))) ? 'checked' : ''}}> Hitam </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($errors->has('ash_color'))
                                                    <label class="error" for="ash_color">{{  ucfirst($errors->first('ash_color')) }}</label>
                                                @endif

                                                @if($errors->has('ash_color.*'))
                                                @foreach($errors->get('ash_color.*') as $error)
                                                    <label class="error" for="ash_color">{{ $error[0] }}</label>
                                                    @break
                                                @endforeach
                                                @endif
                                        </div>
                                    </div>

                                    {{-- Intensitas Abu Letusan --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Intensitas</label>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label class="checkbox-inline"><input name="ash_intensity[]" value="Tipis" type="checkbox" class="i-checks ash_intensity" {{ (is_array(old('ash_intensity')) AND in_array('Tipis',old('ash_intensity'))) ? 'checked' : ''}}> Tipis </label>
                                                    <label class="checkbox-inline"><input name="ash_intensity[]" value="Sedang" type="checkbox" class="i-checks ash_intensity" {{ (is_array(old('ash_intensity')) AND in_array('Sedang',old('ash_intensity'))) ? 'checked' : ''}}> Sedang </label>
                                                    <label class="checkbox-inline"><input name="ash_intensity[]" value="Tebal" type="checkbox" class="i-checks ash_intensity" {{ (is_array(old('ash_intensity')) AND in_array('Tebal',old('ash_intensity'))) ? 'checked' : ''}}> Tebal </label>
                                                </div>
                                            </div>

                                            @if( $errors->has('ash_intensity'))
                                            <label class="error" for="ash_intensity">{{ ucfirst($errors->first('ash_intensity')) }}</label>
                                            @endif

                                            @if($errors->has('ash_intensity.*'))
                                            @foreach($errors->get('ash_intensity.*') as $error)
                                                <label class="error" for="ash_intensity">{{  $error[0] }}</label>
                                                @break
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Arah Abu --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Abu Mengarah ke</label>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Utara" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Utara',old('ash_directions'))) ? 'checked' : ''}}> Utara </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Timur Laut" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Timur Laut',old('ash_directions'))) ? 'checked' : ''}}> Timur Laut </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Timur" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Timur',old('ash_directions'))) ? 'checked' : ''}}> Timur </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Tenggara" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Tenggara',old('ash_directions'))) ? 'checked' : ''}}> Tenggara </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Selatan" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Selatan',old('ash_directions'))) ? 'checked' : ''}}> Selatan </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Barat Daya" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Barat Daya',old('ash_directions'))) ? 'checked' : ''}}> Barat Daya </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Barat" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Barat',old('ash_directions'))) ? 'checked' : ''}}> Barat </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="ash_directions[]" value="Barat Laut" type="checkbox" class="i-checks arah-abu" {{ (is_array(old('ash_directions')) AND in_array('Barat Laut',old('ash_directions'))) ? 'checked' : ''}}> Barat Laut </label>
                                                    </div>
                                                </div>
                                            </div>

                                            @if( $errors->has('ash_directions'))
                                            <label class="error" for="ash_directions">{{ ucfirst($errors->first('ash_directions')) }}</label>
                                            @endif

                                            @if($errors->has('ash_directions.*'))
                                            @foreach($errors->get('ash_directions.*') as $error)
                                                <label class="error" for="ash_directions">{{  $error[0] }}</label>
                                                @break
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Foto</label>
                                           <span class="help-block m-t-none">Format yang diterima adalah format gambar. Per file <strong>maksimal 3MB</strong></span>
                                            <div class="form-photos m-b">
                                                <div class="input-group btn-file">
                                                    <span class="input-group-btn">
                                                        <label class="btn btn-primary">
                                                            <i class="fa fa-upload"></i>
                                                            <span class="label-file">Browse </span>
                                                            <input id="file_" accept="image/jpeg" class="file" name="photos[]" type="file" style="display: none;">
                                                        </label>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-danger clear-file"><i class="fa fa-trash"></i></button>
                                                    </span>
                                                    <input class="form-control overviews-files" name="photos_description[]" type="text" placeholder="(Optional) Keterangan file" value="">
                                                    <span class="input-group-btn add-remove-button">
                                                        <button type="button" class="btn btn-primary add-file">+</button>
                                                    </span>

                                                </div>
                                                <span class="span-file"></span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Awan Panas Guguran --}}
                                    <div class="is-apg" style="display: {{ old('jenis') == 'apg' ? 'block' :'none'}};">

                                        {{-- Jarak Guguran --}}
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label>Jarak Guguran</label>

                                                <div class="input-group">
                                                    <input placeholder="Maksimal 20000 meter" name="distance" class="form-control" type="numeric" value="{{ empty(old('distance')) ? 0 : old('distance') }}">
                                                    <span class="input-group-addon h-bg-red">meter, dari puncak</span>
                                                </div>

                                                @if( $errors->has('distance'))
                                                <label class="error" for="distance">{{ ucfirst($errors->first('distance')) }}</label>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Arah Guguran --}}
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label>Guguran mengarah ke</label>

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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Kegempaan --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-sm">
                                <div class="col-lg-4 text-center">
                                    <i class="pe-7s-graph3 fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Informasi Kegempaan</strong>
                                    </p>
                                    <p>
                                        Masukkan parameter kegempaan yang meliputi stasiun seismik yang merekam, durasi dan amplitudo letusan.
                                    </p>
                                </div>

                                <div class="col-lg-8">

                                    {{-- Stasiun Seismik --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Pilih Stasiun</label>
                                            <span class="help-block m-b-none">Pilih stasiun seismik yang merekam letusan. Jika tidak ada, bisa ditambahkan menggunakan tombol <b>Tambah Seismometer</b></span>
                                            <div class="input-group">
                                                <select id="seismometer_id" class="form-control" name="seismometer_id">
                                                    <option value="9999">-- Pilih Stasiun --</option>
                                                    @foreach ($gadds as $gadd)
                                                        @foreach ($gadd->seismometers as $seismometer)
                                                        <option value="{{ $seismometer->id }}" {{ old('seismometer_id') == $seismometer->id ? 'selected' : ''}}>{{ $gadd->name }} - {{ $seismometer->scnl }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select><span class="input-group-btn">
                                                    <button id="load-seismometer" type="button" class="btn btn-primary">Refresh</button>
                                                    <a href="{{ route('chambers.seismometer.create') }}" class="btn btn-info" target="_blank">+ Seismometer</a>
                                                </span>
                                            </div>

                                            @if( $errors->has('seismometer_id'))
                                            <label class="error" for="seismometer_id">{{ ucfirst($errors->first('seismometer_id')) }}</label>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Erupsi telah selesai --}}
                                    <div class="is-continuing" style="display: {{ old('is_continuing') == '0' ? 'none' :'block'}};">

                                        {{-- Amplitude --}}
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label>Amplitudo Maksimum</label>
                                                <span class="help-block m-t-none">Maksimal nilai amplitudo adalah 240 mm.</span>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 100px;">Amplitudo</span>
                                                    <input id="amplitude" type="number"  name="amplitude" min="1" max="240" class="form-control" type="text" value="{{ empty(old('amplitude')) ? 1 : old('amplitude') }}">
                                                    <span class="input-group-addon" style="min-width: 75px;">mm</span>
                                                </div>
                                                @if( $errors->has('amplitude'))
                                                <label class="error" for="amplitude">{{ ucfirst($errors->first('amplitude')) }}</label>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Durasi --}}
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label>Durasi</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 100px;">Durasi</span>
                                                    <input name="duration" type="number" min="1" class="form-control" type="text" value="{{ empty(old('duration')) ? 0 : old('duration') }}">
                                                    <span class="input-group-addon" style="min-width: 75px;">detik</span>
                                                </div>

                                                @if( $errors->has('duration'))
                                                <label class="error" for="duration">{{ ucfirst($errors->first('duration')) }}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Lainnya --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-sm">

                                <div class="col-lg-4 text-center">
                                    <i class="pe-7s-ticket fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Informasi Lainnya</strong>
                                    </p>
                                    <p>
                                        Informasi lainnya yang tidak masuk dalam parameter letusan.
                                    </p>
                                </div>

                                <div class="col-lg-8">
                                    {{-- Keterangan Lainnya --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Keterangan Lainnya (opsional)</label>
                                            <textarea placeholder="Kosongi jika tidak ada" name="lainnya"class="form-control" rows="6">{{ old('lainnya')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekomendasi --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-sm">

                                <div class="col-lg-4 text-center">
                                    <i class="pe-7s-ribbon fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Rekomendasi Gunung Api</strong>
                                    </p>
                                    <p>
                                        Pilih rekomendasi dan informasi lainnya. Pilih opsi Draft VONA jika ingin membuat VONA sekaligus.
                                    </p>
                                </div>

                                <div class="col-lg-8">
                                    {{-- Rekomendasi --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Pilih Rekomendasi</label>

                                            @if( $errors->has('rekomendasi_baru'))
                                            <label class="error" for="rekomendasi_baru">{{ ucfirst($errors->first('rekomendasi_baru')) }}</label>
                                            @endif

                                            <div class="refresh-rekomendasi">
                                                @foreach ($recomendations as $key => $recomendation)
                                                <div class="hpanel hblue rekomendasi-{{ $recomendation->id }} {{ $key != 0 ? 'collapsed' : ''}}">
                                                    <div class="panel-heading hbuilt">
                                                        <div class="panel-tools">
                                                        <a class="showhide-rekomendasi"><i class="fa {{ $key != 0 ? 'fa-chevron-circle-down' : 'fa-chevron-circle-up'}} fa-2x"></i></a>
                                                        </div>
                                                        <div class="p-xs" style="max-width: 50%;">
                                                            <div class="checkbox">
                                                                <label class="checkbox-inline">
                                                                <input name="rekomendasi" value="{{ $recomendation->id }}" type="radio" {{ $key == 0 ? 'checked' : '' }} required>
                                                                    Pilih Rekomendasi {{ $key+1 }} {!! $key == 0 ? '<span class="label label-magma">default</span>' : '' !!}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="p-sm">
                                                            <p style="line-height: 1.6;">{!! nl2br($recomendation->rekomendasi) !!}</p>
                                                        </div>
                                                    </div>

                                                    @role('Super Admin')
                                                    <div class="panel-footer text-right">
                                                        <div class="btn-group">
                                                            <button class="btn btn-danger delete-rekomendasi" type="button" rekomendasi-id="{{ $recomendation->id }}" value="{{ route('chambers.laporan.destroy.var.rekomendasi',['id' => $recomendation->id]) }}"><i class="fa fa-trash"></i> Delete</button>
                                                        </div>
                                                    </div>
                                                    @endrole
                                                </div>
                                                @endforeach
                                            </div>

                                            <div class="hpanel hred">
                                                <div class="panel-heading hbuilt">
                                                    <div class="panel-tools">
                                                        <a class="showhide-rekomendasi"><i class="fa fa-chevron-circle-down fa-2x"></i></a>
                                                    </div>
                                                    <div class="p-xs" style="max-width: 50%;">
                                                        <div class="checkbox">
                                                            <label class="checkbox-inline">
                                                            <input id="create-rekomendasi" name="rekomendasi" value="0" type="radio" {{ count($recomendations)  ? '' : 'checked'}} required>
                                                                Buat Rekomendasi Baru
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <textarea id="rekomendasi_baru" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="rekomendasi_baru" class="form-control p-m" rows="6"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Diseminasi Informasi --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-sm">
                                <div class="col-lg-4 text-center">
                                    <i class="pe-7s-signal fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Diseminasi Informasi Letusan</strong>
                                    </p>
                                    <p>
                                        Tentukan media untuk diseminasi informasi letusan gunung api
                                    </p>
                                </div>

                                <div class="col-lg-8">

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <div class="alert alert-info">
                                                <strong>VONA</strong> akan secara otomatis dibuat pada saat VEN dibuat. Jadi tidak perlu membuat VONA lagi.
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SMS Blast --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Apakah akan dikirimkan SMS Blast?</label>
                                            <span class="help-block m-t-none">Pilih Opsi ini jika ingin mengirimkan notifikasi ke masyarakat sekitar terkait informasi erupsi yang dibuat. Jika ragu, pilih <b>Tidak.</b> Bisa dikirim nanti.</span>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="is_blasted" value="1" type="radio" class="i-checks is-blasted"
                                                            {{ old('is_blasted') == '1' ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="is_blasted" value="0" type="radio" class="i-checks is-blasted"
                                                            {{ (old('is_blasted') == '0' OR empty(old('is_blasted'))) ? 'checked' : ''}}> Tidak </label>

                                                    @if( $errors->has('is_blasted'))
                                                    <label class="error" for="is_blasted">{{ ucfirst($errors->first('is_blasted')) }}</label>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- TV Digital --}}
                                    <div class="row m-t">
                                        <div class="form-group col-sm-12">
                                            <label>Apakah akan dikirimkan pada TV Digital?</label>
                                            <span class="help-block m-t-none">Pilih Opsi ini jika ingin mengirimkan informasi peringatan dini melalui <b>TV Digital</b>. Jika ragu, pilih <b>Tidak.</b> Bisa dikirim nanti.</span>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="is_broadcasted" value="1" type="radio" class="i-checks is-blasted"
                                                            {{ old('is_broadcasted') == '1' ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="is_broadcasted" value="0" type="radio" class="i-checks is-blasted"
                                                            {{ (old('is_broadcasted') == '0' OR empty(old('is_broadcasted'))) ? 'checked' : ''}}> Tidak </label>
                                                    @if( $errors->has('is_broadcasted'))
                                                    <label class="error" for="is_broadcasted">{{ ucfirst($errors->first('is_broadcasted')) }}</label>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <div class="hr-line-dashed"></div>
                                            <button class="btn btn-primary" type="submit">Kirim Laporan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

function getRecomendations($code, $status) {
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
            let optional = '<option>-- Pilih Stasiun --</option>'+data
            $('#seismometer_id').html(optional);
        },
        error: function(data){
            console.log(data);
        }
    });
};

$(document).ready(function () {
    function validateSize(input, limit = 3) {
        const fileSize = input[0].files[0].size / 1024 / 1024;

        return (fileSize > limit) ? false : true;
    };

    function resetLabelInputFile(input) {
        input.val('');
        input.siblings('.label-file').html('Browse');
        input.parents('.input-group').siblings('.span-file').html('');
    };

    function replaceLabelInputFile(input, label) {
        input.siblings('.label-file').html('Ganti');
        input.parents('.input-group').siblings('.span-file').html('<b>File:</b> '+label);
    };

    function alertOnFileExceeds(input, limit) {
        resetLabelInputFile(input);
        alert('File berukuran lebih besar dari '+limit+' MB');
    };

    function resetValueAfterClear(element) {
        element.find('input').val('');
        element.find('.label-file').html('Browse');
        element.find('.span-file').html('');
    };


    $('#code').on('change', function() {
        let $code = $('#code').val();
        let $status = $('#status').val();
        getRecomendations($code, $status);
        getSeismometers($code);
    });

    $('#status').on('change', function() {
        let $code = $('#code').val();
        let $status = $('#status').val();
        getRecomendations($code, $status);
    });

    $('#datetime').datetimepicker({
        minDate: '2015-05-01',
        maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
        sideBySide: true,
        locale: 'id',
        format: 'YYYY-MM-DD HH:mm:ss',
    });

    $('#type').on('change',function(){
        let $val = $(this).val();
        console.log($val);
        $val == 'lts' ? $('.is-apg').hide() : $('.is-apg').show();
    });

    $('input.is_visible').on('ifChanged', function(){
        let $val = $(this).val();
        $val == '0' ? $('.is-visible').hide() : $('.is-visible').show();
    });

    $('input.is_continuing').on('ifChanged', function(){
        let $val = $(this).val();
        $val == '1' ? $('.is-continuing').hide() : $('.is-continuing').show();
    });

    $('#load-seismometer').on('click', function() {
        let $code = $('#code').val();
        getSeismometers($code);
    });

    $('input.file').on('change', function(e) {
        const input = $(this);
        const label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        const fileType = input[0].files[0].type;
        const limit = (fileType === 'application/pdf') ? 5 : 3;

        validateSize($(this), limit) ?
            replaceLabelInputFile(input, label) :
            alertOnFileExceeds(input, limit);
    });

    $('.clear-file').on('click', function(e) {
        const element = $(this).closest('.form-photos');
        resetValueAfterClear(element);
    });

    $('.add-file').on('click', function() {
        const element = $(this).closest('.form-photos');
        const $clone = element.clone(true);
        const $removePlus  = $clone.find('.add-remove-button').remove();
        const $remove = '<span class="input-group-btn"><button type="button" class="btn btn-danger remove-file">-</button></span>';
        const $addRemove = $clone.find('.overviews-files').after($remove);

        resetValueAfterClear($clone);

        element.after($clone);
    });

    $('form').on('click','.remove-file',function(){
        $(this).closest('.form-photos').remove();
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
$(document).on('click', '.delete-rekomendasi', function(e) {
    let $url = $(this).val();
    let $id = $(this).attr('rekomendasi-id');
    let $recomendation = $('.rekomendasi-'+$id);

    console.log($url);

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
                        $recomendation.remove();
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