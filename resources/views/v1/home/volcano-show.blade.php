@extends('layouts.slim')

@section('title')
Gunung Api {{ $gadd->ga_nama_gapi }}
@endsection

@section('description')
Data Dasar Gunung Api {{ $gadd->ga_nama_gapi }}
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/SpinKit/css/spinkit.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
<!-- Load Leaflet CSS and JS from CDN-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin="" />
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
    integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
    crossorigin=""></script>
<!-- Load Esri Leaflet from CDN -->
<script src="https://unpkg.com/esri-leaflet@2.0.8"></script>
<script src="https://unpkg.com/esri-leaflet-renderers@2.0.6/dist/esri-leaflet-renderers.js"
    integrity="sha512-mhpdD3igvv7A/84hueuHzV0NIKFHmp2IvWnY5tIdtAHkHF36yySdstEVI11JZCmSY4TCvOkgEoW+zcV/rUfo0A=="
    crossorigin=""></script>

<!-- Load extend Home -->
<link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
<script src="{{ asset('js/leaflet.defaultextent.js') }}"></script>

<!-- Load User Marker -->
<link rel="stylesheet" href="{{ asset('css/leaflet.usermarker.css') }}">
<script src="{{ asset('js/leaflet.usermarker.js') }}"></script>

{{-- Load FullScreen --}}
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

<style>
.leaflet-popup-content-wrapper {
	border-radius: 0px;
}
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a>Gunung Api</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $gadd->ga_nama_gapi }}</li>
@endsection

@section('page-title')
Gunung Api {{ $gadd->ga_nama_gapi }}
@endsection

{{-- @section('bg-color')
bg-white
@endsection --}}

@section('main')
<div class="card card-dash-chart-one mg-b-30">
    <div class="row no-gutters">
        <div class="col-lg-8">
            <div class="left-panel" style="padding: 0px;">
                <div id="map_volcano" class="ht-250 ht-sm-350 ht-md-450 bg-gray-300"
                    style="position: relative; overflow: hidden; background-color: rgb(255, 255, 255);">
                </div>
            </div><!-- right-panel -->
        </div><!-- col-8 -->

        <div class="col-lg-4">
            <div class="pd-20">
                <h6 class="slim-card-title mg-b-5">Administratif</h6>
                <p>{{ $intro }}</p>
                <h6 class="slim-card-title mg-b-5">Morfologi Gunung Api</h6>
                <p>{{ $gadd->ga_morf_gapi }}</p>
                <h6 class="slim-card-title mg-b-5">Dominant Rock Type</h6>
                <p>{{ $gadd->ga_rtype_gapi }}</p>
                <h6 class="slim-card-title mg-b-5">Tingkat Aktivitas Saat Ini</h6>
                <p>
                    @switch($vars_daily[0]['cu_status'])
                        @case('1')
                        Level I (Normal)
                        @break
                        @case('2')
                        Level II (Waspada)
                        @break
                        @case('3')
                        Level III (Siaga)
                        @break
                        @default
                        Level IV (Awas)
                        @break
                    @endswitch
                </p>

                {{-- @auth --}}
                <p class="tx-14">
                    <a href="">Edit</a>
                </p>
                {{-- @endauth --}}
            </div><!-- left-panel -->
        </div><!-- col-4 -->

    </div><!-- row -->
</div>

<div class="row">
    <div class="col-12">
        <div class="nav-statistics-wrapper mg-b-20">
            <nav class="nav" style="display: flex">
                <a href="#tab-id" id="home-tab-id" class="nav-link active"
                    data-toggle="tab" role="tab" aria-controls="home-id"
                    aria-selected="true">Indonesia</a>
                @if ($tentang_en)
                <a href="#tab-en" id="home-tab-en" class="nav-link"
                    data-toggle="tab" role="tab" aria-controls="home-en"
                    aria-selected="true">English</a>
                @endif
            </nav>
        </div>

        <div class="tab-content" id="myTabContent">
            <div id="tab-id" role="tabpanel" aria-labelledby="home-tab-id" class="tab-pane show active">
                <p>{!! $tentang_id !!}</p>
            </div>
            @if ($tentang_en)
            <div id="tab-en" role="tabpanel" aria-labelledby="home-tab-en" class="tab-pane">
                <p style="font-style: italic">{!! $tentang_en !!}</p>
            </div>
            @endif
        </div>

        {{-- @auth --}}
        <p class="tx-14">
            <a href="">Edit</a>
        </p>
        {{-- @endauth --}}
    </div>
</div>

{{-- GALLERY --}}
<hr>
<div class="row">
    <div class="col-12">
        <h4 class="tx-inverse tx-lato tx-bold mg-b-30">
            Gallery
        </h4>

        <div class="row">
            @foreach ($vars_daily as $var)
            <div class="col-lg-2 col-sm">
                <div class="card bd-0 mg-b-10">

                    <a href="{{ $var->var_image }}" data-lightbox="file-set"
                        data-title="{{ $var->ga_nama_gapi.'_'.$var->data_date.' '.$var->periode }}">
                        <img src="{{ $var->var_image }}" class="img-fluid" alt="{{ $var->ga_nama_gapi.'_'.$var->data_date.' '.$var->periode }}" data-value="{{ $var->no }}">
                    </a>

                    <div class="card-body bd bd-t-0">
                        <p class="card-text">{{ $var->ga_nama_gapi.'_'.$var->data_date.' '.$var->periode }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- SEJARAH AKTIVITAS --}}
<hr>
<div class="row mg-b-30">
    <div class="col-12">
        <h4 class="tx-inverse tx-lato tx-bold mg-b-30">
            Sejarah Aktivitas
        </h4>

        @if ($gadd->dataDasarSejarahLetusan->count() > 10)
        <div id="accordion3" class="accordion-two" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingTwo3">
                    <a class="tx-gray-800 transition collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapseTwo3" aria-expanded="false" aria-controls="collapseTwo3">
                        Lihat sejarah letusan ({{ $gadd->dataDasarSejarahLetusan->count() }})
                    </a>
                </div>
                <div id="collapseTwo3" class="collapse" role="tabpanel" aria-labelledby="headingTwo3" style="">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mg-b-0">
                                <thead>
                                    <tr>
                                        <th class="wd-30p">Waktu</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gadd->dataDasarSejarahLetusan as $sejarah)
                                    <tr>
                                        <td class="wd-30p">{{ $sejarah['date_text'] }}</td>
                                        <td>{{ $sejarah['description'] }} <div><a href="">Edit</a></div></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mg-b-0">
                        <thead>
                            <tr>
                                <th class="wd-30p">Waktu</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gadd->dataDasarSejarahLetusan as $sejarah)
                            <tr>
                                <td class="wd-30p">{{ $sejarah['date_text'] }}</td>
                                <td>{{ $sejarah['description'] }} <a href="">Edit</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif

    </div>
</div>

{{-- GEOLOGI --}}
<hr>
<div class="row mg-b-30">
    <div class="col-12">
        <h4 class="tx-inverse tx-lato tx-bold mg-b-30">
            Geologi
        </h4>

        @foreach ($geologis as $geologi)

        @if ($geologi['content'])
        <div id="accordion-{{ $geologi['id'] }}" class="accordion-two" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="heading-{{ $geologi['id'] }}">
                    <a class="tx-gray-800 transition collapsed" data-toggle="collapse" data-parent="#accordion-{{ $geologi['id'] }}" href="#collapse-{{ $geologi['id'] }}" aria-expanded="false" aria-controls="collapse-{{ $geologi['id'] }}">
                        {{ $geologi['header'] }}
                    </a>
                </div>

                <div id="collapse-{{ $geologi['id'] }}" class="collapse" role="tabpanel" aria-labelledby="heading-{{ $geologi['id'] }}" style="">
                    <div class="card-body">
                        <p>{{ $geologi['content'] }}</p>

                        {{-- @auth --}}
                        <p class="tx-14">
                            <a href="">Edit</a>
                        </p>
                        {{-- @endauth --}}
                    </div>
                </div>
            </div>
        </div>
        @endif


        @endforeach

    </div>
</div>

{{-- TINGKAT AKTIVITAS TERKINI --}}
<hr>
<div class="row mg-b-30">
    <div class="col-12">
        <div class="report-summary-header">
            <div>
                <h4 class="tx-inverse mg-b-3">Tingkat Aktivitas Terkini</h4>
                <p class="mg-b-0"><i class="icon ion-calendar mg-r-3"></i> {{ $activity_date }}</p>
            </div>
            <div>
                <a href="{{ route('v1.gunungapi.var') }}" class="btn btn-secondary"><i class="icon ion-ios-clock-outline tx-22"></i> Laporan Aktivitas</a>
                <a href="" class="btn btn-secondary"><i class="icon ion-ios-gear-outline tx-24"></i> Edit Settings</a>
            </div>
        </div>
    </div>
</div>

{{-- CHART --}}
<hr>
<div class="row mg-b-30">
    <div class="col-12">
        <h4 class="tx-inverse tx-lato tx-bold mg-b-30">
            @switch($vars_daily[0]['cu_status'])
                @case('1')
                Level I (Normal)
                @break
                @case('2')
                Level II (Waspada)
                @break
                @case('3')
                Level III (Siaga)
                @break
                @default
                Level IV (Awas)
                @break
            @endswitch
        </h4>

        <div id="container"></div>
    </div>
</div>

{{-- KAWASAN RAWAN BENCANA --}}
<hr>
<div class="row mg-b-10">
    <div class="col-12">
        <div class="report-summary-header">
            <div>
                <h4 class="tx-inverse mg-b-3">Peta Kawasan Rawan Bencana (KRB) {{ $gadd->ga_nama_gapi }}</h4>
                <p class="mg-b-0" style="font-style: italic">{{ \Illuminate\Support\Str::title($gadd->krbGunungApi->created_by) }} ({{ $gadd->krbGunungApi->year_published }})</p>
            </div>
            <div>
                <a href="{{ route('v1.gunungapi.var') }}" class="btn btn-secondary"><i class="icon ion-ios-download-outline tx-20"></i> Download</a>
            </div>
        </div>

        <div class="card card-dash-chart-one mg-t-20 mg-sm-t-30">
            <div class="row no-gutters">
                <div class="col-lg-4">
                    <div class="pd-20">

                        @foreach ($krbs as $krb)
                        <h6 class="slim-card-title mg-b-5">{{ $krb['long_text'] }}</h6>
                        <p>{{ $krb['area_id'] }}</p>
                        <p style="font-style: italic">{{ $krb['area_en'] }}</p>
                        <hr>
                        @endforeach

                    </div>
                </div>
                <div class="col-lg-8">
                    <div id="map" class="ht-250 ht-sm-350 ht-md-450 bg-gray-300"
                        style="position: relative; overflow: hidden; background-color: rgb(255, 255, 255);">
                    </div>
                </div><!-- col-8 -->
            </div><!-- row -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="nav-statistics-wrapper mg-b-20">
            <nav class="nav" style="display: flex">

                @foreach ($krbs as $krb)
                <a href="{{ $krb['href'] }}" id="home-{{ $krb['id'] }}" class="nav-link"
                    data-toggle="tab" role="tab" aria-controls="home-{{ $krb['id'] }}"
                    aria-selected="true">{{ $krb['text'] }}</a>
                @endforeach

            </nav>
        </div>

        <div class="tab-content" id="myTabContent">

            @foreach ($krbs as $krb)
            <div id="{{ $krb['id'] }}" role="tabpanel" aria-labelledby="home-{{ $krb['id'] }}" class="tab-pane">

                <h6 class="slim-card-title mg-b-5">{{ $krb['long_text'] }}</h6>
                <p class="mg-b-0">{{ $krb['area_id'] }}</p>
                <p style="font-style: italic">{{ $krb['area_en'] }}</p>

                <p>{!! $krb['indonesia'] !!}</p>
                <p style="font-style: italic">{!! $krb['english'] !!}</p>

                {{-- @auth --}}
                <p class="tx-14">
                    <a href="">Edit</a>
                </p>
                {{-- @endauth --}}
            </div>
            @endforeach

        </div>
    </div>
</div>

<hr>
<div class="row row-sm mg-t-20">
    <div class="col-lg-12">
        <div class="card card-table">
            <div class="card-header">
                <h6 class="slim-card-title">Daftar Laporan 24 Jam</h6>
            </div><!-- card-header -->
            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                        <tr class="tx-10">
                            <th class="pd-y-5">Waktu Laporan</th>
                            <th class="pd-y-5">Tingkat Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vars_daily as $var_daily)
                        <tr>
                            <td>
                                <a href="{{ URL::signedRoute('v1.gunungapi.var.show', ['id' => $var_daily->no]) }}" class="tx-inverse tx-14 tx-medium d-block">{{ $var_daily->var_data_date->formatLocalized('%A, %d %B %Y') }}</a>
                            </td>
                            <td class="valign-middle">
                                @switch($var_daily->cu_status)
                                    @case('1')
                                    Level I (Normal)
                                    @break
                                    @case('2')
                                    Level II (Waspada)
                                    @break
                                    @case('3')
                                    Level III (Siaga)
                                    @break
                                    @default
                                    Level IV (Awas)
                                    @break
                                @endswitch
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- table-responsive -->
            <div class="card-footer tx-12 pd-y-15 bg-transparent">
                <a href="{{ route('v1.gunungapi.var') }}"><i class="fa fa-angle-down mg-r-5"></i>Lihat seluruh laporan</a>
            </div><!-- card-footer -->
        </div><!-- card -->
    </div><!-- col-6 -->
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {

    $(".nav .nav-link:first-child" ).addClass('show active');
    $(".tab-content .tab-pane:first-child" ).addClass('show active');

    function maxPopUpWidth() {
        var width = $(window).width(),
            maxWidth = 0.3 * width;
        if (width <= 767) {
            return 360;
        }
        return maxWidth;
    }

    var url = '{{ url('/') }}';
    var krb_esri = '{{ $home_krb->url }}';
    var query = "MAG_CODE='{{ $var->ga_code }}'";
    var urlShowKrb = "{{ URL::signedRoute('v1.json.gunungapi.show', $gadd->slug ) }}";
    var map = L.map('map', {
        zoomControl: false,
        center: [{{ $gadd->ga_lat_gapi }}, {{ $gadd->ga_lon_gapi }}],
        zoom: 11,
        attributionControl:false,
    }).setMinZoom(10).setMaxZoom(12);

    var map_volcano = L.map('map_volcano', {
        zoomControl: false,
        center: [{{ $gadd->ga_lat_gapi }}, {{ $gadd->ga_lon_gapi }}],
        zoom: 4,
        attributionControl:false,
    }).setMinZoom(3).setMaxZoom(10);

    var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
    var layerEsriStreetsVolcano = L.esri.basemapLayer('NationalGeographic').addTo(map_volcano);
    var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);
    var ga_icon = L.Icon.extend({options: {iconSize: [32, 32]}});
    var ga_normal = new ga_icon({iconUrl: url+'/icon/1.png'});
    var ga_waspada = new ga_icon({iconUrl: url+'/icon/2.png'});
    var ga_siaga = new ga_icon({iconUrl: url+'/icon/3.png'});
    var ga_awas = new ga_icon({iconUrl: url+'/icon/4.png'});

    switch ({{ $var->cu_status }}) {
        case 1:
            var gaicon = ga_normal;
            var status ='Level I (Normal)';
            break;
        case 2:
            var gaicon = ga_waspada;
            var status ='Level II (Waspada)';
            break;
        case 3:
            var gaicon = ga_siaga;
            var status ='Level III (Siaga)';
            break;
        default:
            var gaicon = ga_awas;
            var status ='Level IV (Awas)';
            break;
    };

    var layerKrb = L.esri.featureLayer({
            url: krb_esri,
        }).bindPopup('Loading..', {
            closeButton: true,
            className: 'anto',
            maxWidth: maxPopUpWidth()
        }).on('click', function (e) {

            var popup = this.getPopup();
            var layer = e.layer;

            switch (layer.feature.properties.INDGA) {
                case 1:
                    var krb = 'Kawasan Rawan Bencana (KRB) I';
                    break;
                case 2:
                    var krb = 'Kawasan Rawan Bencana (KRB) II';
                    break;
                default:
                    var krb = 'Kawasan Rawan Bencana (KRB) III';
                    break;
            }

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: urlShowKrb,
                type: 'POST',
                data: {
                    code: layer.feature.properties.MAG_CODE,
                    lcode: layer.feature.properties.LCODE,
                },
                success: function (response) {
                    // var content = L.Util.template('<h3>'+krb+'</h3><hr/><p>{signature}</p>', response.request);

                    console.log(response);


                    popup.setContent(response.indonesia);
                    popup.openPopup();
                },
            });


            // return L.Util.template('<h3>'+krb+'</h3><hr/><p>{REMARK}</p>', layer.feature.properties);
            // console.log(e);
        });

    var mapKrb = layerKrb.setWhere(query);
    map.addLayer(mapKrb);

    L.marker([{{ $gadd->ga_lat_gapi }}, {{ $gadd->ga_lon_gapi }}], {
        icon: gaicon,
        title: '{{ $gadd->ga_nama_gapi }}',
    }).addTo(map)
    .bindPopup('<h4 class="tx-center">{{ $gadd->ga_nama_gapi }}</h4><br/><b>'+status+'</b>',{
        closeButton: true
    }).openPopup();

    L.marker([{{ $gadd->ga_lat_gapi }}, {{ $gadd->ga_lon_gapi }}], {
        icon: gaicon,
        title: '{{ $gadd->ga_nama_gapi }}',
    }).addTo(map_volcano)
    .bindPopup('<h4 class="tx-center">{{ $gadd->ga_nama_gapi }}</h4><br/><b>'+status+'</b>',{
        closeButton: true
    }).openPopup();

    L.control.defaultExtent({position:'topleft'}).addTo(map);
    L.control.defaultExtent({position:'topleft'}).addTo(map_volcano);

    L.control.zoom({position:'topright'}).addTo(map);
    L.control.zoom({position:'topright'}).addTo(map_volcano);
    L.control.attribution({position:'bottomleft'})
            .addAttribution('MAGMA Indonesia - <a href="http://esdm.go.id" title="Badan Geologi, ESDM" target="_blank">Badan Geologi, ESDM</a>').addTo(map);
    L.control.attribution({position:'bottomleft'})
            .addAttribution('MAGMA Indonesia - <a href="http://esdm.go.id" title="Badan Geologi, ESDM" target="_blank">Badan Geologi, ESDM</a>').addTo(map_volcano);

    map.addControl(new L.Control.Fullscreen());
    map_volcano.addControl(new L.Control.Fullscreen());

    // Get User Location
    map.locate({enableHighAccuracy:true})
        .on('locationfound',function(e){
            var user_marker = L.userMarker([e.latitude, e.longitude], {pulsing:true, accuracy:200, smallIcon:false});
            user_marker.addTo(map).bindPopup('Anda Berada di Sini',{
                closeButton:false
            }).openPopup();
        });

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ URL::signedRoute('v1.json.highcharts') }}",
        type: 'POST',
        data: {
            id: '{{ $var->no }}',
            start: '{{ now()->subDays(14)->format('Y-m-d') }}'
        },
        beforeSend: function() {
            $('.loading').show();
        },
        success: function(data) {
            $('.loading').remove();

            Highcharts.chart('container', {
                chart: {
                    type: 'column',
                    renderTo: 'container',
                    backgroundColor: 'transparent',
                },
                credits: {
                    enabled: false,
                },
                title: {
                    text: null,
                },
                xAxis: {
                    categories: data.categories,
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null
                    },
                    allowDecimals: false
                },
                legend: {
                    enabled: true,
                },
                tooltip: {
                    enabled: true,
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: false,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                        }
                    }
                },
                series: data.series,
            });
        },
    });
});
</script>
@endsection