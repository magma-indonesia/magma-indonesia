@extends('layouts.default')

@section('title')
Rubah {{ $eventCatalog->type->name }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
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
                        <span>Rubah event</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                {{ $eventCatalog->gunungapi->name }} - {{ $eventCatalog->seismometer->scnl }} - {{ $eventCatalog->type->name }}
            </h2>
            <small>Waktu mulai gempa {{ $eventCatalog->p_datetime_utc }} UTC</small>
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
                    <form id="form" method="POST" action="{{ route('chambers.event-catalog.update', $eventCatalog) }}">
                    @csrf
                    @method('PUT')

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
                                        <div class="form-group col-sm-12">
                                            <label>Gunung Api</label>
                                            <select id="code" class="form-control" name="code">
                                                @foreach($gadds as $gadd)
                                                <option value="{{ $gadd->code }}"
                                                    {{ $eventCatalog->gunungapi->code == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}
                                                </option>
                                                @endforeach
                                            </select>

                                            @if( $errors->has('code'))
                                            <label class="error"
                                                for="code">{{ ucfirst($errors->first('code')) }}</label>
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
                                            <div class="panel-body">
                                                <div class="form-group col-sm-12">
                                                    <label>Pilih stasiun</label>
                                                    <div class="input-group">
                                                        <select id="seismometer_id" class="form-control" name="seismometer_id">
                                                            <option value="">Loading...</option>
                                                        </select>
                                                        <span class="input-group-btn">
                                                            <button onclick="window.open('{{ route('chambers.seismometer.create') }}', '_blank')" type="button"class="btn btn-info">Tambah Seismometer</button>
                                                            <button id="load-seismometer" type="button"class="btn btn-primary">Refresh</button>
                                                            </span>
                                                    </div>
                                                    <span class="help-block m-b-none">Tambahkan seismometer jika tidak ada dalam daftar. Kemudian klik tombol <b>refresh</b></span>
                                                </div>

                                                <div class="form-group col-sm-8">
                                                    <label>Jenis Gempa</label>
                                                    <select id="types" class="form-control" name="event">
                                                        @foreach($types as $type)
                                                        <option value="{{ $type->code }}" {{ $eventCatalog->type->code == $type->code ? 'selected' : ''}}>{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-4">
                                                    <label>Zona Waktu</label>
                                                    <select name="zone" class="form-control">
                                                        <option value="Asia/Jakarta" {{ $eventCatalog->time_zone == "Asia/Jakarta" ? 'selected' : ''}}>WIB</option>
                                                        <option value="Asia/Makassar" {{ $eventCatalog->time_zone == "Asia/Makassar" ? 'selected' : ''}}>WITA</option>
                                                        <option value="Asia/Jayapura" {{ $eventCatalog->time_zone == "Asia/Jayapura" ? 'selected' : ''}}>WIT</option>
                                                        <option value="UTC" {{ $eventCatalog->time_zone == "UTC" ? 'selected' : ''}}>UTC</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Waktu Mulai Event atau Waktu Tiba Gelombang P*</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <input type="text" value="{{ $eventCatalog->p_datetime_local }}" class="form-control datetimepicker-input datetimepicker-p" id="p_datetime_1" name="p_time" required/>
                                                        </div>
                                                    </div>
                                                    <span class="help-block m-b-none">*) <b>Presisi waktu dalam milidetik.</b> Jika gelombang memiliki nilai S-P, masukkan waktu tiba gelombang P. Jika tidak, masukkan waktu gempa mulai terjadi</span>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Waktu Tiba Gelombang S*</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <input type="text" value="{{ $eventCatalog->s_datetime_local }}" class="form-control datetimepicker-input datetimepicker-s" id="s_datetime_1" name="s_time"/>
                                                        </div>
                                                    </div>

                                                    <span class="help-block m-b-none">*) <b>Presisi waktu dalam milidetik.</b> Jika gelombang memiliki nilai S-P, masukkan waktu tiba gelombang S. Jika tidak memiliki nilai S-P, boleh dikosongi.</span>
                                                </div>

                                                <div class="form-group col-sm-6">
                                                    <label>Durasi Event</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="duration" required min="0" value="{{ $eventCatalog->duration }}" /> <span class="input-group-addon">detik</span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-6">
                                                    <label>Amplitude Maksimal</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="amplitude" required min="0" value="{{ $eventCatalog->maximum_amplitude }}" />
                                                        <span class="input-group-addon">mm</span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <button type="submit" class="btn btn-small btn-primary">Simpan Perubahan</button>
                                            </div>
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
@endsection

@section('add-script')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function getSeismometers($code, $scnl = '{{ $eventCatalog->seismometer->scnl }}') {
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
        format: 'YYYY-MM-DD HH:mm:ss.SSS',
    };

    getSeismometers($code, "{{ empty(old('seismometer_id')) ? $eventCatalog->seismometer->scnl : old('seismometer_id') }}");

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
});
</script>
@endsection