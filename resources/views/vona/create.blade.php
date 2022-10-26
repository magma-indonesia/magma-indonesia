@extends('layouts.default')

@section('title')
Buat VONA Gunung Api
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
                        <a href="{{ route('chambers.vona.index') }}">VONA</a>
                    </li>
                    <li class="active">
                        <span>Buat VONA</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Form untuk membuat laporan VONA
            </h2>
            <small>Volcano Observatory Notice for Aviation</small>
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

                    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.vona.store') }}" enctype="multipart/form-data">
                        @csrf
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
                                                <option value="real" {{ old('jenis') == 'real' ? 'selected' : ''}}>Real</option>
                                                <option value="exercise" {{ old('jenis') == 'exercise' ? 'selected' : ''}}>Exercise</option>
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

                                        {{-- Color code --}}
                                        <div class="form-group col-sm-12">
                                            <label>Color Code</label>
                                            <select id="color" class="form-control" name="color">
                                                <option value="auto" {{ old('color')=='auto' ? 'selected' : '' }}>Otomatis Berdasarkan Tinggi Abu</option>
                                                <option value="red" {{ old('color')=='red' ? 'selected' : '' }}>RED</option>
                                                <option value="orange" {{ old('color')=='orange' ? 'selected' : '' }}>ORANGE</option>
                                                <option value="yellow" {{ old('color')=='yellow' ? 'selected' : '' }}>YELLOW</option>
                                                <option value="green" {{ old('color')=='green' ? 'selected' : '' }}>GREEN</option>
                                            </select>
                                            <span class="help-block m-b-none">Pilihan warna <b>Otomatis</b> akan ditentukan berdasarkan tinggi abu teramati atau terjadi tidaknya letusan.</span>
                                            @if( $errors->has('color'))
                                            <label class="error" for="color">{{ ucfirst($errors->first('color')) }}</label>
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
                                                <option value="1" {{ old('visibility')=='1' ? 'selected' : '' }}>Teramati</option>
                                                <option value="0" {{ old('visibility')=='0' ? 'selected' : '' }}>Tidak Teramati</option>
                                            </select>
                                            @if( $errors->has('visibility'))
                                            <label class="error" for="visibility">{{ ucfirst($errors->first('visibility')) }}</label>
                                            @endif
                                        </div>

                                        <div class="teramati" style="display: {{ old('visibility') == '0' ? 'none' :'block'}};">
                                            {{-- Tinggi Letusan --}}
                                            <div class="form-group col-sm-12">
                                                <label>Tinggi Abu yang teramati</label>
                                                <div class="input-group">
                                                    <input placeholder="Maksimal 20000 meter" name="height" class="form-control" type="text"
                                                        value="{{ empty(old('height')) ? '' : old('height') }}" >
                                                    <span class="input-group-addon h-bg-red">meter, di atas puncak</span>
                                                </div>

                                                @if( $errors->has('height'))
                                                <label class="error" for="height">{{ ucfirst($errors->first('height')) }}</label>
                                                @endif

                                            </div>

                                            {{-- Warna Abu --}}
                                            <div class="form-group col-sm-12">
                                                <label>Warna abu</label>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Putih" type="checkbox" class="i-checks wasap" {{
                                                                    (is_array(old('warna_asap')) AND in_array('Putih',old('warna_asap'))) ? 'checked'
                                                                    : '' }}> Putih </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Kelabu" type="checkbox" class="i-checks wasap" {{
                                                                    (is_array(old('warna_asap')) AND in_array('Kelabu',old('warna_asap'))) ? 'checked'
                                                                    : '' }}> Kelabu </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Coklat" type="checkbox" class="i-checks wasap" {{
                                                                    (is_array(old('warna_asap')) AND in_array('Coklat',old('warna_asap'))) ? 'checked'
                                                                    : '' }}> Coklat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="warna_asap[]" value="Hitam" type="checkbox" class="i-checks wasap" {{
                                                                    (is_array(old('warna_asap')) AND in_array('Hitam',old('warna_asap'))) ? 'checked'
                                                                    : '' }}> Hitam </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($errors->has('warna_asap'))
                                                <label class="error" for="warna_asap">{{ ucfirst($errors->first('warna_asap')) }}</label>
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
                                                        <label class="checkbox-inline"><input name="intensitas[]" value="Tipis" type="checkbox"
                                                                class="i-checks intensitas" {{ (is_array(old('intensitas')) AND
                                                                in_array('Tipis',old('intensitas'))) ? 'checked' : '' }}> Tipis </label>
                                                        <label class="checkbox-inline"><input name="intensitas[]" value="Sedang" type="checkbox"
                                                                class="i-checks intensitas" {{ (is_array(old('intensitas')) AND
                                                                in_array('Sedang',old('intensitas'))) ? 'checked' : '' }}> Sedang </label>
                                                        <label class="checkbox-inline"><input name="intensitas[]" value="Tebal" type="checkbox"
                                                                class="i-checks intensitas" {{ (is_array(old('intensitas')) AND
                                                                in_array('Tebal',old('intensitas'))) ? 'checked' : '' }}> Tebal </label>
                                                    </div>
                                                </div>
                                                @if( $errors->has('intensitas'))
                                                <label class="error" for="intensitas">{{ ucfirst($errors->first('intensitas')) }}</label>
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
                                                            <label><input name="arah_abu[]" value="Utara" type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array(old('arah_abu')) AND in_array('Utara',old('arah_abu'))) ? 'checked' : ''
                                                                    }}> Utara </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Timur Laut" type="checkbox" class="i-checks arah-abu"
                                                                    {{ (is_array(old('arah_abu')) AND in_array('Timur Laut',old('arah_abu')))
                                                                    ? 'checked' : '' }}> Timur Laut </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Timur" type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array(old('arah_abu')) AND in_array('Timur',old('arah_abu'))) ? 'checked' : ''
                                                                    }}> Timur </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Tenggara" type="checkbox" class="i-checks arah-abu"
                                                                    {{ (is_array(old('arah_abu')) AND in_array('Tenggara',old('arah_abu'))) ? 'checked'
                                                                    : '' }}> Tenggara </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Selatan" type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array(old('arah_abu')) AND in_array('Selatan',old('arah_abu'))) ? 'checked' : ''
                                                                    }}> Selatan </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat Daya" type="checkbox" class="i-checks arah-abu"
                                                                    {{ (is_array(old('arah_abu')) AND in_array('Barat Daya',old('arah_abu')))
                                                                    ? 'checked' : '' }}> Barat Daya </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat" type="checkbox" class="i-checks arah-abu" {{
                                                                    (is_array(old('arah_abu')) AND in_array('Barat',old('arah_abu'))) ? 'checked' : ''
                                                                    }}> Barat </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label><input name="arah_abu[]" value="Barat Laut" type="checkbox" class="i-checks arah-abu"
                                                                    {{ (is_array(old('arah_abu')) AND in_array('Barat Laut',old('arah_abu')))
                                                                    ? 'checked' : '' }}> Barat Laut </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if( $errors->has('arah_abu'))
                                                <label class="error" for="arah_abu">{{ ucfirst($errors->first('arah_abu')) }}</label>
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
                                            <input name="date" id="datepicker" class="form-control" type="text" value="{{ empty(old('date')) ? now()->format('Y-m-d H:i') : old('date') }}">
                                            <span class="help-block m-b-none">Gunakan <b>Waktu Laporan</b> jika tidak tidak terjadi letusan atau ingin <b>menurunkan Color Code</b> </span>
                                            @if( $errors->has('date'))
                                            <label class="error" for="date">{{ ucfirst($errors->first('date')) }}</label>
                                            @endif
                                        </div>

                                        <div class="green" style="display: {{ old('color') === 'green' ? 'none' :'block'}};">

                                            {{-- Erupsi sedang berlangsung --}}
                                            <div class="form-group col-sm-12">
                                                <label>Apakah erupsi saat ini sedang berlangsung?</label>
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

                                            {{-- Rekaman Seismik --}}
                                            <div class="form-group col-sm-12">
                                                <hr>
                                                <label>Amplitudo Maksimum Letusan</label>
                                                <span class="help-block"><b>Isi dengan 0</b> jika tidak terjadi letusan. Range amplitudo 0 - 240mm.</span>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 100px;">Amplitudo</span>
                                                    <input id="amplitudo" name="amplitudo" class="form-control" type="text" value="{{ empty(old('amplitudo')) ? '' : old('amplitudo') }}" >
                                                    <span class="input-group-addon" style="min-width: 75px;">mm</span>
                                                </div>
                                                @if( $errors->has('amplitudo'))
                                                <label class="error" for="amplitudo">{{ ucfirst($errors->first('amplitudo')) }}</label>
                                                @endif
                                            </div>

                                            {{-- Durasi Letusan --}}
                                            <div class="form-group col-sm-12">
                                                <label>Durasi</label>
                                                <span class="help-block"><b>Isi dengan 0</b> jika tidak ada letusan. Jika <b>erupsi sedang berlangsung</b>, durasi waktu letusan boleh dikosongi. </span>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 100px;">Durasi</span>
                                                    <input name="durasi" class="form-control" type="text" value="{{ empty(old('durasi')) ? '' : old('durasi') }}" >
                                                    <span class="input-group-addon" style="min-width: 75px;">detik</span>
                                                </div>

                                                @if( $errors->has('durasi'))
                                                <label class="error" for="durasi">{{ ucfirst($errors->first('durasi')) }}</label>
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
                                            Tidak wajib. Tambahkan catatan lainnya jika diperlukan. <b>Wajib dalam bahasa inggris</b>.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Submit --}}
                                        <div class="form-group col-sm-12">
                                            <label>Remarks (opsional)</label>
                                            <span class="help-block"><b>Wajib</b> dalam bahasa inggris</span>

                                            <textarea placeholder="Kosongi jika tidak ada" name="remarks"class="form-control" rows="3">{{ old('remarks')}}</textarea>

                                            <div class="hr-line-dashed"></div>

                                            <button class="btn btn-primary" type="submit">Buat VONA</button>
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
    });
});
</script>
@endsection