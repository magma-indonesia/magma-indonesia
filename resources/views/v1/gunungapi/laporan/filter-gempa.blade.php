@extends('layouts.default')

@section('title')
    v1 - Cari Laporan Berdasarkan Data Gempa
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>MAGMA v1</span>
                        </li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Cari Laporan (VAR) </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Cari Laporan MAGMA-VAR
                </h2>
                <small>Memberikan hasil pencarian data laporan gunung api sesuai dengan parameter yang kita berikan</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content content-boxed">
        <div class="row">
            <div class="col-md-12">
                @role('Super Admin')
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent
                @endrole
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Filter Laporan
                    </div>
                    <div class="panel-body">
                        @if(!empty($flash_message))
                        <div class="alert alert-danger m-b-md">
                            <i class="fa fa-bolt"></i> {{ $flash_message }}
                        </div>
                        @endif

                        @if ($errors->has('gunungapi'))
                        <div class="alert alert-danger m-t-md">
                            <i class="fa fa-bolt"></i> Gunung Api belum dipilih
                        </div>
                        @endif

                        @if ($errors->has('gempa'))
                        <div class="alert alert-danger m-t-md">
                            <i class="fa fa-bolt"></i> Jenis Gempa belum dipilih
                        </div>
                        @endif

                        <form class="m-t-md" role="form" id="form" method="GET" action="{{ route('chambers.v1.gunungapi.laporan.filter.gempa') }}">

                            <div class="form-group">
                                <label class="control-label">Gunung Api</label>
                                <select id="gunungapi" class="form-control m-b" name="gunungapi">
                                    <option value="all" {{ old('gunungapi') == 'all' || empty(old('gunungapi')) ? 'selected' : '' }}>- Pilih Semua-</option>
                                    @foreach ($gadds as $gadd)
                                    <option value="{{ $gadd->ga_code }}" {{ old('gunungapi') == $gadd->ga_code ? 'selected' : '' }}>{{ $gadd->ga_nama_gapi }}</option>      
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Range Tanggal</label>
                                <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(30)->format('Y-m-d') : old('start')}}" name="start">
                                    <div class="input-group-addon"> - </div>
                                    <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                </div>
                            </div>

                            <div class="row">
                            @foreach($jenis_gempa as $key => $values)
                            <div class="form-group col-lg-6">
                                <label>
                                    @if($key == 0)
                                    Pilih Gempa
                                    @endif
                                </label>
                                @foreach($values as $value)
                                <div class="checkbox">
                                    <label><input name="gempa[]" value="{{ $value['kode'] }}" type="checkbox" class="i-checks tipe-gempa"> {{ $value['nama'] }} </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                            </div>

                            <hr>
                            <button class="btn btn-magma" type="submit">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {

        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($jenis_gempa), {collapsed: true});
        @endrole

        $.fn.datepicker.dates['id'] = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mi', 'Se', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            today: 'Hari ini',
            clear: 'Bersihkan',
            format: 'yyyy-mm-dd',
            titleFormat: 'MM yyyy',
            weekStart: 1
        };

        $('.input-daterange').datepicker({
            startDate: '2015-05-01',
            endDate: '{{ now()->format('Y-m-d') }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false
        });
    });
</script>
@endsection