@extends('layouts.default')

@section('title')
    Gunung Api | Laporan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content-body')   
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Laporan Gunung Api
                </h2>
                <p>
                    Memberikan informasi Laporan Gunung Api yang telah masuk dan 
                    <strong>Daftar Laporan Harian</strong> terkini 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Laporan Harian per Gunungapi
                    </div>
                    <div class="panel-body list">
                        <div class="table-responsive project-list">
                            <table class="table table-striped table-daily">
                                <thead>
                                    <tr>
                                        <th>Gunungapi</th>
                                        <th>Laporan Terakhir</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gadds as $gadd)
                                    <tr title="{{ $gadd->latestVar->status_deskripsi }}">
                                        <td class="{{ $gadd->latestVar->status }}">{{ $gadd->name }}</td>
                                        <td>
                                            <span class="pie">{{ $gadd->latestVar->var_data_date->formatLocalized('%A, %d %B %Y').', '.$gadd->latestVar->periode }}</span>
                                        </td>  
                                        <td>
                                            <a href="{{ route('chambers.laporan.show',$gadd->latestVar->noticenumber ) }}" target="_blank" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
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
        <div class="row">
            <div class="col-md-12">
                <div id="all-vars" class="hpanel">
                    <div class="panel-heading">
                        Semua Laporan Gunung Api
                    </div>
                    <div class="panel-body list">
                        <div class="text-center">
                        {{ $vars->links() }}
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
                                        <td class="{{ $var->status }}">Laporan Gunungapi {{ $var->gunungapi->name }}
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Tanggal : {{ $var->var_data_date->formatLocalized('%d %B %Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="pie">{{ $var->var_perwkt.' Jam, '.$var->periode }}</span>
                                        </td>
                                        {{--  <td>
                                            <strong>{{ $var->var_data_date->diffForHumans() }}</strong>
                                        </td>  --}}
                                        <td>{{ $var->user->name }}</td>
                                        <td>
                                            <a href="">
                                                <a href="{{ route('chambers.laporan.show',$var->noticenumber ) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                        {{ $vars->links() }}
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
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            // Initialize table
            $('.table-daily').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-2 text-center'B><'col-sm-6'f>>tp",
                "lengthMenu": [[8, 24, 50, -1], [8, 24, 50, "All"]]
            });   

        });

    </script>
@endsection