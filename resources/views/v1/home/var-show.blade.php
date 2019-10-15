@extends('layouts.slim') 

@section('title')
Laporan Aktivitas - {{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}, periode {{ $var->periode }}
@endsection

@section('add-vendor-css')
<link href="{{ asset('slim/lib/SpinKit/css/spinkit.css') }}" rel="stylesheet">
<!-- Load Leaflet CSS and JS from CDN-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
<!-- Load Esri Leaflet from CDN -->
<script src="https://unpkg.com/esri-leaflet@2.0.8"></script>
<script src="https://unpkg.com/esri-leaflet-renderers@2.0.6/dist/esri-leaflet-renderers.js"
integrity="sha512-mhpdD3igvv7A/84hueuHzV0NIKFHmp2IvWnY5tIdtAHkHF36yySdstEVI11JZCmSY4TCvOkgEoW+zcV/rUfo0A=="
crossorigin=""></script>
<!-- Load extend Home -->
<link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
<script src="{{ asset('js/leaflet.defaultextent.js') }}"></script>
@endsection
    
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Gunung Api</a></li>
<li class="breadcrumb-item"><a href="#">Laporan Aktivitas</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}</li>
@endsection

@section('page-title')
Laporan Aktivitas
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">

        <div class="card card-blog bd-0">
            <div id="map" class="ht-250 ht-sm-300 ht-md-400 bd-0"></div>
            <div class="card-body pd-30 bd bd-t-0">
                <h5 class="blog-category">
                    @switch($var->status)
                        @case('1')
                            <h5><span class="badge badge-success">Level I (Normal)</span></h5>
                            @break
                        @case('2')
                            <h5><span class="badge badge-warning tx-white">Level II (Waspada)</span></h5>
                            @break
                        @case('3')
                            <h5><span class="badge bg-orange tx-white">Level III (Siaga)</span></h5>
                            @break
                        @default
                            <h5><span class="badge badge-danger">Level IV (Awas)</span></h5>
                            @break
                    @endswitch
                </h5>
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}, periode {{ $var->periode }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">Dibuat oleh,  {{ $var->pelapor }}</p>
                <br>
                <p class="col-lg-6 pd-0">{!! $var->intro !!}</p>
                <div class="btn-wrapper">
                    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="Laporan Aktivitas Gunung Api {{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}, periode pengamatan {{ $var->periode }}. " data-url="{{ route('v1.gunungapi.var.show', $var->id) }}" data-via="id_magma" data-lang="id" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </div>
        </div>

        <div class="card-columns column-count-2 mg-t-20">
            <div class="card">
                <img class="img-fluid" src="{{ $var->foto }}" alt="{{ $var->gunungapi }}, {{ $var->tanggal_deskripsi }}">
                <div class="media pd-t-30 pd-r-30 pd-l-30 pd-b-0 bd-t-0">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-image tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Pengamatan Visual</h6>
                        <p>{{ $var->visual }}</p>
                    </div>
                </div>
                <div class="media pd-30">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-sticky-note-o tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Keterangan Lainnya</h6>
                        <p>{{ $var->visual_lainnya }}</p>
                    </div>
                </div>
            </div>

            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-cloud tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Klimatologi</h6>
                        <p>{!! $var->klimatologi !!}</p>
                    </div>
                </div>
            </div>

            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-area-chart tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Pengamatan Kegempaan</h6>
                        @if (empty($var->gempa))
                        <p>Nihil</p>
                        @else
                        @foreach ($var->gempa as $gempa)
                        <p>{{ $gempa }}</p>
                        <hr>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="card pd-30">
                <div class="media">
                    <div class="d-flex mg-r-10 wd-50">
                        <i class="fa fa-info-circle tx-primary tx-40"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="slim-card-title">Rekomendasi</h6>
                        <p>{!! nl2br($var->rekomendasi) !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mg-t-20 pd-20">
            <div class="loading d-flex bg-gray-200 ht-300 pos-relative align-items-center">
                <div class="sk-cube-grid">
                    <div class="sk-cube sk-cube1"></div>
                    <div class="sk-cube sk-cube2"></div>
                    <div class="sk-cube sk-cube3"></div>
                    <div class="sk-cube sk-cube4"></div>
                    <div class="sk-cube sk-cube5"></div>
                    <div class="sk-cube sk-cube6"></div>
                    <div class="sk-cube sk-cube7"></div>
                    <div class="sk-cube sk-cube8"></div>
                    <div class="sk-cube sk-cube9"></div>
                </div>
            </div>
            <div id="container"></div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
@endsection

@section('add-script')
<script>

$(document).ready(function () {

    var url = '{{ url('/') }}';
    var krb_esri = 'https://services7.arcgis.com/Y24oyWJVNs6VLjiH/arcgis/rest/services/KRB_GA_ID/FeatureServer/0';
    var query = "MAG_CODE='{{ $var->code }}'";
    var map = L.map('map', {
                    zoomControl: false,
                    center: @json($var->loc),
                    zoom: 11,
                    attributionControl:false,
                }).setMinZoom(10).setMaxZoom(12);

    var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
    var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);
    var ga_icon = L.Icon.extend({options: {iconSize: [32, 32]}});
    var ga_normal = new ga_icon({iconUrl: url+'/icon/1.png'});
    var ga_waspada = new ga_icon({iconUrl: url+'/icon/2.png'});
    var ga_siaga = new ga_icon({iconUrl: url+'/icon/3.png'});
    var ga_awas = new ga_icon({iconUrl: url+'/icon/4.png'});

    switch ({{ $var->status }}) {
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
        }).bindPopup(function(layer) {
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
            return L.Util.template('<h3>'+krb+'</h3><hr/><p>{REMARK}</p>', layer.feature.properties);
        });

    var mapKrb = layerKrb.setWhere(query);
    map.addLayer(mapKrb);

    L.marker(@json($var->loc), {
        icon: gaicon,
        title: '{{ $var->gunungapi }}',
    }).addTo(map)
    .bindPopup('<h4 class="tx-center">{{ $var->gunungapi }}</h4><br/><b>'+status+'</b>',{
        closeButton: true
    }).openPopup();

    L.control.defaultExtent({position:'topleft'}).addTo(map);
    L.control.zoom({position:'topright'}).addTo(map);
    L.control.attribution({position:'bottomleft'})
            .addAttribution('MAGMA Indonesia - <a href="http://esdm.go.id" title="Badan Geologi, ESDM" target="_blank">Badan Geologi, ESDM</a>')
            .addTo(map);

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '{{ URL::signedRoute('v1.json.highcharts') }}',
        type: 'POST',
        data: {id: '{{ $var->id }}'},
        beforeSend: function() {
            $('.loading').show();
        },
        success: function(data) {
            $('.loading').remove();
            var $size = screen.width <= 767 ? '400px' : '600px';
            var $size_w = screen.width <= 767 ? 80 : 106;
            var $size_h = screen.width <= 767 ? 38 : 51;
            console.log($size);
            $('#container').css('height',$size);

            Highcharts.chart('container', {
                chart: {
                    type: 'column',
                    renderTo: 'container',
                    events: {
                        load: function() {
                            this.renderer.image('https://magma.vsi.esdm.go.id/img/logo-esdm-magma.png', 80, 40, $size_w, $size_h)
                                .add();
                        }
                    }
                },
                credits: {
                    enabled: true,
                    text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
                },
                title: {
                    text: 'Kegempaan {{ $var->gunungapi }} 90 hari Terakhir'
                },
                xAxis: {
                    categories: data.categories,
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Gempa'
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
                exporting: {
                    enabled: true,
                    scale: 1,
                    sourceHeight: 720,
                    sourceWidth: 1080
                }
            });
        }
    });

});

</script>
@endsection

