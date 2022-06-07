@extends('layouts.default')

@section('title')
    Edit Stakeholder - {{ $stakeholder->app_name }}
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
                            <a href="{{ route('chambers.stakeholder.edit', ['id' => $stakeholder->id]) }}">Edit</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Edit Stakeholder - {{ $stakeholder->app_name }}
                </h2>
                <small>Edit Stakeholder untuk aplikasi {{ $stakeholder->app_name }}, {{ $stakeholder->organisasi }}.</small>
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
                        Edit Stakeholder
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.stakeholder.update', ['id' => $stakeholder->id])}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content">
                                <div id="step1" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Edit data Stakeholder MAGMA</strong>, form ini digunakan untuk mengubah sakeholder pengguna data MAGMA Indonesia.
                                                <br/><br/>Hak akses dibagi menjadi beberapa bagian, pilih salah satu yang mewakili. Sertakan tanggal kapan berlakunya API bisa digunakan.
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
                                                    <input type="text" value="{{ old('app_name') ?: $stakeholder->app_name }}" id="app_name" class="form-control" name="app_name" placeholder="Masukkan nama aplikasi">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Instansi/Lembaga</label>
                                                    <input type="text" value="{{ old('instansi') ?: $stakeholder->organisasi }}" id="instansi" class="form-control" name="instansi" placeholder="Masukkan nama instansi/lembaga pemilik aplikasi">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pemohon</label>
                                                    <input type="text" value="{{ old('nama') ?: $stakeholder->kontak_nama }}" id="nama" class="form-control" name="nama" placeholder="Masukkan nama pemohon dari instansi terkait">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>No Handphone</label>
                                                    <input type="text" value="{{ old('phone') ?: $stakeholder->kontak_phone }}" id="phone" class="form-control" name="phone" placeholder="Masukkan nomor HP pemohon dari instansi terkait">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Email</label>
                                                    <input type="text" value="{{ old('email') ?: $stakeholder->kontak_email}}" id="email" class="form-control" name="email" placeholder="Masukkan email pemohon dari instansi terkait">
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Hak Akses</label>
                                                    <select id="type" class="form-control" name="type">
                                                        <option value="private" {{ $stakeholder->api_type == 'private' ? 'selected' : '' }}>Private</option>
                                                        <option value="public" {{ $stakeholder->api_type == 'public' ? 'selected' : '' }}>Public</option>
                                                        <option value="internal" {{ $stakeholder->api_type == 'internal' ? 'selected' : '' }}>Internal - Semua Bidang</option>
                                                        <option value="internal-mga" {{ $stakeholder->api_type == 'internal-mga' ? 'selected' : '' }}>Internal - MGA</option>
                                                        <option value="internal-mgb" {{ $stakeholder->api_type == 'internal-mgb' ? 'selected' : '' }}>Internal - MGB</option>
                                                        <option value="internal-mgt" {{ $stakeholder->api_type == 'internal-mgt' ? 'selected' : '' }}>Internal - MGT</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Aktifkan API</label>
                                                    <select id="code" class="form-control" name="status">
                                                        <option value="0" {{ $stakeholder->status == '0' ? 'selected' : '' }}>Tidak</option>
                                                        <option value="1" {{ $stakeholder->status == '1' ? 'selected' : '' }}>Ya</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label>Expire At</label>
                                                    <input name="date" id="date" class="form-control" type="text" value="{{ old('date') ?: $stakeholder->expired_at->format('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <button type="submit" class="submit btn btn-primary" href="#"> Update <i class="fa fa-angle-double-right"></i></button>
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
                endDate: '{{ now()->addYears(5)->format('Y-m-d') }}',
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