@extends('layouts.default')

@section('title')
    Kebencanaan Geologi
@endsection

@section('add-vendor-css')
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('content-header')
<div class="content animate-panel content-boxed normalheader">
    <div class="hpanel">
        <div class="panel-body">   
            <h2 class="font-light m-b-xs">
                Daftar Kebencanaan Geologi 
            </h2>
            <small class="font-light"> Digunakan dalam laporan Kebencanan Geologi Harian</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed animate-panel">

    @if ($pendahuluans->count() == 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">   
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Data Pendahuluan Bencana Geologi belum ada. <a href="{{ route('chambers.bencana-geologi-pendahuluan.create') }}"><b>Mau buat baru?</b></a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($resumes->isEmpty() AND $pendahuluans->count() >0)
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">   
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Data Bencana Geologi harian belum ada. <a href="{{ route('chambers.resume-harian.create') }}"><b>Generate Laporan?</b></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Pendahuluan yang Digunakan dalam Laporan
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.bencana-geologi-pendahuluan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Buat Pendahuluan Baru</a>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.resume-harian.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Buat Resume Hari ini</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="15%">Gunung Api</th>
                                    <th>Laporan Pendahuluan</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendahuluans as $key => $pendahuluan)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $pendahuluan->gunungapi->name }}</td>
                                    <td>{{ $pendahuluan->pendahuluan }}</td>
                                    <td>
                                        <a href="{{ route('chambers.bencana-geologi-pendahuluan.edit', $pendahuluan) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Untuk menghapus data yang digunakan, silahkan kontak Admin</a>
                </div>

            </div>
        </div>
    </div>

    @elseif ($resumes->isNotEmpty())

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Resume Harian Gunung Api
                </div>

                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endforeach
                @endif

                @if(Session::has('flash_resume'))
                <div class="alert alert-success">
                    <i class="fa fa-bolt"></i> {!! session('flash_resume') !!}
                </div>
                @endif

                <div class="panel-body float-e-margins">
                    <form action="{{ route('chambers.resume-harian.create') }}" method="get">
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Buat Resume Laporan Harian</strong>, gunakan form ini untuk merangkum laporan gunung api.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

                                            @if ($errors->any())
                                            <div class="form-group col-sm-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12">
                                                <label>Tanggal Resume</label>
                                                <input name="date" id="date" class="form-control" type="text" value="{{ now()->format('Y-m-d') }}">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Generate Rangkuman</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>

                <div class="panel-body">
                    <div class="text-center">
                        {{ $resumes->links() }}
                    </div>
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="20%">Tanggal</th>
                                    <th>Resume</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resumes as $key => $resume)
                                <tr>
                                    <td>{{ $resumes->firstItem()+$key }}</td>
                                    <td>{{ $resume->tanggal->formatLocalized('%A, %d %B %Y') }}</td>
                                    <td>{{ $resume->truncated }}</td>
                                    <td>
                                        <a href="{{ route('chambers.resume-harian.show', $resume) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                        <a href="{{ route('chambers.resume-harian.create', ['date' => $resume->tanggal->format('Y-m-d')]) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Generate</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Untuk menghapus data yang digunakan, silahkan kontak Admin</a>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Aktifkan Gunung Api baru
                </div>

                @if ($bencanas->isEmpty())
                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Gunung Api untuk laporan belum dipilih. <b>Silahkan aktifkan salah satu gunung melalui form di bawah ini</b></a>
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.bencana-geologi.create') }}" method="get">
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Pilih Gunung Api</strong>, gunakan form menu ini untuk menambahkan gunung api dalam laporan.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

                                            @if ($errors->any())
                                            <div class="form-group col-sm-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12">
                                                <label>Nama Gunung Api</label>
                                                <select name="code" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($gadds as $gadd)
                                                    <option value="{{ $gadd->code }}">{{ $gadd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Simpan dan Pilih Pendahuluan >></button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>
                @else

                @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                </div>
                @endif

                <div class="panel-body">
                    <form action="{{ route('chambers.bencana-geologi.create') }}" method="get">
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Pilih Gunung Api</strong>, gunakan form menu ini untuk menambahkan gunung api dalam laporan.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

                                            @if ($errors->any())
                                            <div class="form-group col-sm-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12">
                                                <label>Nama Gunung Api</label>
                                                <select name="code" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($gadds as $gadd)
                                                    <option value="{{ $gadd->code }}">{{ $gadd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Simpan dan Pilih Pendahuluan >></button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>

                <div class="panel-heading m-t-md">
                    Daftar Gunung Api aktif yang digunakan dalam Laporan
                </div>

                <div class="panel-body">
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="15%">Gunung Api</th>
                                    <th>Urutan</th>
                                    <th>Laporan Pendahuluan</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bencanas as $key => $bencana)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $bencana->gunungapi->name }}</td>
                                    <td>{{ $bencana->urutan }}</td>
                                    <td>{{ $bencana->pendahuluan->pendahuluan }}</td>
                                    <td>{{ $bencana->active ? 'Aktif' : 'Tidak Aktif' }}</td>
                                    <td>
                                        <a href="{{ route('chambers.bencana-geologi.edit', $bencana) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Pendahuluan yang Digunakan dalam Laporan
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.bencana-geologi-pendahuluan.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Buat Pendahuluan Baru</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive m-t">
                        <table id="table-kesimpulan" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="15%">Gunung Api</th>
                                    <th>Laporan Pendahuluan</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendahuluans as $key => $pendahuluan)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $pendahuluan->gunungapi->name }}</td>
                                    <td>{{ $pendahuluan->pendahuluan }}</td>
                                    <td>
                                        <a href="{{ route('chambers.bencana-geologi-pendahuluan.edit', $pendahuluan) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="alert alert-danger">
                    <i class="fa fa-gears"></i> Untuk menghapus data yang digunakan, silahkan kontak Admin</a>
                </div>

            </div>
        </div>
    </div>

    @endif

    @if ($stats->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div clas="panel-heading">
                    Penggunaan Terakhir
                </div>
                <div class="panel-body">
                    <div class="table-responsive m-t">
                        <table id="table-visitor" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="20%">Nama</th>
                                    <th>Tanggal</th>
                                    <th>Hit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats as $key => $stat)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $stat->user->name }}</td>
                                    <td>{{ $stat->updated_at->formatLocalized('%A, %d %B %Y') }}</td>
                                    <td>{{ $stat->hit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('add-vendor-script')
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function() {
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
    });
});
</script>
@endsection