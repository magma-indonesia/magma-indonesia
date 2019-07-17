@extends('layouts.default')

@section('title')
    WOVOdat | Seismic Network
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Seismic Network Information
                </h2>
                <p>
                    Giving all the registered Seismic Network from
                    <strong>WOVOdat database</strong>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="text-center">
                        {{ $volcanoes->links() }}
                    </div>
                    
                    @foreach ($volcanoes as $volcano)
                    <div class="panel-heading">
                        {{ $volcano->vd_name }}
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>Station Name</th>
                                        <th>Station Code</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Elevation</th>
                                        <th>Type</th>
                                        <th>Gain</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volcano->seismic_network->stations as $station)
                                        <tr>
                                            <td>{{ $station->ss_name }}</td>
                                            <td>{{ $station->ss_code }}</td>
                                            <td>{{ $station->ss_lat ?: '-' }}</td>
                                            <td>{{ $station->ss_lon ?: '-'}}</td>
                                            <td>{{ $station->ss_elev ?: '-'}}</td>
                                            <td>{{ $station->ss_instr_type ?: '-' }}</td>
                                            <td>{{ $station->ss_sgain ?: '-'}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center">
                        {{ $volcanoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection