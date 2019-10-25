@extends('layouts.default')

@section('title')
    v1 - Evaluasi
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
                        <li class="active">
                            <a href="{{ route('chambers.v1.gunungapi.laporan.index') }}">Evaluasi</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Evaluasi Gunung Api
                </h2>
                <small>Buat evaluasi gunung api.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">

        @if ($stats->isNotEmpty())
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Evaluasi Terakhir
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Gunung Api</th>
                                        <th>Periode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stats as $stat)
                                    <tr>
                                        <td>{{ $stat->user->name }}</td>
                                        <td>{{ $stat->gunungapi->name }}</td>
                                        <td>{{ $stat->start->format('Y-m-d') }} - {{ $stat->end->format('Y-m-d') }} - {{ $stat->jumlah_hari }} hari</td>
                                        <td><a href="{{ $stat->url }}" class="btn btn-sm btn-magma btn-outline" target="_blank">View</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Masukkan parameter
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="GET" action="{{ route('chambers.v1.gunungapi.evaluasi.result') }}">
                            <div class="tab-content">
                                <div id="step1" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-4 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Masukkan parameter evaluasi</strong>, gunakan form menu ini untuk membuat grafik dan rangkuman visual dan kegempaan gunung api.
                                                <br/><br/>Laporan tipe 2 minggu dan bulanan selama periode Januari hingga Juni akan menggunakan tanggal awal 1 Juli tahun sebelumnya sebagai tanggal awal untuk plot grafik. <br/><br/>Gunakan pilihan custom jika ingin melihat data dalam periode tertentu.
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

                                            <div class="row p-md">

                                                <div class="form-group">
                                                    <label class="control-label">Gunung Api</label>
                                                    <select id="gunungapi" class="form-control m-b" name="code">
                                                        @foreach ($gadds as $gadd)
                                                        <option value="{{ $gadd->ga_code }}" {{ old('gunungapi') == $gadd->ga_code ? 'selected' : '' }}>{{ $gadd->ga_nama_gapi }}</option>      
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Range Laporan</label>
                                                    <select id="jenis" class="form-control m-b" name="jenis">
                                                        <option value="0" {{ old('jenis') == 0 || empty(old('tipe')) ? 'selected' : '' }}>2 Mingguan</option>
                                                        <option value="1" {{ old('jenis') == 1 ? 'selected' : '' }}>Bulanan</option>
                                                        <option value="2" {{ old('jenis') == 2 ? 'selected' : '' }}>Custom</option>
                                                    </select>
                                                </div>
                            
                                                <div class="form-group">
                                                    <label class="control-label">Bulan Awal</label>
                                                    <input id="bulan" type="text" class="form-control" value="{{ empty(old('bulan')) ? now()->subMonth()->startOfMonth()->format('Y-m-d') : old('bulan')}}" name="start" {{ old('jenis') == 1 ? '' : 'disabled' }}>
                                                </div>
                            
                                                <div class="form-group">
                                                    <label class="control-label">Range Tanggal/Tanggal Awal</label>
                                                    <div class="input-group input-daterange">
                                                        <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(14)->format('Y-m-d') : old('start')}}" name="start" {{ old('jenis') == 1 ? 'disabled' : '' }}>
                                                        <div class="input-group-addon"> - </div>
                                                        <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end" {{ old('jenis') == 2 ? '' : 'disabled' }}>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pilih Jenis Gempa</label>
                                                    <div class="row">
                                                        @foreach ($gempas->chunk(10) as $gempa)
                                                        <div class="col-lg-6">
                                                            @foreach ($gempa as $code=>$name)
                                                            <div class="checkbox">
                                                            <label><input name="gempa[]" value="{{ $code }}" type="checkbox" class="i-checks gempa" checked> {{ $name }}</label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @endforeach
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label>Pilih Semua Gempa</label> 
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" class="i-checks all" checked> Check All</label>    
                                                    </div>
                                                </div>

                                                <hr>
                                                <button class="btn btn-magma" type="submit">Apply</button>
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
    <!-- DataTables -->
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {

            var $checkAll = $('input.all'),
                $checkboxes = $('input.gempa');

            $checkAll.on('ifChecked ifUnchecked', function(event) {        
                if (event.type == 'ifChecked') {
                    $checkboxes.iCheck('check');
                } else {
                    $checkboxes.iCheck('uncheck');
                }
            });

            $checkboxes.on('ifChanged', function(event){
                if($checkboxes.filter(':checked').length == $checkboxes.length) {
                    $checkAll.prop('checked', 'checked');
                } else {
                    $checkAll.removeProp('checked');
                }
                $checkAll.iCheck('update');
            });

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

            $('#bulan').datepicker({
                startDate: '2015-05-01',
                endDate: '{{ now()->subMonth()->format('Y-m-d') }}',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: false,
                minViewMode: 1,
                maxViewMode: 2,
                format: 'yyyy-mm-dd',
            });

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

            $('#jenis').on('change load',function(e){
                var jenisVal = $(this).val();
                switch(jenisVal) {
                    case '0':
                        console.log(jenisVal);
                        $('#bulan, #end').prop('disabled',true);
                        $('#start').prop('disabled',false);
                        break;
                    case '1':
                        console.log(jenisVal);
                        $('#bulan').prop('disabled',false); 
                        $('#start, #end').prop('disabled',true);
                        break;
                    default:
                        console.log(jenisVal);
                        $('#start, #end').prop('disabled',false);
                        $('#bulan').prop('disabled',true);                     
                        break;
                } 
            });
        });
    </script>
@endsection