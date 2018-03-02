@extends('layouts.default')

@section('title')
    Aktivitas Gunung Api
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/select2-3.5.2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap/select2-bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" />
@endsection

@section('content-body')   
    <div class="content animate-panel">
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
                                    <tr>
                                        <td>{{ $gadd->name }}</td>
                                        <td>
                                            <span class="pie">{{ $gadd->latestVar->var_data_date->formatLocalized('%A, %d %B %Y').', '.$gadd->latestVar->periode }}</span>
                                        </td>  
                                        <td>
                                            <a href="{{ route('activities.show',$gadd->latestVar->noticenumber ) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
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
            <div class="col-md-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Filter Laporan
                    </div>
                    <div class="panel-body">
                        <div class="m-b-md">
                            Filter your project basend on diferent options below.
                        </div>
                        <div class="form-group">
                            <label class="control-label">Project date:</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" value="10/06/2016">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Project value:</label>
                            <div class="input-group">
                                <input id="demo1" type="text"  name="demo1" value="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Client:</label>
                            <div class="input-group">
                                <select class="form-control m-b" name="account">
                                    <option selected>Company and Brothers</option>
                                    <option>Morgan & Morgan Inc.</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Employees</label>
                            <div class="input-group">
                                <input id="demo2" type="text" name="demo2" value="2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status:</label>
                            <div class="input-group">
                                <div class="checkbox checkbox-primary">
                                    <input checked id="checkbox1" type="checkbox">
                                    <label for="checkbox1">
                                        Completed
                                    </label>
                                </div>
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox2" type="checkbox">
                                    <label for="checkbox2">
                                        Pending
                                    </label>
                                </div>
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox3" type="checkbox">
                                    <label for="checkbox3">
                                        Cancel
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tags:</label>
                            <div class="input-group">
                                <select class="select2" multiple="multiple" >
                                    <option selected value="Branding">Branding</option>
                                    <option selected value="Website">Website</option>
                                    <option selected value="Design">Design</option>
                                    <option selected value="Ilustration">Ilustration</option>
                                    <option selected value="New">New</option>
                                    <option value="Important">Important</option>
                                    <option value="External">External</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-success btn-block">Apply</button>

                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hpanel">
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
                                                <a href="{{ route('activities.show',$var->noticenumber ) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">View</a>
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
    <script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/sparkline/index.js') }}"></script>
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/select2-3.5.2/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            // Initialize table
            $('.table-daily').dataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-2 text-center'B><'col-sm-6'f>>tp",
                "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]]
            });

            $('.input-group.date').datepicker();

            $("#demo1").TouchSpin({
                min: 0,
                max: 100,
                step: 0.1,
                decimals: 2,
                boostat: 5,
                maxboostedstep: 10
            });
    
            $("#demo2").TouchSpin({
                verticalbuttons: true
            });
    
            $(".select2").select2();
    

        });

    </script>
@endsection