@extends('layouts.default')

@section('title')
    Gunung Api - Cari Laporan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
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
            <div class="col-md-12 col-lg-3">
                <div class="hpanel">                     
                    <div class="panel-heading">
                        Filter Laporan
                    </div>
                    <div class="panel-body">
                        <div class="m-b-md">
                            Masukkan parameter pencarian
                        </div>
                        <form role="form" id="form" method="GET" action="{{ route('chambers.laporan.search') }}">
                            <div class="form-group">
                                <label class="control-label">Nama Pelapor</label>
                                <select id="nip" class="form-control m-b" name="nip">
                                    <option value="all" {{ old('nip') == 'all' || empty(old('nip')) ? 'selected' : '' }}>- Pilih Semua-</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->nip }}" {{ old('nip') == $user->nip ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Gunung Api</label>
                                <select id="gunungapi" class="form-control m-b" name="gunungapi">
                                    <option value="all" {{ old('gunungapi') == 'all' || empty(old('gunungapi')) ? 'selected' : '' }}>- Pilih Semua-</option>
                                    @foreach ($gadds as $gadd)
                                    <option value="{{ $gadd->code }}" {{ old('gunungapi') == $gadd->code ? 'selected' : '' }}>{{ $gadd->name }}</option>      
                                    @endforeach
                                </select>
                                @if( $errors->has('gunungapi'))
                                <label class="error" for="gunungapi">{{ ucfirst($errors->first('gunungapi')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipe Laporan</label>
                                <select id="tipe" class="form-control m-b" name="tipe">
                                    <option value="all" {{ old('tipe') == 'all' || empty(old('tipe')) ? 'selected' : '' }}>- Pilih Semua-</option>                                                                                 
                                    <option value="24" {{ old('tipe') == 24 ? 'selected' : '' }}>24 Jam</option>
                                    <option value="6" {{ old('tipe') == 6 ? 'selected' : '' }}>6 Jam</option>                                            
                                </select>
                                @if( $errors->has('tipe'))
                                <label class="error" for="tipe">{{ ucfirst($errors->first('tipe')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Range Laporan</label>
                                <select id="jenis" class="form-control m-b" name="jenis">                                    
                                    <option value="0" {{ old('jenis') == 0 || empty(old('tipe')) ? 'selected' : '' }}>2 Mingguan</option>
                                    <option value="1" {{ old('jenis') == 1 ? 'selected' : '' }}>Bulanan</option>
                                    <option value="3" {{ old('jenis') == 3 ? 'selected' : '' }}>Custom</option>
                                </select>
                                @if( $errors->has('jenis'))
                                <label class="error" for="jenis">{{ ucfirst($errors->first('jenis')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Bulan Awal</label>
                                <input id="bulan" type="text" class="form-control" value="{{ empty(old('bulan')) ? now()->startOfMonth()->format('Y-m-d') : old('bulan')}}" name="bulan" {{ old('jenis') == 1 ? '' : 'disabled' }}>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Range Tanggal (awal)</label>
                                <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(14)->format('Y-m-d') : old('start')}}" name="start" {{ old('jenis') == 1 ? 'disabled' : '' }}>
                                    <div class="input-group-addon"> - </div>
                                    <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end" {{ old('jenis') == 3 ? '' : 'disabled' }}>
                                </div>
                                @if( $errors->has('start'))
                                <label class="error" for="start">{{ ucfirst($errors->first('start')) }}</label>
                                @endif
                                @if( $errors->has('end'))
                                <label class="error" for="end">{{ ucfirst($errors->first('end')) }}</label>
                                @endif
                            </div>
                            <button class="btn btn-magma btn-block" type="submit">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-9">
                <div class="hpanel">
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
                        <div class="row">
                            <div class="col-md-6">
                                {{ $vars->appends(Request::except('page'))->links() }}
                            </div>
                            <div class="col-md-6">
                                <div class="pagination pull-right">
                                    <a href="{{ route('chambers.export',['type' => 'var',Request::getQueryString()]) }}" type="button" class="btn btn-sm btn-magma m-b-t">Save to Excel</a>
                                </div>
                            </div>
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
                                            <a href="{{ route('chambers.laporan.show',$var->noticenumber ) }}" target="_blank" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>

                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.laporan.destroy', $var->noticenumber) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                            @endrole
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $vars->appends(Request::except('page'))->links() }}                        
                        @endif
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
    @role('Super Admin')
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
    @endrole
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            @role('Super Admin')
            $('body').on('submit','#deleteForm',function (e) {
                e.preventDefault();                

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();

                swal({
                    title: "Anda yakin?",
                    text: "Data yang telah dihapus tidak bisa dikembalikan",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, hapus!",
                    cancelButtonText: "Gak jadi deh!",
                    closeOnConfirm: false,
                    closeOnCancel: true },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: $url,
                            data: $data,
                            type: 'POST',
                            success: function(data){
                                console.log(data);
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    setTimeout(function(){
                                        location.reload();
                                    },2000);
                                }
                            },
                            error: function(data){
                                var $errors = {
                                    'status': data.status,
                                    'exception': data.responseJSON.exception,
                                    'file': data.responseJSON.file,
                                    'line': data.responseJSON.line
                                };
                                console.log($errors);
                                swal("Gagal!", data.responseJSON.exception, "error");
                            }
                        });
                    }
                });

                return false;
            });
            @endrole

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