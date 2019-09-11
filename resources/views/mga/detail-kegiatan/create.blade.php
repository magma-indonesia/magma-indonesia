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
                    {{ $kegiatan->tahun }} - {{ $kegiatan->jenis_kegiatan->nama}}
                </h2>
                <small>Meliputi seluruh kegiatan yang sedang, pernah, atau akan dilakukan (perencanaan) </small>
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
                    Form Buat Detail Kegiatan
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.administratif.mga.detail-kegiatan.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk menambahkan data detail kegiatan baru.
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

                                            <div class="form-group col-sm-12">
                                                <label>Nama Kegiatan</label>
                                                <select name="kegiatan_id" class="form-control">
                                                    <option value="{{ $kegiatan->id }}">{{ '('.$kegiatan->jenis_kegiatan->bidang->code.' '.$kegiatan->tahun.') ' }} - {{ $kegiatan->jenis_kegiatan->nama }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Nama Gunung Api</label>
                                                <select name="code" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">- Tidak Berlokasi di Gunung Api -</option>
                                                    @foreach ($gadds as $gadd)
                                                    <option value="{{ $gadd->code }}">{{ $gadd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Lokasi (ospional)</label>
                                                <input name="lokasi_lainnya" class="form-control" placeholder="Lokasi detail" type="text">
                                                <small class="help-block m-b text-danger">Isi data ini, jika lokasinya bukan di daerah gunung api</small>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Waktu Kegiatan</label>
                                                <div class="input-group input-daterange">
                                                    <input id="start" type="text" class="form-control" value="{{ now()->subDays(12)->format('Y-m-d') }}" name="start">
                                                    <div class="input-group-addon"> - </div>
                                                    <input id="end" type="text" class="form-control" value="{{ now()->addDays(30)->format('Y-m-d') }}" name="end">
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Ketua Tim</label>
                                                <select name="ketua_tim" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($users as $user)
                                                    <option value="{{ $user->nip }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Upah Tenaga Lokal</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 75px;">Rp.</span>
                                                    <input type="number" value="{{ old('upah') ?: 0 }}" class="form-control" name="upah" required min="0">
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Biaya Bahan-bahan</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 75px;">Rp.</span>
                                                    <input type="number" value="{{ old('bahan') ?: 0 }}" class="form-control" name="bahan" required min="0">
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Sewa Kendaraan</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 75px;">Rp.</span>
                                                    <input type="number" value="{{ old('transportasi') ?: 0 }}" class="form-control" name="transportasi" required min="0">
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Biaya Bahan Lain-lain</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="min-width: 75px;">Rp.</span>
                                                    <input type="number" value="{{ old('biaya_lainnya') ?: 0 }}" class="form-control" name="biaya_lainnya" required min="0">
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <label>Upload Proposal (doc, docx)</label>
                                                <div class="form-group">
                                                    <label class="w-xs btn btn-outline btn-default btn-file">
                                                        <i class="fa fa-upload"></i>
                                                        <span class="label-proposal">Browse </span> 
                                                        <input class="file" type="file" name="proposal" style="display: none;">
                                                    </label>
                                                    <button type="button" class="w-xs btn btn-danger clear-proposal"><i class="fa fa-trash"></i> Clear</button>
                                                </div>
                                                <small class="help-block m-b text-danger">Opsional</small>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <label>Upload Laporan Kegiatan (doc, docx)</label>
                                                <div class="form-group">
                                                    <label class="w-xs btn btn-outline btn-default btn-file">
                                                        <i class="fa fa-upload"></i>
                                                        <span class="label-kegiatan">Browse </span> 
                                                        <input class="file-kegiatan" type="file" name="laporan_kegiatan" style="display: none;">
                                                    </label>
                                                    <button type="button" class="w-xs btn btn-danger clear-kegiatan"><i class="fa fa-trash"></i> Clear</button>
                                                </div>
                                                <small class="help-block m-b text-danger">Opsional</small>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="hr-line-dashed"></div>
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
            startDate: '2015-05-01',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false,
            format: 'yyyy-mm-dd',
        });

        $('input.file').on('change', function(e) {
            var input = $(this),
                label = input.val()
                            .replace(/\\/g, '/')
                            .replace(/.*\//, '');

            $('.label-proposal').html(label);
        });

        $('input.file-kegiatan').on('change', function(e) {
            var input = $(this),
                label = input.val()
                            .replace(/\\/g, '/')
                            .replace(/.*\//, '');

            $('.label-kegiatan').html(label);
        });

        $('.clear-proposal').on('click', function(e) {
            $('.file').val(null);
            $('.label-proposal').html('Browse');
        });

        $('.clear-kegiatan').on('click', function(e) {
            $('.file-kegiatan').val(null);
            $('.label-kegiatan').html('Browse');
        });
    });
</script>
@endsection