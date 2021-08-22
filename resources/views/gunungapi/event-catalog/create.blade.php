@extends('layouts.default')

@section('title')
Tambahkan Kejadian Gempa Gunung Api
@endsection

@section('add-vendor-css')
<link rel="stylesheet"
    href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('add-css')
<style>
    /* For Firefox */
    input[type='number'] {
        -moz-appearance: textfield;
    }

    /* Webkit browsers like Safari and Chrome */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
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
                        <a href="{{ route('chambers.event-catalog.index') }}">Event Catalog</a>
                    </li>
                    <li class="active">
                        <span>Add event(s)</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Form untuk menambahkan katalog event gempa gunung api
            </h2>
            <small>Untuk membantu pendataan seluruh jenis kejadian gempa yang terekam di Gunung Api</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Form Event Catalog
                </div>

                <div class="panel-body">
                    <form id="form" method="POST"
                        action="{{ route('chambers.event-catalog.store') }}">
                        @csrf

                        <div class="tab-content">
                            <div class="p-m">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Pilih gunung api. Setiap gunung api yang dipilih akan meload seluruh data
                                            seismometer yang ada di gunung api tersebut.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        {{-- Nama Gunung Api --}}
                                        <div class="form-group col-sm-12">
                                            <label>Gunung Api</label>
                                            <select id="code" class="form-control" name="code">
                                                @foreach($gadds as $gadd)
                                                <option value="{{ $gadd->code }}"
                                                    {{ old('code') == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}
                                                </option>
                                                @endforeach
                                            </select>

                                            @if( $errors->has('code'))
                                            <label class="error"
                                                for="code">{{ ucfirst($errors->first('code')) }}</label>
                                            @endif
                                        </div>

                                        {{-- Stasiun Seismik --}}
                                        <div class="form-group col-sm-12">
                                            <label>Pilih stasiun</label>
                                            <div class="input-group">
                                                <select id="seismometer_id" class="form-control" name="seismometer_id">
                                                    <option value="9999">-- Optional --</option>
                                                </select><span class="input-group-btn">
                                                    <button id="load-seismometer" type="button"
                                                        class="btn btn-primary">Refresh</button></span>
                                            </div>
                                            <span class="help-block m-b-none">Tambahkan seismometer jika tidak ada dalam daftar. Kemudian klik tombol <b>refresh</b></span>

                                            <a href="{{ route('chambers.seismometer.create') }}"
                                                class="btn btn-info m-t-md" target="_blank">Tambah Seismometer</a>

                                            @if( $errors->has('seismometer_id'))
                                            <label class="error"
                                                for="seismometer_id">{{ ucfirst($errors->first('seismometer_id')) }}</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-ribbon fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Masukkan data waktu tiba fase gelombang P dan S. Sertakan perkiraan durasi
                                            waktu eventnya. Jangan lupa nilai maximum ampliudenya (dalam mm).
                                            <b>Format waktu yang digunakan adalah </b>YYYY-mm-dd H:i:s.SSS dimana <b>Y</b> adalah tahun, <b>m</b> adalah bulan, <b>d</b> adalah tanggal. Sementara <b>H:i:s.SSS</b> adalah format Jam (H), menit (i), detik (s), dan milidetik (S) dalam ribuan.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
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

                                        <div class="hpanel event">
                                            <div class="panel-heading">
                                                <label>Event <span class="event-number">1</span></label>
                                            </div>
                                            <div class="panel-body">

                                                <div class="form-group col-sm-12">
                                                    <label>Jenis Gempa</label>
                                                    <select id="types" class="form-control" name="events[]">
                                                        @foreach($types as $type)
                                                        <option value="{{ $type->code }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Zona Waktu</label>
                                                    <select name="zones[]" class="form-control">
                                                        <option value="Asia/Jakarta">WIB</option>
                                                        <option value="Asia/Makassar">WITA</option>
                                                        <option value="Asia/Jayapura">WIT</option>
                                                        <option value="UTC">UTC</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Waktu Mulai Gempa atau Waktu Tiba Gelombang P*</label>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control datetimepicker-input datetimepicker-p" id="p_datetime_1" name="p_times[]" required/>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="p_milidetik[]" min="0" max="999" value="0" required />
                                                                <span class="input-group-addon">milidetik</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="help-block m-b-none">*) Jika gelombang memiliki nilai S-P, masukkan waktu tiba gelombang P. Jika tidak, masukkan waktu gempa mulai terjadi</span>

                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label>Waktu Tiba Gelombang S*</label>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control datetimepicker-input datetimepicker-s" id="s_datetime_1" name="s_times[]"/>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="s_milidetik[]" min="0" max="999" value="0"/>
                                                                <span class="input-group-addon">milidetik</span>
                                                            </div>
                                                        </div>
                                                        <input class="s-start" type="hidden" name="s_starts[]" value="">
                                                    </div>

                                                    <span class="help-block m-b-none">*) Jika gelombang memiliki nilai S-P, masukkan waktu tiba gelombang S. Jika tidak memiliki nilai S-P, boleh dikosongi.</span>
                                                </div>


                                                <div class="form-group col-sm-6">
                                                    <label>Durasi Event</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="durations[]" required min="0" value="0" /> <span class="input-group-addon">detik</span>
                                                    </div>

                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Amplitude Maksimal</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="amplitudes[]" required min="0" value="0" />
                                                        <span class="input-group-addon">mm</span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <a role="button" class="btn btn-small btn-primary add-event">Tambah Event Baru</a>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="text-left m-t-xs">
                                            <button type="submit" class="submit btn btn-primary" href="#"> Simpan <i class="fa fa-angle-double-right"></i></button>
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

function getSeismometers($code, $scnl = 'PSAG_EHZ_VG_00') {
    let $url = '{{ route("chambers.partial.seismometer.scnl") }}/'+$code+'/'+$scnl;

    $.ajax({
        url: $url,
        type: 'POST',
        success: function(data) {
            let optional = data;
            $('#seismometer_id').html(optional);
        },
        error: function(data){
            console.log(data);
        }
    });
};

$(document).ready(function () {
    let $code = $('#code').val();
    let options = {
        minDate: '2015-05-01',
        maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
        sideBySide: true,
        locale: 'id',
        format: 'YYYY-MM-DD HH:mm:ss',
    };
    getSeismometers($code, {{ empty(old('seismometer_id')) ? 'PSAG_EHZ_VG_00' : old('seismometer_id') }});

    $('#p_datetime_1').datetimepicker(options);

    $('#s_datetime_1').datetimepicker(options);

    $('#code').on('change', function() {
        let $code = $('#code').val();
        getSeismometers($code);
    });

    $('#load-seismometer').on('click', function() {
        let $code = $('#code').val();
        getSeismometers($code);
    });

    $('form').on('click', '.add-event', function(e) {
        var $event = $(this).closest('.hpanel').clone(),
            $removePlus = $event.find('.remove-event').remove(),
            $remove = ' <a role="button" class="btn btn-small btn-danger remove-event">Hapus Event</a>',
            $addRemove = $event.find('a').after($remove);

        $(this).closest('.hpanel').after($event);

        let length = $('.event').length;
        let p_name = 'p_datetime_'+length;
        let s_name = 's_datetime_'+length;
        let datetime_p = $event.find('.datetimepicker-p');
        let datetime_s = $event.find('.datetimepicker-s');

        $event.find('.event-number').html(length);
        datetime_p.attr('id',p_name);
        datetime_s.attr('id',s_name);

        $('#'+p_name).datetimepicker(options);
        $('#'+s_name).datetimepicker(options);

    });

    $('form').on('click','.remove-event',function(){
        $(this).closest('.hpanel').remove();

        var $events = $('.event-number');
        $events.each(function (index) {
            $(this).html(index+1);
        });

    });

});
</script>
@endsection