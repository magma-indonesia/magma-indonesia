@extends('layouts.default')

@section('title')
    Gunung Api - Cari Laporan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('nav-search-laporanga')
    <li class="{{ active('laporan.gunungapi.search.*') }}">
        <a href="{{ route('laporan.gunungapi.search') }}">Cari Laporan</a>
    </li>
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chamber') }}">Chamber</a></li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Pencarian </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Hasil Pencarian
                </h2>
                <small>Memberikan hasil pencarian data laporan gunung api sesuai dengan parameter yang kita berikan</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-12">
                <div class="hpanel">
                    <div class="row">
                        <div class="col-md-12 col-lg-3">
                            <div class="hpanel">                     
                                <div class="panel-heading">
                                    Filter Laporan
                                </div>
                                <div class="panel-body">
                                    <div class="m-b-md">
                                        Masukkan parameter pencarian
                                    </div>
                                    <form role="form" id="form" method="GET" action="{{ route('laporan.gunungapi.search') }}">
                                        <div class="form-group">
                                            <label class="control-label">Nama Pelapor</label>
                                            <select id="nip" class="form-control m-b" name="nip">
                                                <option value="all" {{ !empty($input) ? $input['nip'] == 'all' ? 'selected' : '' : '' }}>- Pilih Semua-</option>
                                                @foreach($users as $user)
                                                <option value="{{ $user->nip }}" {{ !empty($input) ? $input['nip'] == $user->nip  ? 'selected' : '' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Gunung Api</label>
                                            <select id="gunungapi" class="form-control m-b" name="gunungapi">
                                                <option value="all" {{ !empty($input) ? $input['gunungapi'] == 'all' ? 'selected' : '' :'' }}>- Pilih Semua-</option>
                                                @foreach ($gadds as $gadd)
                                                <option value="{{ $gadd->code }}" {{ !empty($input) ? $input['gunungapi'] == $gadd->code ? 'selected' : '' : '' }}>{{ $gadd->name }}</option>      
                                                @endforeach
                                            </select>
                                            @if( $errors->has('gunungapi'))
                                            <label class="error" for="gunungapi">{{ ucfirst($errors->first('gunungapi')) }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Tipe Laporan</label>
                                            <select id="tipe" class="form-control m-b" name="tipe">
                                                <option value="all" {{ !empty($input) ? $input['tipe'] == 'all' ? 'selected' : '' : '' }}>- Pilih Semua-</option>                                                                                 
                                                <option value="24" {{ !empty($input) ? $input['tipe'] == 24 ? 'selected' : '' : '' }}>24 Jam</option>
                                                <option value="6" {{ !empty($input) ? $input['tipe'] == 6 ? 'selected' : '' : '' }}>6 Jam</option>                                             
                                            </select>
                                            @if( $errors->has('tipe'))
                                            <label class="error" for="tipe">{{ ucfirst($errors->first('tipe')) }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Range Laporan</label>
                                            <select id="jenis" class="form-control m-b" name="jenis">                                    
                                                <option value="0" {{ !empty($input) ? $input['jenis'] == 0 ? 'selected' : '' : '' }}>2 Mingguan</option>
                                                <option value="1" {{ !empty($input) ? $input['jenis'] == 1 ? 'selected' : '' : '' }}>Bulanan</option>    
                                                <option value="3" {{ !empty($input) ? $input['jenis'] == 3 ? 'selected' : '' : '' }}>Custom</option>   
                                            </select>
                                            @if( $errors->has('jenis'))
                                            <label class="error" for="jenis">{{ ucfirst($errors->first('jenis')) }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Bulan Awal</label>
                                            <input id="bulan" type="text" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}" name="bulan" {{ !empty($input) ? $input['jenis'] == 1 ? '' : 'disabled' : '' }}>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Range Tanggal (awal)</label>
                                            <div class="input-group input-daterange">
                                                <input id="start" type="text" class="form-control" value="{{ now()->subDays(14)->format('Y-m-d') }}" name="start" {{ !empty($input) ? $input['jenis'] == 1 ? 'disabled' : '' : '' }}>
                                                <div class="input-group-addon"> - </div>
                                                <input id="end" type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" name="end" {{ !empty($input) ? $input['jenis'] == 1 ? 'disabled' : '' : '' }}>
                                            </div>
                                            @if( $errors->has('start'))
                                            <label class="error" for="start">{{ ucfirst($errors->first('start')) }}</label>
                                            @endif
                                            @if( $errors->has('end'))
                                            <label class="error" for="end">{{ ucfirst($errors->first('end')) }}</label>
                                            @endif
                                        </div>
                                        <button class="btn btn-success btn-block" type="submit">Apply</button>
                                    </form>
                                </div>
                            </div>
                        </div>     
    
                        <div class="col-md-12 col-lg-9">
                            <div id="all-vars" class="hpanel">
                                <div class="panel-heading">
                                    Hasil Pencarian
                                </div>
                                <div class="panel-body list">
                                    @if(!empty($flash_message))
                                    <div class="alert alert-danger">
                                        <i class="fa fa-bolt"></i> {{ $flash_message }}
                                    </div>
                                    @endif
                                    @if(!empty($flash_result))
                                    <div class="alert alert-success">
                                        <i class="fa fa-bolt"></i> {{ $flash_result }}
                                    </div>                                    
                                    <div class="text-center">
                                    {{ $vars->appends(Request::except('page'))->links() }}
                                    </div>
                                    <div class="table-responsive project-list">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Laporan</th>
                                                    <th>Jenis Laporan</th>
                                                    <th>Pembuat Laporan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vars as $var)
                                                <tr>
                                                    <td>Laporan Gunungapi {{ $var->gunungapi->name }}
                                                        <br/>
                                                        <small>
                                                            <i class="fa fa-clock-o"></i> Tanggal : {{ $var->var_data_date->formatLocalized('%d %B %Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="pie">{{ $var->var_perwkt.' Jam, '.$var->periode }}</span>
                                                    </td>
                                                    <td>{{ $var->user->name }}</td>
                                                    <td>
                                                        <a href="">
                                                            <a href="{{ route('laporanga.show',$var->noticenumber ) }}" target="_blank" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                    {{ $vars->appends(Request::except('page'))->links() }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables -->
    <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {
            var endDateForm = $('#end').closest('.form-group'),
                bulanForm = $('#bulan').closest('.form-group');

            $('#nip').on('change', function(e){
                var $nip = $('#nip').val();
                console.log($nip);
            });

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

            jQuery.validator.addMethod('isValid', function (value, element) {
                var startDate = $('#start').val();
                var endDate = $('#end').val();
                return Date.parse(startDate) <= Date.parse(endDate);
            }, '* Tanggal akhir harus setelah tanggal awal');

            $('#bulan').datepicker({
                startDate: '2015-05-01',
                endDate: '{{ now()->format('Y-m-d') }}',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: false,
                minViewMode: 1,
                maxViewMode: 2
            });

            $('.input-daterange').datepicker({
                startDate: '2015-05-01',
                endDate: '{{ now()->format('Y-m-d') }}',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: false
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
                    case '1':
                        console.log(jenisVal);
                        $('#start, #end').prop('disabled',true);
                        $('#bulan').prop('disabled',false); 
                        break;
                    default:
                        console.log(jenisVal);        
                        $('#start, #end').prop('disabled',false);
                        $('#bulan').prop('disabled',true);                      
                        break;
                } 
            });

            $("#form").validate({
                rules: {
                    gunungapi: {
                        required: true
                    },
                    start: {
                        required: true,
                        date: true
                    },
                    end: {
                        required: true,
                        date: true,
                        isValid: true
                    }
                },
                messages: {
                    gunungapi: {
                        required: 'Harap pilih gunung api'
                    },
                    start: {
                        required: 'Harap masukkan tanggal',
                        date: 'Format tanggal salah (yyyy-mm-dd)',
                        isValid: 'Tanggal akhir harus setelah tanggal awal'
                    },
                    end: {
                        required: 'Harap masukkan tanggal',
                        date: 'Format tanggal salah (yyyy-mm-dd)',
                        isValid: 'Tanggal akhir harus setelah tanggal awal'
                    }
                },
                errorPlacement: function(error, element) {
                    var endDate = $('#end').closest('.form-group');
                    error.appendTo(endDate);
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });    

        });

    </script>
@endsection