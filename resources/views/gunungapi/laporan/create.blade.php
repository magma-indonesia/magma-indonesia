@extends('layouts.default')

@section('title')
    Create VAR
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
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.laporan.create') }}">Buat VAR</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru.</small>
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
                        Form MAGMA-VAR
                    </div>
                    <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-primary" href="#" >Step 1 - Data Laporan</a>
                                <a class="btn btn-default" href="#" disabled>Step 2 - Data Visual</a>
                                <a class="btn btn-default" href="#" disabled>Step 3 - Data Kegempaan</a>
                            </div>
    
                            <div class="tab-content">
                                <div id="step1" class="p-m tab-pane active">
        
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Buat Laporan MAGMA-VAR</strong>, form ini digunakan untuk input data laporan gunung api, meliputi laporan visual dan instrumental.
                                                <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pembuat</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Pos Gunung Api</label>
                                                    @if( $errors->has('code'))
                                                    <label class="error" for="code">{{ ucfirst($errors->first('code')) }}</label>
                                                    @endif
                                                    <select id="code" class="form-control" name="code">
                                                        @foreach($pgas as $pga)
                                                        <option value="{{ $pga->obscode }}" {{ old('code') == $pga->obscode ? 'selected' : ''}}>{{ $pga->observatory }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Status</label>
                                                    @if( $errors->has('status'))
                                                    <label class="error" for="status">{{ ucfirst($errors->first('status')) }}</label>
                                                    @endif
                                                    <select id="status" class="form-control" name="status">
                                                        <option value="1" {{ old('status') == '1' ? 'selected' : ''}}>Level I (Normal)</option>
                                                        <option value="2" {{ old('status') == '2' ? 'selected' : ''}}>Level II (Waspada)</option>
                                                        <option value="3" {{ old('status') == '3' ? 'selected' : ''}}>Level III (Siaga)</option>
                                                        <option value="4" {{ old('status') == '4' ? 'selected' : ''}}>Level IV (Awas)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Tanggal Laporan</label>
                                                    @if( $errors->has('date'))
                                                    <label class="error" for="date">{{ ucfirst($errors->first('date')) }}</label>
                                                    @endif
                                                    <input name="date" id="date" class="form-control" type="text" value="{{ empty(old('date')) ? now()->format('Y-m-d') : old('date') }}">
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Periode</label>
                                                    @if( $errors->has('periode'))
                                                    <label class="error" for="periode">{{ ucfirst($errors->first('periode')) }}</label>
                                                    @endif
                                                    <select id="periode" class="form-control" name="periode">
                                                        <optgroup label="24 Jam">
                                                            <option value="00:00-24:00" {{ old('periode') == '00:00-24:00' ? 'selected' : ''}}>Pukul 00:00-24:00</option>
                                                        </optgroup>
                                                        <optgroup label="6 Jam">
                                                                <option value="00:00-06:00" {{ old('periode') == '00:00-06:00' ? 'selected' : ''}}>Pukul 00:00-06:00</option>
                                                                <option value="06:00-12:00" {{ old('periode') == '06:00-12:00' ? 'selected' : ''}}>Pukul 06:00-12:00</option>
                                                                <option value="12:00-18:00" {{ old('periode') == '12:00-18:00' ? 'selected' : ''}}>Pukul 12:00-18:00</option>
                                                                <option value="18:00-24:00" {{ old('periode') == '18:00-24:00' ? 'selected' : ''}}>Pukul 18:00-24:00</option>
                                                        </optgroup>
                                                    </select>
                                                </div>

                                                <div class="form group col-lg-12">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            00:00-06:00
                                                            <span class="pull-right"><i class="periode_0 text-danger fa fa-close"></i></span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            06:00-12:00
                                                            <span class="pull-right"><i class="periode_1 text-danger fa fa-close"></i></span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            12:00-18:00
                                                            <span class="pull-right"><i class="periode_2 text-danger fa fa-close"></i></span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            18:00-24:00
                                                            <span class="pull-right"><i class="periode_3 text-danger fa fa-close"></i></span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            00:00-24:00
                                                            <span class="pull-right"><i class="periode_4 text-danger fa fa-close"></i></span>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="text-right m-t-xs">
                                        <button type="submit" class="submit btn btn-primary" href="#">Step 1 - Submit</button>
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
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {

            var $showUrl = '{{ route('chambers.laporan.index') }}/',
                data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: $('#code').val(),
                    date: $('#date').val(),
                    periode: $('#periode').val()
                };

            function exist(data) {
                $.ajax({
                    url: "{{ route('chambers.laporan.exists') }}",
                    type: 'POST',
                    data: data,
                    success: function(response, status, xhr) {
                        response.length == 5 ? $('.submit').hide() : $('.submit').show();
                        replaceClass(response);
                    },
                    error: function(response) {
                        resetClass();
                        console.log(response.responseJSON);
                    }
                });
            };

            function replaceIfExists(index,noticenumber) {
                var $exists = '<a href="'+$showUrl+noticenumber+'" class="periode_'+index+' btn btn-xs btn-outline btn-success" target="_blank">View <i class="text-sucees fa fa-check"></i></a>';
                $('.periode_'+index).replaceWith($exists);
                console.log('exists');
            }

            function getElement(periode,noticenumber) {
                switch (periode) {
                    case '00:00-06:00':
                        replaceIfExists('0',noticenumber);
                        break;
                    case '06:00-12:00':
                        replaceIfExists('1',noticenumber);
                        break;
                    case '12:00-18:00':
                        replaceIfExists('2',noticenumber);
                        break;
                    case '18:00-24:00':
                        replaceIfExists('3',noticenumber);
                        break;
                    default:
                        replaceIfExists('4',noticenumber);
                        break;
                }
            }

            function resetClass() {
                $('[class^="periode_"]').each(function(index) {
                    var $default = '<i class="periode_'+index+' text-danger fa fa-close"></i>';
                    $('.periode_'+index).replaceWith($default);
                });
            }

            function replaceClass(response) {
                resetClass();
                $.each(response, function(index, value) {
                   getElement(value.periode,value.noticenumber);
                });
            }

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

            $('#date').datepicker({
                startDate: '2015-05-01',
                endDate: '{{ now()->format('Y-m-d') }}',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: true,
                minViewMode: 0,
                maxViewMode: 2,
                readOnly: true,
            }).on('changeDate',function(e) {
                var data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: $('#code').val(),
                    date: this.value,
                    periode: $('#periode').val()
                };
                exist(data);
            });

            $('#code, #periode').change(function(e) {
                var data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: $('#code').val(),
                    date: $('#date').val(),
                    periode: $('#periode').val()
                };
                exist(data);
            });

            exist(data);
        });
    </script>
@endsection