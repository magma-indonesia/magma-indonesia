@extends('layouts.default')

@section('title')
    Buat Kejadian Gempa Bumi
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
@endsection

@section('add-css')
<style>
    /* For Firefox */
    input[type='number'] {
        -moz-appearance:textfield;
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
                        <a href="{{ route('chambers.index') }}">Chamber</a>
                    </li>
                    <li>
                        <span>Gempa Bumi</span>
                    </li>
                    <li class="active">
                        <span>Create </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Tambahkan Kejadian Gempa Bumi
            </h2>
            <small>Menu ini digunakan untuk menambahkan kejadian Gempa Bumi secara manual ke dalam database MAGMA v1</small>
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
                    Form Tambahkan Kejadian Gempa Bumi untuk MAGMA v1
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('chambers.v1.gempabumi.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $user->name }}" name="roq_nama_pelapor">
                        <input name="roq_nip_pelapor" type="hidden" value="{{ $user->nip }}">

                        <div class="text-center m-b-md" id="wizardControl">
                            <a class="btn btn-primary m-b" href="{{ route('chambers.v1.gempabumi.create') }}">Form Kejadian</a>
                        </div>
                        <hr>
                        <div class="tab-content">
                            <div id="step1" class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-3 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Tambahkan Kejadian Gempa Bumi</strong>, form ini digunakan untuk menambahkan secara manual kejadian Gempa Bumi terkini ke dalam database MAGMA v1.<br><br>
                                            Info gempa terkini bisa diakses melalui website <u><a href="http://www.bmkg.go.id/gempabumi/gempabumi-terkini.bmkg" target="_blank">BMKG - Info Gempa Terkini</a></u><br/><br/> Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                        </p>
                                    </div>
                                    <div class="col-lg-9">
        
                                        @if ($errors->any())
                                        <div class="row m-b-md">
                                            <div class="col-lg-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{!! $error !!}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Waktu Gempa</label>
                                                <div class="input-group">
                                                    <input name="datetime_wib" id="datepicker" class="form-control" type="text" value="{{ empty(old('datetime_wib')) ? now()->format('Y-m-d H:i:s') : old('datetime_wib') }}" required>
                                                    <span class="input-group-addon" style="min-width: 75px;">WIB</span>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Lintang</label>
                                                <div class="input-group">
                                                    <input name="lat_lima" id="latitude" class="form-control" type="text" value="{{old('lat_lima')}}" required>
                                                    <span class="input-group-addon" style="min-width: 75px;">&deg;LU</span>
                                                </div>
                                                <span class="help-block m-b-none">BMKG menggunakan acuan <b>Lintang Utara (LU)</b></span>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Bujur</label>
                                                <div class="input-group">
                                                    <input name="lon_lima" id="longitude" class="form-control" type="text" value="" required>
                                                    <span class="input-group-addon" style="min-width: 75px;">&deg;BT</span>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Magnitudo</label>
                                                <div class="input-group">
                                                    <input name="magnitude" id="magnitude" class="form-control" type="text" value="" required>
                                                    <span class="input-group-addon" style="min-width: 75px;"> SR </span>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Kedalaman</label>
                                                <div class="input-group">
                                                    <input name="depth" id="depth" class="form-control" type="text" value="10" required>
                                                    <span class="input-group-addon" style="min-width: 75px;"> Km </span>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Wilayah</label>
                                                <input name="area" id="area" class="form-control" type="text" value="" placeholder="Contoh: 55 km Tenggara MANOKWARISEL-PAPUABRT" required>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Kota Terdekat</label>
                                                <input name="koter" id="koter" class="form-control" type="text" value="" placeholder="Contoh: MANOKWARISEL-PAPUABRT" required>
                                                <span class="help-block m-b-none">Biasanya menggunakan <b>akhiran dari Wilayah</b>, contoh 55 km Tenggara <b>MANOKWARISEL-PAPUABRT</b>.</span>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <hr>
                                                <label>Buat Tanggapan ?</label>
                                                <div class="checkbox">
                                                    <label class="checkbox-inline"><input name="roq_tanggapan" value="1" type="radio" class="i-checks tanggapan" {{ old('roq_tanggapan') == '1' ? 'checked' : ''}}> Ya </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label class="checkbox-inline"><input name="roq_tanggapan" value="0" type="radio" class="i-checks tanggapan" {{ (old('roq_tanggapan') == '0') || empty(old('roq_tanggapan')) ? 'checked' : ''}}> Tidak </label>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="tanggapan" style="{{ (old('roq_tanggapan') == '0') || empty(old('roq_tanggapan')) ? 'display: none;' : ''}}">
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Daerah</label>
                                                    <input name="roq_title" id="roq_title" class="form-control" type="text" value="">
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Pendahuluan</label>
                                                    <textarea id="roq_intro" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="roq_intro" class="form-control p-m" rows="4"></textarea>
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Kondisi Wilayah</label>
                                                    <textarea id="roq_konwil" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="roq_konwil" class="form-control p-m" rows="4"></textarea>
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Mekanisme</label>
                                                    <textarea id="roq_mekanisme" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="roq_mekanisme" class="form-control p-m" rows="4"></textarea>
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Efek</label>
                                                    <textarea id="roq_efek" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="roq_efek" class="form-control p-m" rows="4"></textarea>
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Rekomendasi</label>
                                                    <textarea id="roq_rekom" placeholder="Gunakan tata bahasa Indonesia yang baik dan benar dan hindari penggunaan singkatan." name="roq_rekom" class="form-control p-m" rows="4"></textarea>
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Sumber Data</label>
                                                    <div class="checkbox">
                                                        <label><input name="roq_source_code[]" value="BMKG" type="checkbox" class="i-checks source" checked> BMKG </label>
                                                        <label><input name="roq_source_code[]" value="GFZ" type="checkbox" class="i-checks source"> GFZ </label>
                                                        <label><input name="roq_source_code[]" value="USGS" type="checkbox" class="i-checks source"> USGS </label>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label>Potensi Tsunami ?</label>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="roq_tsu" value="1" type="radio" class="i-checks tsunami" {{ old('roq_tsu') == '1' ? 'checked' : ''}}> Berpotensi terjadi Tsunami </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="roq_tsu" value="0" type="radio" class="i-checks tsunami" {{ (old('roq_tsu') == '0') || empty(old('roq_tsu')) ? 'checked' : ''}}> Tidak berpotensi terjadi Tsunami</label>
                                                    </div>
                                                </div>
                                            </div>
        
                                        </div>
        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-9">
                                        <hr>
                                        <div class="text-left m-t-xs">
                                            <button type="submit" class="submit btn btn-primary" href="#"> Save </button>
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
        $(document).ready(function () {

            $('#datepicker').datetimepicker({
                minDate: '2018-01-01',
                maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
                sideBySide: true,
                locale: 'id',
                format: 'YYYY-MM-DD HH:mm:ss',
            });

            $('.i-checks.tanggapan').on('ifChecked', function(e) {
                console.log($(this).attr('class'));
                $(this).val() == '1' ? $('.tanggapan').show() : $('.tanggapan').hide();
            });

        });
    </script>
@endsection