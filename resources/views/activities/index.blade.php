@extends('layouts.default')

@section('title')
    Aktivitas Gunung Api
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content-body')   
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Laporan Gunung Api
                </h2>
                <p>
                        Mmeberikan informasi Laporan Gunung Api yang telah masuk dan 
                        <strong>Daftar Laporan Harian</strong> terkini 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="hpanel">
                    <div class="panel-heading">
                        Recently active projects
                    </div>
                    <div class="panel-body list">
                        <div class="table-responsive project-list">
                            {{ $vars->links() }}
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Laporan</th>
                                        <th>Jenis Laporan</th>
                                        <th>Tanggal</th>
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
                                                <i class="fa fa-clock-o"></i> Tanggal : {{ $var->var_data_date->formatLocalized('%d-%B-%Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="pie">{{ $var->var_perwkt.', '.$var->periode }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $var->var_data_date->diffForHumans() }}</strong>
                                        </td>
                                        <td>{{ $var->user->name }}</td>
                                        <td>
                                            <a href="">
                                                <a href="#" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
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
                                    <tr>
                                        <td>{{ $gadd->name }}</td>
                                        <td>
                                            <span class="pie">{{ $gadd->latestVar->var_data_date->formatLocalized('%A, %d %B %Y').', '.$gadd->latestVar->periode }}</span>
                                        </td>  
                                        <td>
                                            <a href="">
                                                <a href="#" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
                                            </a>
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
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });

        });

    </script>
@endsection