@extends('layouts.default')

@section('title')
    Absensi - Cari Absensi
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('chambers.index') }}">Chambers</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.users.index') }}">Users</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.absensi.index') }}">Absensi</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.absensi.search') }}">Search</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Cari Absensi Pegawai PVMBG
                </h2>
                <small>Cari Data absensi Pegawai PVMBG, Badan Geologi, ESDM</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        @if ($errors->any())
        <div class="row">
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
            <div class="col-md-12 col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Filter Absensi
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="GET" action="{{ route('chambers.absensi.search') }}">
                            <div class="row">
                                <div class="form-group col-xs-12 col-lg-4">
                                    <label class="control-label">Nama Pegawai</label>
                                    <select id="nip" class="form-control m-b" name="nip">
                                        @foreach($users as $user)
                                        <option value="{{ $user->nip }}" {{ old('nip') == $user->nip || auth()->user()->nip == $user->nip ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-xs-12 col-lg-4">
                                    <label class="control-label">Range Tanggal</label>
                                    <div class="form-group">
                                        <div class="input-group input-daterange">
                                            <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? '2018-11-01' : old('start')}}" name="start">
                                            <div class="input-group-addon"> - </div>
                                            <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-lg-4">
                                    <label class="control-label m-b-md"></label>
                                    <button class="btn btn-magma btn-block" type="submit">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if(count($absensis))
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        {{ $data->name }}
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-absensi" class="table  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nip</th>
                                        <th>Nama</th>
                                        <th>Checkin</th>
                                        <th>Checkout</th>
                                        <th>Durasi Kerja</th>
                                        <th>Radius Check In</th>
                                        <th>Radius Check Out</th>
                                        <th>Kehadiran</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensis as $key => $absensi)
                                    @if(!empty($absensi))
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $absensi[0]->nip_id }}</td>
                                        <td>{{ $absensi[0]->user->name }}</td>
                                        <td>{{ optional($absensi[0]->checkin)->formatLocalized('%H:%M:%S') ?? 'Manual'}}</td>
                                        <td>{{ optional($absensi[0]->checkout)->formatLocalized('%H:%M:%S') ?? '-'}}</td>
                                        <td>{{ optional($absensi[0]->checkout)->diffInHours($absensi[0]->checkin) ?? '-'}} </td>
                                        <td>{{ $absensi[0]->checkin_distance }} m</td>
                                        <td>{{ $absensi[0]->distance }} m</td>
                                        <td>{!! $absensi[0]->keterangan_label !!}</td>
                                        <td>{{ empty($absensi[0]->keterangan_tambahan) ? '-' : str_replace_last(', ',' dan ', implode(', ',$absensi[0]->keterangan_tambahan)) }}</td>
                                        <td><a href="{{ route('chambers.absensi.show',['nip' =>$absensi[0]->nip_id]) }}" class="m-t-xs m-b-xs btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $data->nip }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><span class="label label-danger">Alpha</span></td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    @endif
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
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
<script>
    $(document).ready(function () {

        $('#nip').on('change', function(e){
            var $nip = $('#nip').val();
            console.log($nip);
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

        jQuery.validator.addMethod('isValid', function (value, element) {
            var startDate = $('#start').val();
            var endDate = $('#end').val();
            return Date.parse(startDate) <= Date.parse(endDate);
        }, '* Tanggal akhir harus setelah tanggal awal');

        $('.input-daterange').datepicker({
            startDate: '2018-10-01',
            endDate: '{{ now()->format('Y-m-d') }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false
        });

        $('.input-daterange input').each(function() {
            $(this).datepicker().on('changeDate', function(e){
                var startDate = $('#start').val(),
                    endDate = $('#end').val(),
                    isValid = Date.parse(startDate) <= Date.parse(endDate);
                console.log(isValid);
            });
        });

        $("#form").validate({
            rules: {
                nip: {
                    required: true
                },
                start: {
                    required: true,
                    date: true
                },
                end: {
                    required: true,
                    date: true,
                    isValid: true
                }
            },
            messages: {
                gunungapi: {
                    required: 'Harap pilih nama pegawai'
                },
                start: {
                    required: 'Harap masukkan tanggal',
                    date: 'Format tanggal salah (yyyy-mm-dd)',
                    isValid: 'Tanggal akhir harus setelah tanggal awal'
                },
                end: {
                    required: 'Harap masukkan tanggal',
                    date: 'Format tanggal salah (yyyy-mm-dd)',
                    isValid: 'Tanggal akhir harus setelah tanggal awal'
                }
            },
            errorPlacement: function(error, element) {
                var endDate = $('#end').closest('.form-group');
                error.appendTo(endDate);
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // Initialize table
        $('#table-absensi').dataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [[40, 50, 100, -1], [40, 50, 100, "All"]],
            buttons: [
                { extend: 'copy', className: 'btn-sm'},
                { extend: 'csv', title: 'Daftar Absensi {{ optional($data)->name }}', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6,7, 8 ]} },
                { extend: 'pdf', title: 'Daftar Absensi {{ optional($data)->name }}', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6,7, 8 ]}, orientation: 'landscape', },
                { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6,7, 8 ]} }
            ]

        });

    });
</script>
@endsection
