@extends('layouts.default')

@section('title')
Filter VONA
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
                    <li>
                        <a href="{{ route('chambers.index') }}">Chamber</a>
                    </li>
                    <li>
                        <span>MAGMA v1</span>
                    </li>
                    <li>
                        <span>Gunung Api</span>
                    </li>
                    <li>
                        <span>VONA</span>
                    </li>
                    <li class="active">
                        <a href="{{ route('chambers.v1.vona.filter') }}">Filter</a>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Filter laporan VONA
            </h2>
            <small>Gunakan menu ini untuk mencari laporan VONA</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">
    <div class="row">
        <div class="col-lg-12">

            @role('Super Admin')
            @component('components.json-var')
                @slot('title')
                    For Developer
                @endslot
            @endcomponent
            @endrole

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

            @if (session()->has(('empty')))
                <div class="alert alert-danger">
                    {{ session('empty') }}
                </div>
            @endif

            <div class="hpanel">
                <div class="panel-heading">
                    Masukkan parameter
                </div>

                <div class="panel-body">
                    <form role="form" id="form" method="GET" action="{{ route('chambers.v1.vona.filter') }}">
                        <div class="tab-content">
                            <div id="step1" class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Masukkan parameter VONA yang ingin dicari. Meliputi Gunung Api, Color Code dan tanggal kapan datanya akan dicari.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">
                                            <div class="form-group">
                                                <label class="control-label">Gunung Api</label>
                                                <select id="gunungapi" class="form-control m-b" name="code">
                                                    @foreach ($gadds as $gadd)
                                                    <option value="{{ $gadd->ga_code }}">{{ $gadd->ga_nama_gapi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Color Code</label>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="checkbox">
                                                        <label><input name="colors[]" value="red" type="checkbox" class="i-checks"> Red</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        <label><input name="colors[]" value="orange" type="checkbox" class="i-checks"> Orange</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        <label><input name="colors[]" value="yellow" type="checkbox" class="i-checks"> Yellow</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        <label><input name="colors[]" value="green" type="checkbox" class="i-checks"> Green</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        <label><input name="colors[]" value="unassigned" type="checkbox" class="i-checks"> Unassigned</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Range Tanggal</label>
                                                <div class="input-group input-daterange">
                                                    <input id="start" type="text" class="form-control" value="{{ empty(old('start_date')) ? now()->subDays(14)->format('Y-m-d') : old('start_date')}}" name="start_date">
                                                    <div class="input-group-addon"> - </div>
                                                    <input id="end" type="text" class="form-control" value="{{ empty(old('end_date')) ? now()->format('Y-m-d') : old('end_date')}}" name="end_date">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" name="form" value="filter" type="submit">Filter</button>
                                                    <button class="btn btn-outline btn-primary" name="form" value="download" type="submit">Download</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (count($vonas))
            <div class="hpanel">
                <div class="panel-heading">
                    Hasil Pencarian - Total {{ $vonas->total() }} VONA Ditemukan
                </div>

                <div class="panel-body">
                    {{ $vonas->appends(request()->except('page'))->links() }}
                        <div class="table-responsive">
                            <table id="table-vona" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano</th>
                                        <th>Issued (UTC)</th>
                                        <th>Current Code</th>
                                        <th>Previous Code</th>
                                        <th>Cloud Height (ASL)</th>
                                        <th>Sender</th>
                                        <th style="min-width: 180px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vonas as $key => $vona)
                                    <tr>
                                        <td>{{ $vonas->firstItem()+$key }}</td>
                                        <td>{{ $vona->ga_nama_gapi }}</td>
                                        <td>{{ $vona->issued_time }}</td>
                                        <td>{{ $vona->cu_avcode }}</td>
                                        <td>{{ strtolower($vona->pre_avcode) }}</td>
                                        <td>{{ $vona->vc_height ? $vona->vc_height.' m' : 'Not observed' }}</td>
                                        <td>{{ $vona->nama }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-magma btn-outline" href="{{ route('chambers.v1.vona.show',['no' => $vona->no])}}" target="_blank">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    {{ $vonas->appends(request()->except('page'))->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    @role('Super Admin')
    $('#json-renderer').jsonViewer(@json($vonas), {collapsed: true});
    @endrole

    $.fn.datepicker.dates['id'] = {
        days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        daysMin: ['Mi', 'Se', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
        months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        today: 'Hari ini',
        clear: 'Bersihkan',
        titleFormat: 'MM yyyy',
        weekStart: 1
    };

    jQuery.validator.addMethod('isValid', function (value, element) {
        var startDate = $('#start').val();
        var endDate = $('#end').val();
        return Date.parse(startDate) <= Date.parse(endDate);
    }, '* Tanggal akhir harus setelah tanggal awal');

    $('.input-daterange').datepicker({
        startDate: '2015-05-01',
        endDate: '{{ now()->format('Y-m-d') }}',
        language: 'id',
        todayHighlight: true,
        todayBtn: 'linked',
        enableOnReadonly: false,
        format: 'yyyy-mm-dd',
    });

    $('.input-daterange input').each(function() {
        $(this).datepicker().on('changeDate', function(e){
            var startDate = $('#start').val(),
                endDate = $('#end').val(),
                isValid = Date.parse(startDate) <= Date.parse(endDate);
            console.log(isValid);
        });
    });
});
</script>
@endsection