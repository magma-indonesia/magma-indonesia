@extends('layouts.default')

@section('title')
    Step 3 - Klimatologi
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
                            <a href="{{ route('chambers.laporan.create.var.klimatologi') }}">Step 3 - Klimatologi</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru Input data Klimatologi.</small>
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
                        Form MAGMA-VAR data Klimatologi
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.var.klimatologi') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 1 - Data Laporan</a>
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 2 - Visual</a>
                                <a class="btn btn-primary" href="#">Step 3 - Klimatologi</a>
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 4 - Kegempaan</a>
                            </div>
                            <hr>
                            <div class="tab-content">
                                <div id="step3" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class="pe-7s-cloud fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Buat Laporan MAGMA-VAR</strong>, form ini digunakan untuk input data pengamatan klimatologi.
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
                                                {{-- Cuaca --}}
                                                <div class="form-group col-lg-12">
                                                    <label>Cuaca</label>
                                                    <div class="checkbox">
                                                        <label><input name="cuaca[]" value="Cerah" type="checkbox" class="i-checks"> Cerah </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="cuaca[]" value="Berawan" type="checkbox" class="i-checks"> Berawan</label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="cuaca[]" value="Mendung" type="checkbox" class="i-checks"> Mendung</label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="cuaca[]" value="Hujan" type="checkbox" class="i-checks"> Hujan</label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="cuaca[]" value="Badai" type="checkbox" class="i-checks"> Badai</label>
                                                    </div>
                                                </div>

                                                {{-- Kecepatan Angin --}}
                                                <div class="form-group col-lg-12">
                                                    <label>Kecepatan Angin</label>
                                                    <div class="checkbox">
                                                        <label><input name="kecangin[]" value="Lemah" type="checkbox" class="i-checks"> Lemah </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="kecangin[]" value="Sedang" type="checkbox" class="i-checks"> Sedang</label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="kecangin[]" value="Kencang" type="checkbox" class="i-checks"> Kencang</label>
                                                    </div>
                                                </div>

                                                {{-- Arah Angin --}}
                                                <div class="form-group col-lg-12">
                                                    <label>Arah Angin</label>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Utara" type="checkbox" class="i-checks"> Utara </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Timur" type="checkbox" class="i-checks"> Timur </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Tenggara" type="checkbox" class="i-checks"> Tenggara </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Selaran" type="checkbox" class="i-checks"> Selatan </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Barat" type="checkbox" class="i-checks"> Barat </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Barat Daya" type="checkbox" class="i-checks"> Barat Daya </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Barat Laut" type="checkbox" class="i-checks"> Barat Laut </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label><input name="arah_angin[]" value="Timur Laut" type="checkbox" class="i-checks"> Timur Laut </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Curah Hujan --}}
                                                <div class="form-group col-lg-12">
                                                    <label>Curah Hujan</label>
                                                    <div class="form-group">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <div class="input-group">
                                                                <input placeholder="Curah hujan" name="curah_hujan" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon"> mm </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Suhu Udara--}}
                                                <div class="form-group col-lg-12">
                                                    <label>Suhu Udara</label>
                                                    <div class="form-group">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <div class="input-group">
                                                                <input placeholder="Suhu min" name="suhu_min" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon"> - </span>
                                                                <input placeholder="Suhu max" name="suhu_max" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon">°C</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kelembaban Udara--}}
                                                <div class="form-group col-lg-12">
                                                    <label>Kelembaban Udara</label>
                                                    <div class="form-group">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <div class="input-group">
                                                                <input placeholder="Kelembaban Min" name="kelembaban_min" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon"> - </span>
                                                                <input placeholder="Kelembaban Max" name="kelembaban_max" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tekanan Udara--}}
                                                <div class="form-group col-lg-12">
                                                    <label>Tekanan Udara</label>
                                                    <div class="form-group">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <div class="input-group">
                                                                <input placeholder="Tekanan Min" name="tekanan_min" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon"> - </span>
                                                                <input placeholder="Tekanan Max" name="tekanan_max" class="form-control" type="number" value="0" required>
                                                                <span class="input-group-addon">mmHg</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            {{-- Button Footer --}}
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <a href="{{ route('chambers.laporan.create.var') }}" type="button" class="btn btn-default">Step 1 - Data Laporan</a>
                                                <a href="{{ route('chambers.laporan.create.var.visual') }}" type="button" class="btn btn-default">Step 2 - Visual</a>
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
        $(document).ready(function () {

        });
    </script>
@endsection