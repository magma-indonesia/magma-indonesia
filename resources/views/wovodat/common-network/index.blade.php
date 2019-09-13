@extends('layouts.default')

@section('title')
    WOVOdat | Common Network
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md m-b-md">
                <h2>
                    Common Network Information
                </h2>
                <p>
                    This menu contains information about the network of stations that collect data at a particular site, in general at one volcano from
                    <strong>WOVOdat database</strong>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Deformation Stations</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-info">{{ $volcanoes->sum('deformation_stations_count') }}</h1>
                            <small>
                                Number of registered deformation stations.
                            </small>
                            <h4><a href="{{ route('chambers.wovodat.common-network.deformation-station.tilt.index') }}" class="btn btn-outline btn-danger" target="_blank">Tilt Data</a></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Fields Stations</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-info">{{ $volcanoes->sum('fields_stations_count') }}</h1>
                            <small>
                                Number of registered fields stations.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Gas Stations</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-info">{{ $volcanoes->sum('gas_stations_count') }}</h1>
                            <small>
                                Number of registered gas stations.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Hydrologic Stations</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-info">{{ $volcanoes->sum('hydrologic_stations_count') }}</h1>
                            <small>
                                Number of registered hydrologic stations.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Meteo Stations</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-info">{{ $volcanoes->sum('meteo_stations_count') }}</h1>
                            <small>
                                Number of registered meteo stations.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Thermal Stations</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-info">{{ $volcanoes->sum('thermal_stations_count') }}</h1>
                            <small>
                                Number of registered thermal stations.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                        Common Network List
                    </div>
                    <div class="panel-body">
                        @foreach ($volcanoes as $volcano)
                        <div class="hpanel collapsed">
                            <div class="panel-heading hbuilt">
                                <div class="panel-tools">
                                    <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                                </div>
                                {{ $volcano->vd_name }}
                            </div>

                            @foreach ($volcano->common_network as $common)
                            
                            @if (count($common->deformation_stations))
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Common Network Type</th>
                                                <th>Common Network Name</th>
                                                <th>Station Name</th>
                                                <th>Station Code</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($common->deformation_stations as $deformation)
                                            <tr>
                                                <td>{{ $common->cn_type }}</td>
                                                <td>{{ $common->cn_name }}</td>
                                                <td>{{ $deformation->ds_name }}</td>
                                                <td>{{ $deformation->ds_code }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            @if (count($common->fields_stations))
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Common Network Type</th>
                                                <th>Common Network Name</th>
                                                <th>Station Name</th>
                                                <th>Station Code</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($common->fields_stations as $fs)
                                            <tr>
                                                <td>{{ $common->cn_type }}</td>
                                                <td>{{ $common->cn_name }}</td>
                                                <td>{{ $fs->fs_name }}</td>
                                                <td>{{ $fs->fs_code }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            @if (count($common->gas_stations))
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Common Network Type</th>
                                                <th>Common Network Name</th>
                                                <th>Station Name</th>
                                                <th>Station Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($common->gas_stations as $gs)
                                            <tr>
                                                <td>{{ $common->cn_type }}</td>
                                                <td>{{ $common->cn_name }}</td>
                                                <td>{{ $gs->gs_name }}</td>
                                                <td>{{ $gs->gs_code }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            @if (count($common->hydrologic_stations))
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Common Network Type</th>
                                                <th>Common Network Name</th>
                                                <th>Station Name</th>
                                                <th>Station Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($common->hydrologic_stations as $hs)
                                            <tr>
                                                <td>{{ $common->cn_type }}</td>
                                                <td>{{ $common->cn_name }}</td>
                                                <td>{{ $hs->hs_name }}</td>
                                                <td>{{ $hs->hs_code }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            @if (count($common->meteo_stations))
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Common Network Type</th>
                                                <th>Common Network Name</th>
                                                <th>Station Name</th>
                                                <th>Station Code</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($common->meteo_stations as $ms)
                                            <tr>
                                                <td>{{ $common->cn_type }}</td>
                                                <td>{{ $common->cn_name }}</td>
                                                <td>{{ $ms->ms_name }}</td>
                                                <td>{{ $ms->ms_code }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            @if (count($common->thermal_stations))
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Common Network Type</th>
                                                <th>Common Network Name</th>
                                                <th>Station Name</th>
                                                <th>Station Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($common->thermal_stations as $ts)
                                            <tr>
                                                <td>{{ $common->cn_type }}</td>
                                                <td>{{ $common->cn_name }}</td>
                                                <td>{{ $ts->ts_name }}</td>
                                                <td>{{ $ts->ts_code }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection