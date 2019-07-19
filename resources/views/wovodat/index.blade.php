@extends('layouts.default') 

@section('title') 
    MAGMA | WOVOdat
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                @include('includes.alert')
                <h2>
                    Selamat datang di aplikasi <strong>WOVOdat</strong>
                </h2>
                <p class="p-md">
                    <strong class="text-danger">WOVOdat</strong> is a Database of Volcanic Unrest; instrumentally and visually recorded changes in seismicity, ground deformation, gas emission, and other parameters from their normal baselines. The database is created per the structure and format as described in the WOVOdat 1.0 report of Venezky and Newhall (USGS Openfile report 2007-1117) , updated in WOVOdat 1.1
                </p>
                <div class="alert alert-info">
                    <i class="fa fa-gears"></i> Menu ini menjembatani aplikasi MAGMA Indonesia dengan database WOVOdat
                </div>
            </div>
        </div>
    </div>
@endsection