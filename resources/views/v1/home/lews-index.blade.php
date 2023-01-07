@extends('layouts.slim')

@section('title')
Landslide Early Warning System (LEWS)
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/SpinKit/css/spinkit.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gerakan Tanah</a></li>
<li class="breadcrumb-item active" aria-current="page">Landslide Early Warning System</li>
@endsection

@section('page-title')
Landslide Early Warning System (LEWS)
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table mg-b-0 tx-13">
                <thead>
                    <tr class="tx-10">
                        <th class="wd-10p pd-y-5 tx-center">Nama Stasiun</th>
                        <th class="pd-y-5">Dusun</th>
                        <th class="pd-y-5">Desa</th>
                        <th class="pd-y-5">Kecamatan</th>
                        <th class="pd-y-5">Kabupaten</th>
                        <th class="pd-y-5">Kabupaten</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card mg-t-20 pd-20">
            <div id="container"></div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/highcharts.js"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    var $size = screen.width <= 767 ? '400px' : '600px';
    var $size_w = screen.width <= 767 ? 80 : 106;
    var $size_h = screen.width <= 767 ? 38 : 51;

    Highcharts.chart('container', {
        chart: {
            type: 'column',
            renderTo: 'container',
            events: {
                load: function() {
                    this.renderer.image('https://magma.vsi.esdm.go.id/img/logo-esdm-magma.png', 40, 20, $size_w, $size_h)
                        .add();
                }
            }
        },
        credits: {
            enabled: true,
            text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
        },
        title: {
            text: 'Monitoring Gerakan Tanah'
        },
    });
});
</script>
@endsection