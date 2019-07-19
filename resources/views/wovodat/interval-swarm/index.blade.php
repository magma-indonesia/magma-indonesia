@extends('layouts.default') 

@section('title') 
    WOVOdat | Interval Swarm
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
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
                        <span>WOVOdat</span>
                    </li>
                    <li class="active">
                        <span>Interval Swarm </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Giving information about Interval Swarm from specific seismic station
            </h2>
            <small>This table contains data about earthquakes that occur in specified time intervals, e.g., as seismic swarms.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            @role('Super Admin')
            <div class="col-lg-12">
            @component('components.json-var')
                @slot('title')
                    For Developer
                @endslot
            @endcomponent
            </div>
            @endrole

            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        View Data
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.wovodat.interval-swarm.store') }}">
                            @csrf
                            <div class="tab-content">
                                <div id="step1" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-4 text-center">
                                            <i class="pe-7s-note fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk melihat data swarm.
                                            </p>
                                        </div>

                                        <div class="col-lg-8">
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

                                            <div class="row p-md">
                                                <div class="form-group">
                                                    <label class="control-label">Seismic Station</label>
                                                    <select id="volcano" class="form-control m-b" name="station">
                                                        @foreach ($volcanoes as $volcano)
                                                            @foreach ($volcano->stations as $station)
                                                            <option value="{{ $station->ss_id }}" {{ old('station') == $station->ss_id ? 'selected' : '' }}>{{ $volcano->vd_name.' - '.$station->ss_name.' ('.$station->ss_code.')' }}</option> 
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Range Tanggal</label>
                                                    <div class="input-group input-daterange">
                                                        <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(30)->format('Y-m-d') : old('start')}}" name="start">
                                                        <div class="input-group-addon"> - </div>
                                                        <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                                    </div>
                                                </div>

                                                <hr>
                                                <button class="btn btn-magma" type="submit">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Swarm Data Count
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-swarm" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano Name</th>
                                        <th>Seismic Network</th>
                                        <th>Seismic Station (Code)</th>
                                        <th>Swarm Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 1;
                                    @endphp
                                    @foreach ($volcanoes as $key => $volcano)
                                        @foreach ($volcano->stations as $key1 => $station)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $volcano->vd_name }}</td>
                                            <td>{{ $volcano->seismic_network->sn_name }}</td>
                                            <td>{{ $station->ss_name.' ('.$station->ss_code.')' }}</td>
                                            <td>{{ $station->swarm_count }}</td>
                                        </tr>
                                        @php
                                            $index = $index+1;
                                        @endphp
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($volcanoes), {collapsed: true});
        @endrole

        $('#table-swarm').dataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [[15, 30, -1], [30, 60, 100, "All"]],
            buttons: [
                { extend: 'copy', className: 'btn-sm'},
                { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4 ]} },
                { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4 ]} },
                { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4 ]} }
            ]
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

        $('.input-daterange').datepicker({
            startDate: '1989-01-11',
            endDate: '{{ now()->format('Y-m-d') }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false
        });
    });
</script>
@endsection