@extends('layouts.default')

@section('title')
Edit VONA {{ $vona->gunungapi->name.' '.$vona->issued }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet"
    href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
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
                        <a href="{{ route('chambers.vona.index') }}">VONA</a>
                    </li>
                    <li class="active">
                        <span>{{ $vona->gunungapi->name.' '.$vona->issued }}</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Edit laporan laporan VONA
            </h2>
            <small><a href="{{ route('chambers.vona.show', $vona) }}">{{ $vona->gunungapi->name.' '.$vona->issued }}</a></small>
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

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Form VONA
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

                    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.vona.update', $vona) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="tab-content">
                            <div class="p-m tab-pane active">

                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Pilih jenis laporan VONA dan gunung api yang akan digunakan dalam pelaporan.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Pilih Jenis letusan --}}
                                        <div class="form-group col-sm-12">
                                            <label>Jenis</label>
                                            <select id="jenis" class="form-control" name="type">
                                                <option value="real" {{ $vona->type == 'real' ? 'selected' : '' }}>Real
                                                </option>
                                                <option value="exercise" {{ $vona->type == 'exercise' ? 'selected' : ''
                                                    }}>Exercise</option>
                                            </select>
                                        </div>

                                        {{-- Nama Gunung Api --}}
                                        <div class="form-group col-sm-12">
                                            <label>Gunung Api</label>
                                            <select id="code" class="form-control" name="code">
                                                @foreach($gadds as $gadd)
                                                <option value="{{ $gadd->code }}" {{ $vona->code_id == $gadd->code ?
                                                    'selected' : ''}}>{{ $gadd->name }}</option>
                                                @endforeach
                                            </select>
                                            @if( $errors->has('code'))
                                            <label class="error" for="code">{{ ucfirst($errors->first('code'))
                                                }}</label>
                                            @endif
                                        </div>

                                        {{-- Color code --}}
                                        <div class="form-group col-sm-12">
                                            <label>Color Code</label>
                                            <select id="color" class="form-control" name="color">
                                                <option value="red" {{ strtolower($vona->current_code) == 'red' ? 'selected' : '' }}>RED
                                                </option>
                                                <option value="orange" {{ strtolower($vona->current_code) == 'orange' ? 'selected' : '' }}>
                                                    ORANGE</option>
                                                <option value="yellow" {{ strtolower($vona->current_code) == 'yellow' ? 'selected' : '' }}>
                                                    YELLOW</option>
                                                <option value="green" {{ strtolower($vona->current_code) == 'green' ? 'selected' : '' }}>
                                                    GREEN</option>
                                                <option value="auto" {{ strtolower($vona->current_code) == 'auto' ? 'selected' : '' }}>
                                                    Otomatis oleh MAGMA</option>
                                            </select>
                                            <span class="help-block m-b-none">Pilihan warna <b>Otomatis</b> akan
                                                ditentukan berdasarkan tinggi abu teramati atau ada tidaknya rekaman
                                                gempa letusan.</span>
                                            @if( $errors->has('color'))
                                            <label class="error" for="color">{{ ucfirst($errors->first('color'))
                                                }}</label>
                                            @endif
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
                                            <label>Visual Kolom Letusan</label>
                                            <select id="visibility" class="form-control" name="visibility">
                                                <option value="1" {{ $vona->is_visible == '1' ? 'selected' : '' }}>
                                                    Teramati</option>
                                                <option value="0" {{ $vona->is_visible == '0' ? 'selected' : '' }}>Tidak
                                                    Teramati</option>
                                            </select>
                                            @if( $errors->has('visibility'))
                                            <label class="error" for="visibility">{{
                                                ucfirst($errors->first('visibility')) }}</label>
                                            @endif
                                        </div>

                                        <div class="green teramati"
                                            style="display: {{ ($vona->current_code == 'GREEN' or $vona->is_visible == '0') ? 'none' :'block'}};">
                                            {{-- Tinggi Letusan --}}
                                            <div class="form-group col-sm-12">
                                                <label>Tinggi Abu yang teramati</label>
                                                <div class="input-group">
                                                    <input placeholder="Maksimal 20000 meter" name="height"
                                                        class="form-control" type="text"
                                                        value="{{ $vona->ash_height }}">
                                                    <span class="input-group-addon h-bg-red">meter, di atas
                                                        puncak</span>
                                                </div>

                                                @if( $errors->has('height'))
                                                <label class="error" for="height">{{ ucfirst($errors->first('height'))
                                                    }}</label>
                                                @endif

                                                <div class="alert alert-info m-t-sm">
                                                    Tinggi VONA akan dikalkulasi ulang oleh MAGMA dengan <b>menjumlahkan
                                                        tinggi gunung api dengan tinggi kolom abu letusan</b>.
                                                    <b>Jika total tinggi abu di atas 6000 mdpl</b>, maka color code VONA
                                                    akan <b>dirubah secara otomatis oleh MAGMA menjadi RED.</b>
                                                </div>

                                            </div>

                                            {{-- Warna Abu --}}
                                            <div class="form-group col-sm-12">
                                                <label>Warna abu</label>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Putih"
                                                                    type="checkbox" class="i-checks wasap" {{
                                                                    (is_array($vona->ash_color) AND
                                                                    in_array('Putih',$vona->ash_color)) ? 'checked'
                                                                    : '' }}> Putih </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Kelabu"
                                                                    type="checkbox" class="i-checks wasap" {{
                                                                    (is_array($vona->ash_color) AND
                                                                    in_array('Kelabu',$vona->ash_color)) ? 'checked'
                                                                    : '' }}> Kelabu </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Coklat"
                                                                    type="checkbox" class="i-checks wasap" {{
                                                                    (is_array($vona->ash_color) AND
                                                                    in_array('Coklat',$vona->ash_color)) ? 'checked'
                                                                    : '' }}> Coklat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Hitam"
                                                                    type="checkbox" class="i-checks wasap" {{
                                                                    (is_array($vona->ash_color) AND
                                                                    in_array('Hitam',$vona->ash_color)) ? 'checked'
                                                                    : '' }}> Hitam </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($errors->has('warna_asap'))
                                                <label class="error" for="warna_asap">{{
                                                    ucfirst($errors->first('warna_asap')) }}</label>
                                                @endif
                                                @if($errors->has('warna_asap.*'))
                                                @foreach($errors->get('warna_asap.*') as $error)
                                                <label class="error" for="warna_asap">{{ $error[0] }}</label>
                                                @break
                                                @endforeach
                                                @endif
                                            </div>

                                            {{-- Intensitas --}}
                                            <div class="form-group col-sm-12">
                                                <label>Intensitas</label>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label class="checkbox-inline"><input name="intensitas[]"
                                                                value="Tipis" type="checkbox"
                                                                class="i-checks intensitas" {{
                                                                (is_array($vona->ash_intensity) AND
                                                                in_array('Tipis',$vona->ash_intensity)) ? 'checked' : ''
                                                                }}> Tipis </label>
                                                        <label class="checkbox-inline"><input name="intensitas[]"
                                                                value="Sedang" type="checkbox"
                                                                class="i-checks intensitas" {{
                                                                (is_array($vona->ash_intensity) AND
                                                                in_array('Sedang',$vona->ash_intensity)) ? 'checked' : ''
                                                                }}> Sedang </label>
                                                        <label class="checkbox-inline"><input name="intensitas[]"
                                                                value="Tebal" type="checkbox"
                                                                class="i-checks intensitas" {{
                                                                (is_array($vona->ash_intensity) AND
                                                                in_array('Tebal',$vona->ash_intensity)) ? 'checked' : ''
                                                                }}> Tebal </label>
                                                    </div>
                                                </div>
                                                @if( $errors->has('intensitas'))
                                                <label class="error" for="intensitas">{{
                                                    ucfirst($errors->first('intensitas')) }}</label>
                                                @endif
                                                @if($errors->has('intensitas.*'))
                                                @foreach($errors->get('intensitas.*') as $error)
                                                <label class="error" for="intensitas">{{ $error[0] }}</label>
                                                @break
                                                @endforeach
                                                @endif
                                            </div>

                                            {{-- Arah Abu --}}
                                            <div class="form-group col-sm-12">
                                                <label>Abu di udara mengarah ke</label>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Utara"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND
                                                                    in_array('Utara',$vona->ash_directions)) ? 'checked' : ''
                                                                    }}> Utara </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Timur Laut"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND in_array('Timur Laut',$vona->ash_directions)) ? 'checked' : '' }}> Timur
                                                                Laut </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Timur"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND
                                                                    in_array('Timur',$vona->ash_directions)) ? 'checked' : ''
                                                                    }}> Timur </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Tenggara"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND
                                                                    in_array('Tenggara',$vona->ash_directions)) ? 'checked'
                                                                    : '' }}> Tenggara </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Selatan"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND
                                                                    in_array('Selatan',$vona->ash_directions)) ? 'checked'
                                                                    : '' }}> Selatan </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat Daya"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND in_array('Barat Daya',$vona->ash_directions)) ? 'checked' : '' }}> Barat Daya </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND
                                                                    in_array('Barat',$vona->ash_directions)) ? 'checked' : ''
                                                                    }}> Barat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat Laut"
                                                                    type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array($vona->ash_directions) AND in_array('Barat Laut',$vona->ash_directions)) ? 'checked' : '' }}> Barat
                                                                Laut </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if( $errors->has('arah_abu'))
                                                <label class="error" for="arah_abu">{{
                                                    ucfirst($errors->first('arah_abu')) }}</label>
                                                @endif
                                                @if($errors->has('arah_abu.*'))
                                                @foreach($errors->get('arah_abu.*') as $error)
                                                <label class="error" for="arah_abu">{{ $error[0] }}</label>
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

                                        {{-- Waktu Letusan --}}
                                        <div class="form-group col-sm-12">
                                            <label>Waktu Letusan/Waktu Laporan (Waktu Lokal)</label>
                                            <input name="date" id="datepicker" class="form-control" type="text"
                                                value="{{ $vona->issued_local->format('Y-m-d H:i:s') }}">
                                            <span class="help-block m-b-none">Gunakan <b>Waktu Laporan</b> jika tidak
                                                tidak terjadi letusan.</span>
                                            @if( $errors->has('date'))
                                            <label class="error" for="date">{{ ucfirst($errors->first('date'))
                                                }}</label>
                                            @endif
                                        </div>

                                        <div class="green"
                                            style="display: {{ strtolower($vona->current_code) == 'green' ? 'none' :'block'}};">

                                            {{-- Terekam Gempa Letusan --}}
                                            <div class="form-group col-sm-12">
                                                <hr>
                                                <label>Apakah gempa letusan terekam di seismogram?</label>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="checkbox-inline"><input
                                                                name="terjadi_gempa_letusan" value="1" type="radio"
                                                                class="i-checks draft" {{ $vona->amplitude > 0 ? 'checked' : ''
                                                                }}> Ya </label>
                                                        <label class="checkbox-inline"><input
                                                                name="terjadi_gempa_letusan" value="0" type="radio"
                                                                class="i-checks draft" {{
                                                                $vona->amplitude == 0 ? 'checked' : '' }}>
                                                            Tidak </label>
                                                        <span class="help-block m-b-none">Pilih opsi ini jika <b>gempa
                                                                letusan</b> terekam pada seismogram.</span>
                                                        @if( $errors->has('terjadi_gempa_letusan'))
                                                        <label class="error" for="terjadi_gempa_letusan">{{
                                                            ucfirst($errors->first('terjadi_gempa_letusan')) }}</label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Terekam tremor --}}
                                            <div class="form-group col-sm-12">
                                                <hr>
                                                <label>Apakah pada saat ini terekam tremor menerus?</label>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="checkbox-inline"><input name="terjadi_tremor"
                                                                value="1" type="radio" class="i-checks draft" {{
                                                                $vona->amplitude_tremor > 0 ? 'checked' : '' }}> Ya
                                                        </label>
                                                        <label class="checkbox-inline"><input name="terjadi_tremor"
                                                                value="0" type="radio" class="i-checks draft" {{
                                                                $vona->amplitude_tremor == 0 ? 'checked' : '' }}> Tidak
                                                        </label>
                                                        <span class="help-block m-b-none">Pilih Opsi ini jika ketika
                                                            laporan dibuat, masih terekam tremor menerus.</span>
                                                        @if( $errors->has('terjadi_tremor'))
                                                        <label class="error" for="terjadi_tremor">{{
                                                            ucfirst($errors->first('terjadi_tremor')) }}</label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="terjadi-gempa-letusan"
                                                style="display: {{ $vona->amplitude == 0 ? 'none' :'block'}};">

                                                {{-- Rekaman Seismik Gempa Letusan--}}
                                                <div class="form-group col-sm-12">
                                                    <hr>
                                                    <h2>Gempa Letusan</h2>

                                                    <label>Amplitudo Maksimum</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon"
                                                            style="min-width: 100px;">Amplitudo</span>
                                                        <input id="amplitudo" name="amplitudo" class="form-control"
                                                            type="text"
                                                            value="{{ $vona->amplitude }}">
                                                        <span class="input-group-addon"
                                                            style="min-width: 75px;">mm</span>
                                                    </div>

                                                    <span class="help-block"><b>Isi dengan 0</b> jika tidak terjadi
                                                        letusan. Range amplitudo 0 - 240mm.</span>

                                                    @if( $errors->has('amplitudo'))
                                                    <label class="error" for="amplitudo">{{
                                                        ucfirst($errors->first('amplitudo')) }}</label>
                                                    @endif
                                                </div>

                                                {{-- Durasi Gempa Letusan --}}
                                                <div class="form-group col-sm-12">
                                                    <label>Durasi Waktu Letusan</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon"
                                                            style="min-width: 100px;">Durasi</span>
                                                        <input name="durasi" class="form-control" type="text"
                                                            value="{{ $vona->duration }}">
                                                        <span class="input-group-addon"
                                                            style="min-width: 75px;">detik</span>
                                                    </div>

                                                    <span class="help-block">Jika <b>erupsi sedang berlangsung</b>,
                                                        durasi waktu letusan boleh dikosongi. </span>

                                                    @if( $errors->has('durasi'))
                                                    <label class="error" for="durasi">{{
                                                        ucfirst($errors->first('durasi')) }}</label>
                                                    @endif

                                                    <label>Apakah erupsi saat ini sedang berlangsung?</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label class="checkbox-inline"><input
                                                                    name="erupsi_berlangsung" value="1" type="radio"
                                                                    class="i-checks draft" {{
                                                                    $vona->is_continuing == '1' ? 'checked' : '' }}>
                                                                Ya </label>
                                                            <label class="checkbox-inline"><input
                                                                    name="erupsi_berlangsung" value="0" type="radio"
                                                                    class="i-checks draft" {{
                                                                    $vona->is_continuing == '0' ? 'checked' : ''
                                                                    }}> Tidak </label>
                                                            <span class="help-block m-b-none">Pilih Opsi ini jika ketika
                                                                laporan dibuat, erupsi masih berlangsung.</span>
                                                            @if( $errors->has('erupsi_berlangsung'))
                                                            <label class="error" for="erupsi_berlangsung">{{
                                                                ucfirst($errors->first('erupsi_berlangsung')) }}</label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Rekaman Seismik Tremor Menerus--}}
                                            <div class="form-group col-sm-12 terjadi-tremor"
                                                style="display: {{ $vona->amplitude_tremor ? 'block' :'none'}};">
                                                <hr>
                                                <h2>Tremor Menerus</h2>

                                                <label>Amplitudo Maksimum</label>

                                                <div class="input-group">
                                                    <span class="input-group-addon"
                                                        style="min-width: 100px;">Amplitudo</span>
                                                    <input id="amplitudo_tremor" name="amplitudo_tremor"
                                                        class="form-control" type="text"
                                                        value="{{ $vona->amplitude_tremor }}">
                                                    <span class="input-group-addon" style="min-width: 75px;">mm</span>
                                                </div>

                                                <span class="help-block"><b>Isi dengan 0</b> jika tidak ada rekaman
                                                    tremor. Range amplitudo 0 - 240mm.</span>

                                                @if( $errors->has('amplitudo_tremor'))
                                                <label class="error" for="amplitudo_tremor">{{
                                                    ucfirst($errors->first('amplitudo_tremor')) }}</label>
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                {{-- Remarks --}}
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-ticket fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Tidak wajib. Tambahkan catatan lainnya jika diperlukan. <b>Wajib dalam
                                                bahasa inggris</b>.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Submit --}}
                                        <div class="form-group col-sm-12">
                                            <label>Remarks (opsional)</label>
                                            <span class="help-block"><b>Wajib</b> dalam bahasa inggris</span>

                                            <textarea placeholder="Kosongi jika tidak ada" name="remarks"
                                                class="form-control" rows="3">{{ $vona->remarks }}</textarea>

                                            <div class="hr-line-dashed"></div>

                                            <button class="btn btn-primary" type="submit">Rubah VONA</button>
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

@section('add-script')
<script>
    $(document).ready(function () {
    $('#datepicker').datetimepicker({
        minDate: '2015-05-01',
        maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
        sideBySide: true,
        locale: 'id',
        format: 'YYYY-MM-DD HH:mm:ss',
    });

    $('#visibility').on('change',function(){
        let $val = $(this).val();
        $val == '0' ? $('.teramati').hide() : $('.teramati').show();
    });

    $('#color').on('change',function(){
        let $val = $(this).val();
        $val == 'green' ? $('.green').hide() : $('.green').show();
        $val == 'green' ? $("#visibility").val('0') : $("#visibility").val('1');
        $val == 'green' ? $("#visibility").prop( "disabled", true ) : $("#visibility").prop( "disabled", false )
    });

    $("input[name='terjadi_tremor']").on('ifChecked', function() {
        let $val = $(this).val();
        $val == '0' ? $('.terjadi-tremor').hide() : $('.terjadi-tremor').show();
    });

    $("input[name='terjadi_gempa_letusan']").on('ifChecked', function() {
        let $val = $(this).val();
        $val == '0' ? $('.terjadi-gempa-letusan').hide() : $('.terjadi-gempa-letusan').show();
    });
});
</script>
@endsection