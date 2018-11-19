@extends('layouts.default')

@section('title')
    VAR - Step 3
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
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.laporan.create.3') }}">Buat VAR - Step 3</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru Step 3 - Input data kegempaan.</small>
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
                        Form MAGMA-VAR data Kegempaan
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.3')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 1 - <span class="hidden-xs">Data Laporan</span></a>
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 2 - Data Visual</a>
                                <a class="btn btn-primary" href="#">Step 3 - Data Kegempaan</a>
                            </div>
                            <hr>
                            <div class="tab-content">
                                <div id="step3" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class=" pe-7s-graph2 fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Input Laporan pengamatan Kegempaan MAGMA-VAR</strong>, form ini digunakan untuk input data kegempaan gunung api.
                                                <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
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
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pembuat</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label>Data Kegempaan</label>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="hasgempa" value="0" type="radio" class="i-checks has-gempa"> Nihil </label>
                                                        <label class="checkbox-inline"><input name="hasgempa" value="1" type="radio" class="i-checks has-gempa" checked> Tambahkan Kegempaan </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Pilih Jenis Gempa --}}
                                            <div class="pilih-gempa">
                                                <div class="row">
                                                    @foreach($jenisgempa as $key => $values)
                                                    <div class="form-group col-lg-6">
                                                        <label>
                                                            @if($key == 0)
                                                            Pilih Gempa
                                                            @endif
                                                        </label>
                                                        @foreach($values as $key1 => $value)
                                                        <div class="checkbox">
                                                            <label><input name="tipe_gempa[]" value="{{ $value['kode'] }}" type="checkbox" class="i-checks tipe-gempa"> {{ $value['nama'] }} </label>
                                                        </div>
                                                        @endforeach                                                    
                                                    </div>
                                                    @endforeach
                                                </div>

                                                {{-- Input Gempa --}}
                                                @foreach($jenisgempa as $key => $values)
                                                @foreach($values as $key1 => $value)
                                                
                                                @if($value['jenis'] == 'sp')
                                                {{-- Jenis Gempa - SP --}}
                                                <div class="row" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- SP --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="SP-min" name="data[{{ $value['kode'] }}][spmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="SP-max" name="data[{{ $value['kode'] }}][spmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'erupsi')
                                                {{-- Jenis Gempa - Erupsi --}}
                                                <div class="row" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        {{-- Jumlah --}}
                                                        <div class="p-sm">
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=1 value="">                       
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="number" step=0.1 value="">
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Tinggi Letusan --}}
                                                            <label class="m-t-sm">Tinggi Abu Letusan</label>
                                                            <div class="input-group">
                                                                <input placeholder="Tinggi min" name="data[{{ $value['kode'] }}][tmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][tmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Warna Asap --}}
                                                            <label class="m-t-sm">Warna Abu Letusan</label>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Putih" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}"> Putih </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Kelabu" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}"> Kelabu </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Cokelat" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}"> Cokelat </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][wasap][]" value="Hitam" type="checkbox" class="i-checks wasap input-{{ $value['kode'] }}"> Hitam </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'normal')
                                                {{-- Jenis Gempa - Normal --}}
                                                <div class="row" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        {{-- Jumlah --}}
                                                        <div class="p-sm">
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">                       
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'dominan')
                                                {{-- Jenis Gempa - Dominan --}}
                                                <div class="row" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">                       
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm m-b-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Dominan --}}
                                                            <input placeholder="Amplitudo Dominan" name="data[{{ $value['kode'] }}][adom]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'luncuran')
                                                {{-- Jenis Gempa - Luncuran --}}
                                                <div class="row" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">                       
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Jarak Luncur --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Jarak Luncur min" name="data[{{ $value['kode'] }}][rmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Jarak Luncur max" name="data[{{ $value['kode'] }}][rmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Arah Luncuran --}}
                                                            <label class="m-t-sm">Arah Luncuran</label>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Utara" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Utara </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Timur" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Timur </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Tenggara" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Tenggara </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Selaran" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Selatan </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Barat" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Barat </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Barat Daya" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Barat Daya </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Barat Laut" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Barat Laut </label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input name="data[{{ $value['kode'] }}][alun][]" value="Timur Laut" type="checkbox" class="i-checks alun input-{{ $value['kode'] }}"> Timur Laut </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr style="margin-top:5px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                @elseif($value['jenis'] == 'terasa')
                                                {{-- Jenis Gempa - Terasa --}}
                                                <div class="row" data-code="gempa-{{ $value['kode'] }}" data-jenis="{{ $value['jenis']}}">
                                                    <div class="col-lg-10">
                                                        <label class="control-label">{{ $value['nama'] }}</label>
                                                        <div class="p-sm">
                                                            {{-- Jumlah --}}
                                                            <input placeholder="Jumlah" name="data[{{ $value['kode'] }}][jumlah]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">                       
                                                            {{-- Amplitudo --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="A-min" name="data[{{ $value['kode'] }}][amin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="A-max" name="data[{{ $value['kode'] }}][amax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- SP --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="SP-min" name="data[{{ $value['kode'] }}][spmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="SP-max" name="data[{{ $value['kode'] }}][spmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Durasi --}}
                                                            <div class="input-group m-t-sm">
                                                                <input placeholder="Durasi min" name="data[{{ $value['kode'] }}][dmin]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                                <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                                <input placeholder="Durasi max" name="data[{{ $value['kode'] }}][dmax]" class="input-gempa form-control input-{{ $value['kode'] }}" type="text" value="">
                                                            </div>
                                                            {{-- Skala Terasa --}}
                                                            <label class="m-t-sm">Skala Gempa</label>
                                                            <div class="input-group">
                                                                <select class="input-gempa form-control input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][skala][]"">
                                                                    <option value="1">I</option>
                                                                    <option value="2">II</option>
                                                                    <option value="3">III</option>
                                                                    <option value="4">IV</option>
                                                                    <option value="5">V</option>
                                                                    <option value="6">VI</option>
                                                                    <option value="7">VII</option>
                                                                </select>
                                                                <span class="input-group-addon"> hingga </span>
                                                                <select class="input-gempa form-control input-{{ $value['kode'] }}" name="data[{{ $value['kode'] }}][skala][]"">
                                                                    <option value="1">I</option>
                                                                    <option value="2">II</option>
                                                                    <option value="3">III</option>
                                                                    <option value="4">IV</option>
                                                                    <option value="5">V</option>
                                                                    <option value="6">VI</option>
                                                                    <option value="7">VII</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endforeach
                                            </div>
                                            
                                            {{-- Button Footer --}}
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <a href="{{ route('chambers.laporan.create.1') }}" type="button" class="btn btn-default">Step 1 - Data Laporan</a>
                                                <a href="{{ route('chambers.laporan.create.2') }}" type="button" class="btn btn-default">Step 2 - Data Visual</a>
                                                <button type="submit" class="submit btn btn-primary">Step 3 - Submit</button>
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
        $(document).ready(function() {

            function initiate()
            {
                $('div[data-code^="gempa"]').hide();
                $('.input-gempa').prop('disabled',true);
                $('.i-checks').not('.i-checks.has-gempa, .i-checks.tipe-gempa').iCheck('disable');
            }

            function nihilChecked()
            {
                $('.input-gempa').prop('disabled',true);
                $('.i-checks').not('.i-checks.has-gempa').iCheck('disable');
                $('.pilih-gempa').hide();
                console.log('Kegempaan Nihil');
            }

            function nihilUnchecked()
            {
                $('.i-checks.has-gempa, .i-checks.tipe-gempa').iCheck('enable');
                $('.pilih-gempa').show();
                console.log('Tambahkan Kegempaan');
            }
            
            initiate();

            $('.has-gempa').on('ifChecked', function(e) {
                $(this).val() == '1' ? nihilUnchecked() : nihilChecked();
            });

            $('.tipe-gempa').on('ifChecked', function(e) {
                var $codeGempa = $(this).val();
                console.log('Gempa '+$codeGempa+' checked');
                $('div[data-code="gempa-'+$codeGempa+'"]').fadeIn();
                $('.i-checks.input-'+$codeGempa).iCheck('enable');
                $('.input-'+$codeGempa).prop('disabled',false);
            });

            $('.tipe-gempa').on('ifUnchecked', function(e) {
                var $codeGempa = $(this).val();
                console.log('Gempa '+$codeGempa+' unchecked');
                $('div[data-code="gempa-'+$codeGempa+'"]').hide();
                $('.i-checks.input-'+$codeGempa).iCheck('disable');
                $('.input-'+$codeGempa).prop('disabled',true);
            });
        });
    </script>
@endsection