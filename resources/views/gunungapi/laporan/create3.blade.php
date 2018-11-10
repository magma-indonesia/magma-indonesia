@extends('layouts.default')

@section('title')
    VAR - Step 3
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
                            <a href="{{ route('chambers.laporan.create.2') }}">Buat VAR - Step 3</a>
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
                                                        <label class="checkbox-inline"><input name="hasgempa" value="0" type="radio" class="i-checks hasgempa"> Nihil </label>
                                                        <label class="checkbox-inline"><input name="hasgempa" value="1" type="radio" class="i-checks hasgempa" checked> Tambahkan Kegempaan </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Pilih Jenis Gempa --}}
                                            <div class="row">
                                                @foreach($jenisgempa as $key => $values)
                                                <div class="form-group col-lg-6 pilih-gempa ">
                                                    <label>
                                                        @if($key == 0)
                                                        Pilih Gempa
                                                        @endif
                                                    </label>
                                                    @foreach($values as $key1 => $value)
                                                    <div class="checkbox">
                                                        <label><input name="gempas[]" value="{{ $value['kode'] }}" type="checkbox" class="i-checks gempas"> {{ $value['nama'] }} </label>
                                                    </div>
                                                    @endforeach                                                    
                                                </div>
                                                @endforeach
                                            </div>

                                            {{-- Input Gempa --}}
                                            @foreach($jenisgempa as $key => $values)
                                            @foreach($values as $key1 => $value)
                                            <div class="row {{ $value['kode'] }}">
                                                <div class="form-group col-lg-6">
                                                    <label class="control-label">{{ $value['nama'] }}</label>
                                                    <input placeholder="Jumlah" name="{{ $value['kode'] }}[jumlah]" class="form-control" type="text" value="">                       
                                                    {{-- Amplitudo --}}
                                                    <div class="input-group m-t-sm">
                                                        <input placeholder="A-min" name="{{ $value['kode'] }}[amin]" class="form-control" type="text" value="">
                                                        <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                        <input placeholder="A-max" name="{{ $value['kode'] }}[amax]" class="form-control" type="text" value="">
                                                    </div>
                                                    {{-- SP --}}
                                                    <div class="input-group m-t-sm">
                                                        <input placeholder="SP-min" name="{{ $value['kode'] }}[spmin]" class="form-control" type="text" value="">
                                                        <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                        <input placeholder="SP-max" name="{{ $value['kode'] }}[spmax]" class="form-control" type="text" value="">
                                                    </div>
                                                    {{-- Durasi --}}
                                                    <div class="input-group m-t-sm">
                                                        <input placeholder="Durasi min" name="{{ $value['kode'] }}[dmin]" class="form-control" type="text" value="">
                                                        <span class="input-group-addon" style="min-width: 30px;"> - </span>
                                                        <input placeholder="Durasi max" name="{{ $value['kode'] }}[dmax]" class="form-control" type="text" value="">
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endforeach
                                            

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
            $('.hasgempa').on('ifChecked', function(e) {
                $(this).val() == '1' ? $('.pilih-gempa').show() : $('.pilih-gempa').hide();
            });

            $('.gempas').on('ifChecked', function(e) {
                console.log($(this).val());
            });

            $('.gempas').on('ifUnchecked', function(e) {
                console.log('unchecked '+$(this).val());
            });
        });
    </script>
@endsection