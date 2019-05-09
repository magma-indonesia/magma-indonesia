@extends('layouts.default')

@section('title')
    v1 - Buat Laporan
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
                            <span>Buat Laporan</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Buat Laporan Gunung Api
                </h2>
                <small>Gunakan Form ini untuk membuat laporan gunung api (VAR)</small>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                @role('Super Admin')
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent
                @endrole
                <div class="hpanel">
                    <div class="panel-heading">
                        Form MAGMA-VAR
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.v1.gunungapi.laporan.store.var') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-primary m-b" href="#">Step 1 - <span class="hidden-xs">Data Laporan</span></a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 2 - Rekomendasi</a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 3 - Visual</a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 4 - Klimatologi</a>
                                <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 5 - Kegempaan</a>
                            </div>
                            <hr>
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

                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pembuat Laporan</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>

                                                <div class="form-group col-lg-6">
                                                    <label>Pos Gunung Api</label>
                                                    <select id="code" class="form-control" name="code">
                                                        @foreach($pgas as $pga)
                                                        <option value="{{ $pga->obscode }}" {{ (old('code') == $pga->obscode) || ($var['code'] == $pga->obscode) ? 'selected' : ''}}>{{ $pga->observatory }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-lg-6">
                                                    <label>Status</label>
                                                    <select id="status" class="form-control" name="status">
                                                        <option value="Level I (Normal)" {{ (old('status') == 'Level I (Normal)') || ($var['cu_status'] == 'Level I (Normal)') ? 'selected' : ''}}>Level I (Normal)</option>
                                                        <option value="Level II (Waspada)" {{ (old('status') == 'Level II (Waspada)') || ($var['cu_status'] == 'II (Waspada)')? 'selected' : ''}}>Level II (Waspada)</option>
                                                        <option value="Level III (Siaga)" {{ (old('status') == 'Level III (Siaga)') || ($var['cu_status'] == 'Level III (Siaga)')? 'selected' : ''}}>Level III (Siaga)</option>
                                                        <option value="Level IV (Awas)" {{ (old('status') == 'Level IV (Awas)') || ($var['cu_status'] == 'Level IV (Awas)')? 'selected' : ''}}>Level IV (Awas)</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-lg-6">
                                                    <label>Tanggal Laporan </label>
                                                    @if (!empty($var['var_data_date']))
                                                    <input name="date" id="date" class="form-control" type="text" value="{{ $var['var_data_date'] }}">
                                                    @elseif (!empty(old('date')))
                                                    <input name="date" id="date" class="form-control" type="text" value="{{ old('date') }}">
                                                    @else
                                                    <input name="date" id="date" class="form-control" type="text" value="{{ now()->format('Y-m-d') }}">
                                                    @endif
                                                </div>

                                                <div class="form-group col-lg-6">
                                                    <label>Periode</label>
                                                    <select id="periode" class="form-control" name="periode">
                                                        <optgroup label="24 Jam">
                                                            <option value="00:00-24:00" {{ (old('periode') == '00:00-24:00') || ($var['periode'] == '00:00-24:00') ? 'selected' : ''}}>Pukul 00:00-24:00</option>
                                                        </optgroup>
                                                        <optgroup label="6 Jam">
                                                            <option value="00:00-06:00" {{ (old('periode') == '00:00-06:00') || ($var['periode'] == '00:00-06:00') ? 'selected' : ''}}>Pukul 00:00-06:00</option>
                                                            <option value="06:00-12:00" {{ (old('periode') == '06:00-12:00') || ($var['periode'] == '06:00-12:00') ? 'selected' : ''}}>Pukul 06:00-12:00</option>
                                                            <option value="12:00-18:00" {{ (old('periode') == '12:00-18:00') || ($var['periode'] == '12:00-18:00') ? 'selected' : ''}}>Pukul 12:00-18:00</option>
                                                            <option value="18:00-24:00" {{ (old('periode') == '18:00-24:00') || ($var['periode'] == '18:00-24:00') ? 'selected' : ''}}>Pukul 18:00-24:00</option>
                                                        </optgroup>
                                                    </select>
                                                </div>

                                                <div class="form group col-lg-12">
                                                    <label>Status Laporan</label>
                                                    <ul class="list-group form-group">
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

                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <button type="submit" class="submit btn btn-primary" href="#"> Berikutnya <i class="fa fa-angle-double-right"></i></button>
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
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            @role('Super Admin')
            $('#json-renderer').jsonViewer(@json(session()->all()), {collapsed: true});
            @endrole
            
            var $showUrl = '{{ route('chambers.v1.gunungapi.laporan.index') }}/',
                data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: $('#code').val(),
                    date: $('#date').val(),
                    periode: $('#periode').val()
                };

            function exist(data) {
                $.ajax({
                    url: "{{ route('chambers.v1.gunungapi.laporan.exists') }}",
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
                   getElement(value.periode,value.no);
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