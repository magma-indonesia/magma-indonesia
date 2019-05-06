@extends('layouts.default')

@section('title')
    Tambahkan Stakeholder
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
                            <a href="{{ route('chambers.stakeholder.index') }}">Stakeholder</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.stakeholder.create') }}">Create</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Tambah Stakeholder 
                </h2>
                <small>Tambahkan Stakeholder terkait untuk diberikan akses MAGMA.</small>
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
                        Tambahkan Stakeholder
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.stakeholder.store')}}" enctype="multipart/form-data">
                            @csrf
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
                                        <hr>

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
                                                    <label>Nama Pembuat API</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Aplikasi</label>
                                                    <input type="text" value="{{ old('app_name') }}" id="app_name" class="form-control" name="app_name" placeholder="Masukkan nama aplikasi">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Instansi/Lembaga</label>
                                                    <input type="text" value="{{ old('instansi') }}" id="instansi" class="form-control" name="instansi" placeholder="Masukkan nama instansi/lembaga pemilik aplikasi">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pemohon</label>
                                                    <input type="text" value="{{ old('nama') }}" id="nama" class="form-control" name="nama" placeholder="Masukkan nama pemohon dari instansi terkait">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>No Handphone</label>
                                                    <input type="text" value="{{ old('phone') }}" id="phone" class="form-control" name="phone" placeholder="Masukkan nomor HP pemohon dari instansi terkait">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Email</label>
                                                    <input type="text" value="{{ old('email') }}" id="email" class="form-control" name="email" placeholder="Masukkan email pemohon dari instansi terkait">
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Aktifkan API</label>
                                                    <select id="code" class="form-control" name="code">
                                                        <option value="0" selected>Tidak</option>
                                                        <option value="1">Ya</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Expire</label>
                                                    <input name="date" id="date" class="form-control" type="text" value="{{ old('date') ?: now()->addDays(30)->format('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <button type="submit" class="submit btn btn-primary" href="#"> Submit <i class="fa fa-angle-double-right"></i></button>
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

            $('#date').datepicker({
                startDate: '{{ now()->format('Y-m-d') }}',
                endDate: '{{ now()->addYear()->format('Y-m-d') }}',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: true,
                minViewMode: 0,
                maxViewMode: 2,
                readOnly: true,
            });
        });
    </script>
@endsection