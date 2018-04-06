@extends('layouts.default')

@section('title')
    Gunung Api | Laporan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/select2-3.5.2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap/select2-bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('content-body')   
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Laporan Gunung Api
                </h2>
                <p>
                    Memberikan informasi Laporan Gunung Api yang telah masuk dan 
                    <strong>Daftar Laporan Harian</strong> terkini 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Laporan Harian per Gunungapi
                    </div>
                    <div class="panel-body list">
                        <div class="table-responsive project-list">
                            <table class="table table-striped table-daily">
                                <thead>
                                    <tr>
                                        <th>Gunungapi</th>
                                        <th>Laporan Terakhir</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gadds as $gadd)
                                    <tr>
                                        <td class="{{ $gadd->latestVar->statuses_desc_id }}">{{ $gadd->name }}</td>
                                        <td>
                                            <span class="pie">{{ $gadd->latestVar->var_data_date->formatLocalized('%A, %d %B %Y').', '.$gadd->latestVar->periode }}</span>
                                        </td>  
                                        <td>
                                            <a href="{{ route('laporanga.show',$gadd->latestVar->noticenumber ) }}" target="_blank" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-3">
                <div class="hpanel">
                    <form role="form" id="form" method="GET" action="{{ route('laporan.gunungapi.search') }}">                     
                        <div class="panel-heading">
                            Filter Laporan
                        </div>
                        <div class="panel-body">
                            <div class="m-b-md">
                                Masukkan parameter pencarian
                            </div>
                            <div class="form-group">
                                <label class="control-label">Gunung Api</label>
                                <select id="gunungapi" class="form-control m-b" name="gunungapi">                                    
                                    @foreach ($gadds as $gadd)
                                    <option value="{{ $gadd->code }}" {{ old('gunungapi') == $gadd->code || empty(old('gunungapi')) && $loop->first ? 'selected' : ''}}>{{ $gadd->name }}</option>      
                                    @endforeach
                                </select>
                                @if( $errors->has('gunungapi'))
                                <label class="error" for="gunungapi">{{ ucfirst($errors->first('gunungapi')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Range Laporan</label>
                                <select id="jenis" class="form-control m-b" name="jenis">                                    
                                    <option value="0" {{ old('jenis') == 0 || empty(old('jenis')) ? 'selected' : ''}}>2 Mingguan</option>
                                    <option value="1" {{ old('jenis') == 1 ? 'selected' : ''}}>Bulanan</option>    
                                    <option value="2" {{ old('jenis') == 2 ? 'selected' : ''}}>6 Bulanan</option>
                                    <option value="3" {{ old('jenis') == 3 ? 'selected' : ''}}>Custom</option>                                            
                                </select>
                                @if( $errors->has('jenis'))
                                <label class="error" for="jenis">{{ ucfirst($errors->first('jenis')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Bulan Awal</label>
                                <input id="bulan" type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" name="bulan" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Range Tanggal</label>
                                <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" value="{{ now()->subDays(14)->format('Y-m-d') }}" name="start" disabled>
                                    <div class="input-group-addon"> - </div>
                                    <input id="end" type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" name="end" disabled>
                                </div>
                                @if( $errors->has('start'))
                                <label class="error" for="start">{{ ucfirst($errors->first('start')) }}</label>
                                @endif
                                @if( $errors->has('end'))
                                <label class="error" for="end">{{ ucfirst($errors->first('end')) }}</label>
                                @endif
                            </div>

                            <button class="btn btn-success btn-block" type="submit">Apply</button>

                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12 col-lg-9">
                <div id="all-vars" class="hpanel">
                    <div class="panel-heading">
                        Semua Laporan Gunung Api
                    </div>
                    <div class="panel-body list">
                        <div class="text-center">
                        {{ $vars->links() }}
                        </div>
                        <div class="table-responsive project-list">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Laporan</th>
                                        <th>Jenis Laporan</th>
                                        {{--  <th>Tanggal</th>  --}}
                                        <th>Pembuat Laporan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vars as $var)
                                    <tr>
                                    <td class="{{ $var->status->id }}">Laporan Gunungapi {{ $var->gunungapi->name }}
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Tanggal : {{ $var->var_data_date->formatLocalized('%d %B %Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="pie">{{ $var->var_perwkt.' Jam, '.$var->periode }}</span>
                                        </td>
                                        {{--  <td>
                                            <strong>{{ $var->var_data_date->diffForHumans() }}</strong>
                                        </td>  --}}
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
                        {{ $vars->links() }}
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
    <script src="{{ asset('vendor/select2-3.5.2/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {
            var endDateForm = $('#end').closest('.form-group'),
                bulanForm = $('#bulan').closest('.form-group'),
                hideEndDateForm = endDateForm.show();
            
            bulanForm.hide();
            $('#start').prop('disabled',false);
            $('#end, .input-group-addon').hide();

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

            // Initialize table
            $('.table-daily').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-2 text-center'B><'col-sm-6'f>>tp",
                "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]]
            });

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

            $('#jenis').on('change',function(e){
                var jenisVal = $(this).val();
                switch(jenisVal) {
                    case '0':
                        console.log(jenisVal);
                        endDateForm.show();
                        bulanForm.hide();                 
                        $('#start').prop('disabled',false);
                        $('#bulan, #end').prop('disabled',true);                        
                        $('#end, .input-group-addon').hide();
                        break;
                    case '1':
                        console.log(jenisVal);
                        endDateForm.hide();
                        bulanForm.show(); 
                        $('#start, #end').prop('disabled',true);
                        $('#bulan').prop('disabled',false); 
                        break;
                    case '2':
                        console.log(jenisVal);
                        endDateForm.hide();
                        bulanForm.show(); 
                        $('#start, #end').prop('disabled',true);
                        $('#bulan').prop('disabled',false);
                        break;
                    default:
                        console.log(jenisVal);
                        bulanForm.hide();    
                        $('#start, #end').prop('disabled',false);
                        $('#bulan').prop('disabled',true);  
                        $('#end, .input-group-addon').show();
                        endDateForm.show();
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
    
            $(".select2").select2();
    

        });

    </script>
@endsection