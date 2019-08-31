@extends('layouts.default')

@section('title')
    Buat Kegiatan Bidang MGA
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
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
                            <span>Edit Kegiatan </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Edit kegiatan bidang MGA
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
                        Form Edit Kegiatan
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('chambers.administratif.mga.kegiatan.update', $kegiatan) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="tab-content">
                                <div class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-4 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk merubah data kegiatan.
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
                                                    <label>Jenis Kegiatan</label>
                                                    <select name="jenis_kegiatan" class="form-control">
                                                        @foreach ($jenisKegiatan as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == $kegiatan->jenis_kegiatan_id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Tahun Kegiatan</label>
                                                    <input name="year" id="date" class="form-control" value="{{ $kegiatan->tahun }}" type="text">
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Target Jumlah Kegiatan</label>
                                                    <input type="number" class="form-control" name="target_jumlah" value="{{ $kegiatan->target_jumlah }}" required min="0" max="100">
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label>Target Anggaran</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon" style="min-width: 75px;">Rp.</span>
                                                        <input type="number" value="{{ $kegiatan->target_anggaran }}" class="form-control" name="target_anggaran" value="0" required min="0">
                                                    </div>
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
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/moment/locale/id.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/bootstrap-select.min.js') }}"></script>
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
                today: 'Tahun ini',
                clear: 'Bersihkan',
                format: 'yyyy',
                titleFormat: 'yyyy',
                weekStart: 1
            };

            $('#date').datepicker({
                startDate: '2015',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: true,
                minViewMode: 2,
                readOnly: true,
                format: 'yyyy',
            });
        });
    </script>
@endsection