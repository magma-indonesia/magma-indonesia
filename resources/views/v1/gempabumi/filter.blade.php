@extends('layouts.default')

@section('title')
    v1 - Filter Gempa Bumi
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
@endrole
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
<div class="content content-boxed normalheader">
    <div class="hpanel">
        <div class="panel-body">   
            <h2 class="font-light m-b-xs">
                Filter Kejadian Gempa Bumi
            </h2>
            <small class="font-light"> Form pencarian kejadian dan tanggapan kejadian gempa bumi</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">
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

                @if(Session::has('flash_filter'))
                <div class="alert alert-danger">
                    <i class="fa fa-bolt"></i> {!! session('flash_filter') !!}
                </div>
                @endif

                <div class="panel-body">
                    <form action="{{ route('chambers.v1.gempabumi.filter') }}" method="POST">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan paramater pencarian</strong>, gunakan form menu ini untuk mencari laporan/tanggapan.
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
                                                <label>Range Tanggal</label>
                                                <div class="input-group input-daterange">
                                                    <input id="start" type="text" class="form-control" value="{{ $request->start ?: now()->subDays(14)->format('Y-m-d') }}" name="start">
                                                    <div class="input-group-addon"> - </div>
                                                    <input id="end" type="text" class="form-control" value="{{ $request->end ?: now()->format('Y-m-d') }}" name="end">
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Magnitudo Minimal</label>
                                                <input name="magnitudo" class="form-control" value="{{ $request->magnitudo ?: 0 }}" min="0" max="12" placeholder="Masukkan nilai magnitudo minimal" type="number">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Nama Pembuat Laporan</label>
                                                <select name="nip" class="form-control">
                                                    <option value="all">- Pilih Semua-</option>
                                                    @foreach ($roqs as $roq)
                                                    <option value="{{ $roq->roq_nip_pelapor }}" {{ $request->nip == $roq->roq_nip_pelapor ? 'selected' : '' }}>{{ $roq->user->vg_nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Tampilkan kejadian yang hanya ada tanggapannya?</label>
                                                <div>
                                                    <label class="checkbox-inline"> 
                                                    <input name="tanggapan" class="i-checks" type="radio" value="1" id="tanggapan"> Ya </label> 
                                                    <label class="checkbox-inline">
                                                    <input name="tanggapan" class="i-checks" type="radio" value="0" id="tanggapan" checked> Tidak </label> 
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <hr>
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" name="form" value="filter" type="submit">Filter</button>
                                                    <button class="btn btn-outline btn-primary" name="form" value="download" type="submit">Download</button>
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

    @if ($filtereds->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Hasil Pencarian
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-result" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu (WIB)</th>
                                    <th>Area</th>
                                    <th>Magnitude (SR)</th>
                                    <th>Kedalaman (Km)</th>
                                    <th>Tanggapan</th>
                                    <th style="min-width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filtereds as $key => $filtered)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $filtered->datetime_wib }}</td>
                                    <td>{{ $filtered->area }}</td>
                                    <td>{{ $filtered->magnitude }}</td>
                                    <td>{{ $filtered->depth }}</td>
                                    <td>{{ $filtered->roq_tanggapan == 'YA' ? 'Ada' : 'Belum Ada'}}</td>
                                    <td>
                                        @if($filtered->roq_tanggapan == 'YA')
                                        <a href="{{ route('chambers.v1.gempabumi.show',['id'=> $filtered->no]) }}" target="_blank" class="btn btn-sm btn-info btn-outline" style="margin-right: 3px;">View</a>
                                        @endif
                                        <a href="{{ route('chambers.v1.gempabumi.edit',['id'=> $filtered->no]) }}" target="_blank" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
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
    @endif
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($filtereds), {collapsed: true});
        @endrole

        @if ($filtereds->isNotEmpty())
        $('#table-result').dataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            buttons: [
                { extend: 'copy', className: 'btn-sm'},
                { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, ]} },
                { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, ]} },
                { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, ]} }
            ]
        });
        @endif

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

        $('.input-daterange').datepicker({
            startDate: '2015-05-01',
            endDate: '{{ now()->format('Y-m-d') }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false
        });
    });
</script>
@endsection