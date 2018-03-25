@extends('layouts.default')

@section('title')
    Gunung Api - Cari Laporan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('nav-search-laporanga')
    <li class="{{ active('laporan.gunungapi.search.*') }}">
        <a href="{{ route('laporan.gunungapi.search') }}">Cari Laporan</a>
    </li>
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chamber') }}">Chamber</a></li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Pencarian </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Hasil Pencarian
                </h2>
                <small>Memberikan hasil pencarian data laporan gunung api sesuai dengan parameter yang kita berikan</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-12">
                <div id="all-vars" class="hpanel">
                    @if(!$vars->count())
                    <div class="alert alert-danger">
                        <i class="fa fa-bolt"></i> Kriteria pencarian tidak ditemukan
                    </div>
                    @else
                    <div class="panel-heading">
                        Semua Laporan Gunung Api
                    </div>
                    <div class="panel-body list">
                        <div class="text-center">
                        {{ $vars->appends(Request::except('page'))->links() }}
                        </div>
                        <div class="table-responsive project-list">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Laporan</th>
                                        <th>Jenis Laporan</th>
                                        {{--  <th>Tanggal</th>  --}}
                                        <th>Pembuat Laporan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vars as $var)
                                    <tr>
                                        <td>Laporan Gunungapi {{ $var->gunungapi->name }}
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Tanggal : {{ $var->var_data_date->formatLocalized('%d %B %Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="pie">{{ $var->var_perwkt.', '.$var->periode }}</span>
                                        </td>
                                        {{--  <td>
                                            <strong>{{ $var->var_data_date->diffForHumans() }}</strong>
                                        </td>  --}}
                                        <td>{{ $var->user->name }}</td>
                                        <td>
                                            <a href="">
                                                <a href="{{ route('laporanga.show',$var->noticenumber ) }}" target="_blank" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                        {{ $vars->appends(Request::except('page'))->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables -->
    <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {
            
            // Initialize table
            $('.table-daily').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-2 text-center'B><'col-sm-6'f>>tp",
                "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]]
            });

        
        });

    </script>
@endsection