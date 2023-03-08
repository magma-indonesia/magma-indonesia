@extends('layouts.default')

@section('title')
Perubahan Tingkat Aktivitas
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap.min.css" />
<link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
@endsection

@section('content-header')
<div class="normalheader content-boxed">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-ribbon fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Perubahan Tingkat Aktivitas</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li class="active"><a href="{{ route('chambers.v1.gunungapi.perubahan-tingkat-aktivitas') }}">Perubahan Tingkat Aktivitas</a></li>
                </ol>
            </div>

            @if (session('message'))
            <div class="alert alert-success">
                <i class="fa fa-check"></i> {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content no-top-padding content-boxed">
    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Pilih Tahun</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>Tahun {{ $selected_year }}</h1>
                        <p>
                            Menu untuk melihat rekapitulasi jumlah laporan yang dibuat oleh pengamat gunung api. Pilih tahun laporan yang ingin dilihat.
                        </p>
                        @foreach ($years as $year)
                        <a href="{{ route('chambers.v1.gunungapi.perubahan-tingkat-aktivitas', $year->format('Y')) }}" class="btn btn-outline btn-danger m-t-xs {{ $selected_year == $year->format('Y') ? 'active disabled' : ''}}">{{ $year->format('Y') }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Perubahan Tingkat Aktivitas tahun {{ $selected_year }}
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <h3 class="text-center">Tahun {{ $selected_year }}</h3>
                        <table id="table-rekap" class="table table- condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Gunung Api</th>
                                    <th>Tingkat Aktivitas Lama</th>
                                    <th>Tingkat Aktivitas Baru</th>
                                    <th>Naik/Turun</th>
                                    <th>Tanggal Berubah</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($vars as $var)
                            <tr>
                                <td><a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $var->no]) }}" class="card-link" target="_blank">{{ $var->ga_nama_gapi }}</a></td>
                                <td>{{ $var->pre_status  }}</td>
                                <td>{{ $var->cu_status }}</td>
                                <td>{{ $var->activity_change === 'increase' ? 'Naik' : 'Turun' }}</td>
                                <td>{{ $var->var_data_date }}</td>
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
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- DataTables buttons scripts -->
<script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap.min.js"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/moment/locale/id.js') }}"></script>
<script src="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('add-script')
<script>

$(document).ready(function () {
    // Initialize table
    $('#table-rekap').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center 'B><'col-sm-4'f>>tp",
        "lengthMenu": [[250, 100, 150, -1], [250, 100, 150, "All"]],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});

</script>
@endsection