@extends('layouts.default')

@section('title')
    Absensi Pegawai 
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
                        <li class="active">
                            <a href="{{ route('chambers.absensi.index') }}">Absensi</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Absensi Pegawai PVMBG
                </h2>
                <small>Data absensi Pegawai PVMBG, Badan Geologi, ESDM</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar Absensi Hari {{ Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, Tanggal %d %B %Y') }}
                    </div>
                    <div class="panel-body m-b">
                        <form role="form" id="form" method="GET" action="{{ route('chambers.absensi.index') }}">
                            <div class="row">
                                <div class="col-xs-12 col-lg-6">
                                    <input id="date" type="text" class="form-control" value="{{ empty(old('date')) ? now()->format('Y-m-d') : old('date')}}" name="date">
                                </div>
                                <div class="col-xs-12 col-lg-6">
                                    <button class="btn btn-magma btn-block" type="submit">Ganti Tanggal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Lihat Absensi per Pegawai
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <select id="nip" class="form-control" name="nip">
                                    @foreach($users as $user)
                                    <option value="{{ $user->nip }}" {{ auth()->user()->nip == $user->nip ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <a id="showAbsensi" href="{{ route('chambers.absensi.show',['nip' => auth()->user()->nip]) }}" target="_blank" type="button" class="btn btn-magma btn-block" type="submit">Lihat Absensi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-absensi" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nip</th>
                                        <th>Nama</th>
                                        <th>Pos PGA</th>
                                        <th>Tanggal</th>
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
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $absensi->nip }}</td>
                                        <td>{{ $absensi->name }}</td>
                                        <td>{{ 
                                            strlen($absensi->latest_absensi->kantor->code) < 4 ?
                                                $absensi->latest_absensi->kantor->nama :
                                                explode(' ',$absensi->latest_absensi->kantor->nama, 5)[4]
                                            }}
                                        </td>
                                        <td>{{ $absensi->latest_absensi->checkin->format('Y-m-d') }}</td>
                                        <td>{{ optional($absensi->latest_absensi->checkin)->formatLocalized('%H:%M:%S') ?? 'Manual'}}</td>
                                        <td>{{ optional($absensi->latest_absensi->checkout)->formatLocalized('%H:%M:%S') ?? '-'}}</td>
                                        <td>{{ optional($absensi->latest_absensi->checkout)->diffInHours($absensi->latest_absensi->checkin) ?? '-'}} </td>
                                        <td>{{ $absensi->latest_absensi->checkin_distance }} m</td>
                                        <td>{{ $absensi->latest_absensi->distance }} m</td>
                                        <td>{!! $absensi->latest_absensi->keterangan_label !!}</td>
                                        <td>{{ str_replace_last(', ',' dan ', implode(', ',$absensi->latest_absensi->keterangan_tambahan)) }}</td>
                                        <td><a href="{{ route('chambers.absensi.show',['nip' =>$absensi->nip]) }}" class="m-t-xs m-b-xs btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a></td>
                                    </tr>
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
@endsection

@section('add-script')
<script>
    $(document).ready(function() {
        // Initialize table

        $('#nip').on('change', function() {
            var $nip = $(this).val(),
                $show = $('#showAbsensi');
            $show.attr('href','{{ route("chambers.absensi.show",["nip" => "/"]) }}/'+$nip);
        });

        $('#table-absensi').dataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
            buttons: [
                { extend: 'copy', className: 'btn-sm'},
                { extend: 'csv', title: 'Daftar Absensi {{ now()->format('Y-m-d')}}', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]} },
                { extend: 'pdf', title: 'Daftar Absensi {{ now()->format('Y-m-d')}}', className: 'btn-sm', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]}, orientation: 'landscape', },
                { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]}, orientation: 'landscape', }
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

        $('#date').datepicker({
            startDate: '2018-11-01',
            endDate: '{{ now()->format('Y-m-d') }}',
            language: 'id',
            todayHighlight: true,
            todayBtn: 'linked',
            enableOnReadonly: false,
            maxViewMode: 2
        });
    });
</script>
@endsection