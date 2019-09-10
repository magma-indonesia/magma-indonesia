@extends('layouts.default')

@section('title')
    Buat Detail Kegiatan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('add-css')
<style>
    /* For Firefox */
    input[type='number'] {
        -moz-appearance:textfield;
    }

    /* Webkit browsers like Safari and Chrome */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>Administratif</span>
                        </li>
                        <li>
                            <span>MGA</span>
                        </li>
                        <li class="active">
                            <span>Buat Detail Kegiatan </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    {{ $detailKegiatan->kegiatan->tahun }} - {{ $detailKegiatan->kegiatan->jenis_kegiatan->nama}}
                </h2>
                <small>Periode {{ $detailKegiatan->start_date }} hingga {{ $detailKegiatan->end_date }}, Ketua Tim : {{ $detailKegiatan->ketua->name }}</small>
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
                    Form Tambah Anggota Tim
                </div>

                <div class="panel-body">

                    <form action="{{ route('chambers.administratif.mga.anggota-kegiatan.store',['step' => '3', 'id' => $detailKegiatan->id]) }}" method="post">
                        @csrf
                        <div class="text-center m-b-md" id="wizardControl">
                            <a class="btn btn-default hidden-xs m-b" href="#" disabled>Step 1 <span class="hidden-xs">- Pilih Anggota</span></a>
                            <a class="btn btn-primary m-b" href="#">Step 2 <span class="hidden-xs">- Periode</span></a>
                        </div>
                        <hr>

                        <div class="tab-content">
                            <div class="p-m active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan anggota tim pada kegiatan Tahun {{ $detailKegiatan->kegiatan->tahun }} - {{ $detailKegiatan->kegiatan->jenis_kegiatan->nama}}.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

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

                                            @if (!$detailKegiatan->anggota_tim->contains('nip_anggota',$detailKegiatan->ketua->nip))
                                            <div class="form-group col-sm-12" style="display:none;">
                                                <select id="anggota_tim" name="anggota_tim[{{$detailKegiatan->ketua->nip}}]" class="form-control selectpicker" data-live-search="true">
                                                    <option value="{{ $detailKegiatan->ketua->nip }}" selected>{{ $detailKegiatan->ketua->name }}</option>
                                                </select>
                                            </div>


                                            <div class="hpanel">
                                                <div class="panel-heading">
                                                    <h4>{{ $detailKegiatan->ketua->name }} - Ketua Tim</h4>
                                                </div>

                                                <div class="panel-body">
                                                    <div class="form-group col-sm-12">
                                                        <label>Periode</label>
                                                        <div class="input-group input-daterange">
                                                            <input id="start" type="text" class="form-control" value="{{ $detailKegiatan->start_date }}" name="start[{{ $detailKegiatan->ketua->nip }}]">
                                                            <div class="input-group-addon"> - </div>
                                                            <input id="end" type="text" class="form-control" value="{{ $detailKegiatan->end_date }}" name="end[{{ $detailKegiatan->ketua->nip }}]">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Uang Harian</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="uang_harian[{{ $detailKegiatan->ketua->nip }}]" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Penginapan 30%</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="penginapan_tigapuluh[{{ $detailKegiatan->ketua->nip }}]" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Penginapan 100%</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="penginapan_penuh[{{ $detailKegiatan->ketua->nip }}]">
                                                            <div class="input-group-addon"> Jumlah Hari </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="jumlah_hari[{{ $detailKegiatan->ketua->nip }}]">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Uang Transport</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="uang_transport[{{ $detailKegiatan->ketua->nip }}]" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @foreach ($users as $user)
                                            <div class="form-group col-sm-12" style="display:none;">
                                                <select id="anggota_tim" name="anggota_tim[{{$user->nip}}]" class="form-control selectpicker" data-live-search="true">
                                                    <option value="{{ $user->nip }}" selected>{{ $user->name }}</option>
                                                </select>
                                            </div>

                                            <div class="hpanel">
                                                <div class="panel-heading">
                                                    <h4>{{ $user->name }}</h4>
                                                </div>

                                                <div class="panel-body">
                                                    <div class="form-group col-sm-12">
                                                        <label>Periode</label>
                                                        <div class="input-group input-daterange">
                                                            <input id="start" type="text" class="form-control" value="{{ $detailKegiatan->start_date }}" name="start[{{ $user->nip }}]">
                                                            <div class="input-group-addon"> - </div>
                                                            <input id="end" type="text" class="form-control" value="{{ $detailKegiatan->end_date }}" name="end[{{ $user->nip }}]">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Uang Harian</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="uang_harian[{{ $user->nip }}]" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Penginapan 30%</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="penginapan_tigapuluh[{{ $user->nip }}]" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Penginapan 100%</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="penginapan_penuh[{{ $user->nip }}]">
                                                            <div class="input-group-addon"> Jumlah Hari </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="jumlah_hari[{{ $user->nip }}]">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label>Uang Transport</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"> Rp. </div>
                                                            <input type="numeric" class="form-control" value="0" min="0" name="uang_transport[{{ $user->nip }}]" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                            <div class="hr-line-dashed"></div>
                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Save</button>
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
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/bootstrap/bootstrap-select.min.js') }}"></script>
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
            titleFormat: 'MM yyyy',
            weekStart: 1
        };

        $('.input-daterange').datepicker({
            startDate: '{{ $detailKegiatan->start_date }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false,
            format: 'yyyy-mm-dd',
        });

    });
</script>
@endsection
