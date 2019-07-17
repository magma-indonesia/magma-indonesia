@extends('layouts.default')

@section('title')
    WOVOdat | Volcano
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Volcano Information
                </h2>
                <p>
                    Giving latest information about Volcano from 
                    <strong>WOVOdat database</strong>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Alias</th>
                                        <th>CAVW</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Elevation</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volcanoes as $key => $volcano)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $volcano->vd_name }}</td>
                                        <td>{{ $volcano->vd_name2 }}</td>
                                        <td>{{ $volcano->information->vd_inf_cavw }}</td>
                                        <td>{{ $volcano->information->vd_inf_status }}</td>
                                        <td>{{ $volcano->information->vd_inf_desc }}</td>
                                        <td>{{ $volcano->information->vd_inf_slat }}</td>
                                        <td>{{ $volcano->information->vd_inf_slon }}</td>
                                        <td>{{ $volcano->information->vd_inf_selev }}</td>
                                        <td>{{ $volcano->information->vd_inf_type }}</td>
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