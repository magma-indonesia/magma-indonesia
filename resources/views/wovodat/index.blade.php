@extends('layouts.default') 

@section('title') 
    MAGMA | WOVOdat
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <img src="{{ asset('wovodat.png') }}" alt="WOVOdat Logo" style="height: 120px;">
                <h2>
                    Selamat datang di aplikasi <strong>WOVOdat</strong>
                </h2>
                <p class="p-md">
                    <strong class="text-danger">WOVOdat</strong> is a Database of Volcanic Unrest; instrumentally and visually recorded changes in seismicity, ground deformation, gas emission, and other parameters from their normal baselines. The database is created per the structure and format as described in the WOVOdat 1.0 report of Venezky and Newhall (USGS Openfile report 2007-1117) , updated in WOVOdat 1.1
                </p>
                <div class="alert alert-info">
                    <i class="fa fa-gears"></i> Menu ini menghubungkan aplikasi MAGMA Indonesia dengan database WOVOdat
                </div>
                @include('includes.alert')

            </div>
        </div>

        <div class="row">
            {{-- Volcano Information --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Volcano Information</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-info fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1><a href="{{ route('chambers.wovodat.volcano.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a></h1>
                            <small>
                                Giving latest information about Volcano from 
                                <strong>WOVOdat database</strong>
                            </small>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Seismic Network --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Seismic Network Information</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-keypad fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1><a href="{{ route('chambers.wovodat.seismic-network.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a></h1>
                            <small>
                                Giving all the registered Seismic Network from
                                <strong>WOVOdat database</strong>
                            </small>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Common Network --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Common Network Information</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-global fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1><a href="{{ route('chambers.wovodat.common-network.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a></h1>
                            <small>
                                Contains information about the network of stations that collect data at a particular site
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            {{-- interval Swarm --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Interval Swarm</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-global fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1><a href="{{ route('chambers.wovodat.interval-swarm.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a></h1>
                            <small>
                                Giving information about Interval Swarm from specific seismic station
                            </small>
                        </div>
                    </div>

                </div>
            </div>

            {{-- RSAM --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>RSAM</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1><a href="{{ route('chambers.wovodat.rsam.create') }}" class="btn btn-outline btn-danger" target="_blank">View</a></h1>
                            <small>
                                Generate RSAM data from specific seismic station
                            </small>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Seismic Event --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Seismic Events</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1><a href="{{ route('chambers.wovodat.event.create') }}" class="btn btn-outline btn-danger" target="_blank">View</a></h1>
                            <small>
                                Plot Seismic Events
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <div class="row">
            {{-- Deformation --}}
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Deformation</h4>
                        </div>

                        <div class="stats-icon pull-right">
                            <i class="pe-7s-map-marker fa-4x"></i>
                        </div>

                        <div class="m-t-xl">
                            <h1>
                                <a href="{{ route('chambers.wovodat.common-network.deformation-station.index') }}" class="btn btn-outline btn-danger" target="_blank">View Stations</a>
                                <a href="{{ route('chambers.wovodat.common-network.deformation-station.tilt.index') }}" class="btn btn-outline btn-danger" target="_blank">Tilt</a>
                            </h1>
                            <small>
                                    This table stores information such as a location, name, and description for stations where deformation or geodetic data are collected.
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
            </div>
        </div>
    </div>
@endsection