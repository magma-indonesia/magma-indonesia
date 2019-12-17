@extends('layouts.default')

@section('title')
    v1 - Filter Informasi Letusan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
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
                            <span>Informasi Letusan (VEN) </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Filter Informasi Laporan Letusan Gunung Api
                </h2>
                <small>Berdasarkan jumlah data Gempa Letusan</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-12 col-lg-3">
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
                        <form class="m-t-md" role="form" id="form" method="GET" action="{{ route('chambers.v1.gunungapi.ven.filter') }}">

                            <div class="form-group">
                                <label class="control-label">Sumber Data</label>
                                <select class="form-control m-b" name="source">
                                    <option value="all" selected>- Pilih Semua -</option>
                                    <option value="lts">Gempa Letusan</option>
                                    <option value="apg">Gempa Guguran dan Awan Panas Guguran</option>
                                    <option value="hbs">Gempa Hembusan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Gunung Api</label>
                                <select id="gunungapi" class="form-control m-b" name="gunungapi">
                                    <option value="all" {{ old('gunungapi') == 'all' || empty(old('gunungapi')) ? 'selected' : '' }}>- Pilih Semua-</option>
                                    @foreach ($gadds as $gadd)
                                    <option value="{{ $gadd->ga_code }}" {{ old('gunungapi') == $gadd->ga_code ? 'selected' : '' }}>{{ $gadd->ga_nama_gapi }}</option>      
                                    @endforeach
                                </select>
                                @if( $errors->has('gunungapi'))
                                <label class="error" for="gunungapi">{{ ucfirst($errors->first('gunungapi')) }}</label>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="control-label">Range Tanggal</label>
                                <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(30)->format('Y-m-d') : old('start')}}" name="start">
                                    <div class="input-group-addon"> - </div>
                                    <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                </div>
                            </div>

                            <button class="btn btn-magma btn-block" type="submit">Apply</button>
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
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
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