<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#007fff">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="description" content="MAGMA Indonesia - Bridging the will of nature to society">
        <meta name="author" content="Kementerian ESDM">
        <link href="{{ asset('favicon.png') }}" rel="shortcut icon">
        <title>MAGMA Indonesia - Bridging the will of nature to society</title>

        <!-- Calcite Maps Bootstrap -->
        <link rel="stylesheet" href="{{ asset('css/calcite-maps-bootstrap.min-v0.3.css') }}">
    
        <!-- Calcite Maps -->
        <link rel="stylesheet" href="{{ asset('css/calcite-maps-esri-leaflet.min-v0.3.css') }}">
        <link rel="stylesheet" href="{{ asset('fonts/calcite/calcite-ui.css') }}">

        <!-- Load Leaflet CSS and JS from CDN-->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>

        <!-- Load Esri Leaflet from CDN -->
        <script src="https://unpkg.com/esri-leaflet@2.0.8"></script>
        <script src="https://unpkg.com/esri-leaflet-renderers@2.0.6/dist/esri-leaflet-renderers.js"
        integrity="sha512-mhpdD3igvv7A/84hueuHzV0NIKFHmp2IvWnY5tIdtAHkHF36yySdstEVI11JZCmSY4TCvOkgEoW+zcV/rUfo0A=="
        crossorigin=""></script>

        <!-- Load MouseCoordinate from CDN -->
        <link rel="stylesheet" href="{{ asset('css/Leaflet.Coordinates-0.1.5.css') }}">
        <script src="{{ asset('js/Leaflet.Coordinates-0.1.5.min.js') }}"></script>

        <!-- Load Leaflet Ruler-->
        <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/gokertanrisever/leaflet-ruler/master/src/leaflet-ruler.css">
        <link rel="stylesheet" href="{{ asset('css/leaflet-ruler-m.css') }}">
        <script src="https://cdn.rawgit.com/gokertanrisever/leaflet-ruler/master/src/leaflet-ruler.js"></script>

        <!-- Load extend Home -->
        <link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
        <script src="{{ asset('js/leaflet.defaultextent.js') }}"></script>

        <!-- Load CSS Magma-->
        <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    </head>

    <body class="calcite-nav-top calcite-margin-all calcite-zoom-bottom-right calcite-layout-small-title">
        <!-- Navbar -->
        <nav class="navbar calcite-navbar navbar-fixed-top calcite-bg-custom calcite-text-light" style="background-color: rgba(0, 127, 255, 0.8);">
            
            <!-- Menu -->
            <div class="dropdown calcite-dropdown calcite-bg-custom calcite-text-light" role="presentation">
                <a class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="calcite-dropdown-toggle">
                        <span class="sr-only">Toggle dropdown menu</span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <ul class="dropdown-menu calcite-bg-custom" style="background-color: rgb(0, 127, 255, 0.8);">
                    <li><a role="button" data-target="#modalSplash" data-toggle="modal" aria-haspopup="true"><span class="glyphicon glyphicon-info-sign"></span> Tentang MAGMA</a></li>
                    <li><a role="button" data-target="#modalSources" data-toggle="modal" data-dismiss="modal" aria-haspopup="true"><span class="glyphicon glyphicon-info-sign"></span> Sumber Data</a></li>
                    <li><a role="button" data-target="#panelInfo" aria-haspopup="true"><span class="glyphicon glyphicon-tasks"></span> Status Gunung Api</a></li>
                    <li><a role="button" data-target="#panelVolcanoes" aria-haspopup="true"><span class="glyphicon glyphicon-th-list"></span> Gunung Api</a></li>
                    <li class="visible-xs"><a href="#"><span class="glyphicon glyphicon-th-list"></span> VONA</a></li>
                    <li><a role="button" data-target="#panelBasemaps" aria-haspopup="true"><span class="glyphicon glyphicon-th-large"></span> Basemaps</a></li>
                    <li><a role="button" id="calciteToggleNavbar" aria-haspopup="true"><span class="glyphicon glyphicon-fullscreen"></span> Full Map</a></li>
                </ul>
            </div>

            <!-- Title -->
            <div class="calcite-title calcite-overflow-hidden">
                <a href="https://www.esdm.go.id/" target="_blank"><img src="{{ asset('logo-esdm.png') }}" style="height:24px;"></a>
                <img src="{{ asset('favicon.png') }}" style="height:24px;margin-left:15px;">
                <span class="calcite-title-main" style="margin-left:15px;"> MAGMA Indonesia</span>
                <span class="calcite-title-sub hidden-xs" style="margin-left:15px;">Bridging the will of nature to society</span>
                <span class="calcite-title-divider hidden-xs"></span>
                {{-- <span class="calcite-title-sub hidden-xs"><a role="button" data-target="#panelVolcanoes" aria-haspopup="true">Gunung Api</a></span> --}}
                {{-- <span class="calcite-title-divider hidden-xs"></span> --}}
                <span class="calcite-title-sub hidden-xs"><a href="#">VONA</a></span>
                <span class="calcite-title-divider hidden-xs"></span>
                <span class="calcite-title-sub hidden-xs"><a href="#">Press Release</a></span>
            </div>

        </nav>

        <!-- Map Container  -->
        <div class="calcite-map">                
            <div id="map" class="calcite-map-absolute"></div>    
        </div>

        <!-- Panel -->
        <div class="calcite-panels calcite-panels-left calcite-bg-custom calcite-text-light panel-group calcite-bg-custom" role="tablist" aria-multiselectable="true">

            <!-- Info Panel -->
            <div id="panelInfo" style="background-color: rgba(0, 127, 255, 0.8)" class="panel collapse">
                <div id="headingInfo" class="panel-heading" role="tab">
                    <div class="panel-title">
                        <a class="panel-toggle" role="button" data-toggle="collapse" href="#collapseInfo" aria-expanded="true" aria-controls="collapseInfo"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span class="panel-label">Informasi Gunung Api</span></a> 
                        <a class="panel-close" role="button" data-toggle="collapse" data-target="#panelInfo"><span class="esri-icon esri-icon-close" aria-hidden="true"></span></a>  
                    </div>
                </div>
                <div id="collapseInfo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingInfo">
                    <div class="panel-body" style="background-color: white;color: black;">
                        <h4 class="text-bold">Level IV - (Awas)</h4>
                        @if($gadds->where('ga_status',4)->count())
                        Berjumlah {{$gadds->where('ga_status',4)->count()}} Gunung Api, yaitu
                            <ul style="padding-left:15px;">
                            @foreach ($gadds->where('ga_status',4)->all() as $key => $gadd)
                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                            @endforeach
                            </ul>
                        @else
                        <p>Tidak ada gunung api berstatus Level IV - (Awas)</p>
                        @endif

                        <hr>
                        <h4 class="text-bold">Level III - (Siaga)</h4>
                        @if($gadds->where('ga_status',3)->count())
                        Berjumlah {{$gadds->where('ga_status',3)->count()}} Gunung Api, yaitu Gunung
                            <ul style="padding-left:15px;">
                            @foreach ($gadds->where('ga_status',3)->all() as $key => $gadd)
                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                            @endforeach
                            </ul>
                        @else
                        <p>Tidak ada gunung api berstatus Level III - (Siaga)</p>
                        @endif

                        <hr>
                        <h4 class="text-bold">Level II - (Waspada)</h4>
                        @if($gadds->where('ga_status',2)->count())
                        Berjumlah {{$gadds->where('ga_status',2)->count()}} Gunung Api, yaitu Gunung
                            <ul style="padding-left:15px;">
                            @foreach ($gadds->where('ga_status',2)->all() as $key => $gadd)
                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                            @endforeach
                            </ul>
                        @else
                        <p>Tidak ada gunung api berstatus Level II - (Waspada)</p>
                        @endif

                        <hr>
                        <h4 class="text-bold">Level I - (Normal)</h4>
                        @if($gadds->where('ga_status',1)->count())
                        Berjumlah {{$gadds->where('ga_status',1)->count()}} Gunung Api, yaitu:
                            <ul style="padding-left:15px;">
                            @foreach ($gadds->where('ga_status',1)->all() as $key => $gadd)
                            <li>{{ $gadd->ga_nama_gapi}} - {{ $gadd->ga_prov_gapi}}</li>
                            @endforeach
                            </ul>
                        @else
                        <p>Tidak ada gunung api berstatus Level I - (Normal)</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Search Volcano Panel -->
            <div id="panelVolcanoes" style="background-color: rgba(0, 127, 255, 0.8)" class="panel collapse">
                <div id="headingVolcanoes" class="panel-heading" role="tab">
                    <div class="panel-title">
                        <a class="panel-toggle collapsed" role="button" data-toggle="collapse" href="#collapseVolcanoes" aria-expanded="false" aria-controls="collapseVolcanoes"><span class="glyphicon glyphicon-search" aria-hidden="true"></span><span class="panel-label">Gunungapi</span></a> 
                        <a class="panel-close" role="button" data-toggle="collapse" data-target="#panelVolcanoes"><span class="esri-icon esri-icon-close" aria-hidden="true"></span></a>  
                    </div>
                </div>

                <div id="collapseVolcanoes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingVolcanoes">
                    <div class="panel-body">
                        <select id="gunung_api" class="form-control">
                            @foreach ($gadds as $key => $gadd)
                            <option value="{{ $gadd->ga_code }}">{{ $gadd->ga_nama_gapi.', '.$gadd->ga_prov_gapi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Basemaps Panel -->
            <div id="panelBasemaps" style="background-color: rgba(0, 127, 255, 0.8)" class="panel collapse">
                <div id="headingBasemaps" class="panel-heading" role="tab">
                    <div class="panel-title">
                        <a class="panel-toggle collapsed" role="button" data-toggle="collapse" href="#collapseBasemaps" aria-expanded="false" aria-controls="collapseBasemaps"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span><span class="panel-label">Basemaps</span></a> 
                        <a class="panel-close" role="button" data-toggle="collapse" data-target="#panelBasemaps"><span class="esri-icon esri-icon-close" aria-hidden="true"></span></a>  
                    </div>
                </div>
                <div id="collapseBasemaps" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBasemaps">
                    <div class="panel-body">
                        <select id="selectStandardBasemap" class="form-control">
                            <option value="Streets">Streets</option>
                            <option value="Imagery">Satellite</option>
                            <option selected value="NationalGeographic">National Geographic</option>
                            <option value="Topographic">Topographic</option>
                            <option value="Gray">Gray</option>
                            <option value="DarkGray">Dark Gray</option>
                            <option value="OpenStreetMap">Open Street Map</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSplash" tabindex="-1" role="dialog" aria-labelledby="splashlModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="splash-body">
                                    <div class="text-center">
                                        <h3>Selamat Datang di MAGMA Indonesia</h3>
                                        <hr>
                                        <p><strong>MAGMA Indonesia (Multiplatform Application for Geohazard Mitigation and Assessment in Indonesia) adalah aplikasi multiplatform (web & mobile)</strong> dalam jaringan berisikan informasi dan rekomendasi kebencanaan geologi terintegrasi (gunungapi, gempabumi, tsunami, dan gerakan tanah) yang disajikan kepada masyarakat secara kuasi-realtime dan interaktif</p>
                                        <hr>
                                        <p><strong>Sistem ini dibangun dan dikembangkan secara mandiri oleh PNS Pusat Vulkanologi dan Mitigasi Bencana Geologi (PVMBG) sejak tahun 2015</strong> dengan menggunakan teknologi terkini berbasis open-source. MAGMA Indonesia meliputi aplikasi yang digunakan secara internal/pegawai (analisis data dan pelaporan) maupun eksternal/publik (informasi dan rekomendasi)</p>
                                        <hr>
                                        <p>Prinsip utama MAGMA Indonesia adalah mengubah data menjadi informasi dan rekomendasi yang mudah dipahami oleh masyarakat umum. MAGMA Indonesia adalah sistem yang terus belajar dan berevolusi, fitur-fitur baru akan lahir disesuaikan dengan kebutuhan jaman. Diharapkan bahwa seluruh informasi kebencanaan geologi nantinya dapat diakses oleh masyarakat dengan mudah melalui satu jendela (single-window). Hal ini merupakan manifestasi hadirnya negara secara aktif di tengah-tengah masyarakat dalam upaya mitigasi bencana geologi di Indonesia.</p>
                                        <br>
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Sumber Data</button>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Penghargaan</button>
                                            </div>
                                        </div>
                                        <br> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          
        <div class="modal fade" id="modalSources" tabindex="-1" role="dialog" aria-labelledby="splashlModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="splash-body">
                                    <div class="text-center">
                                        <h3>Sumber Data</h3>
                                        <hr>
                                        <h4>Pusat Vulkanologi dan Mitigasi Bencana Geologi</h4>
                                        <ul class="list-inline">
                                            <li>Peta Kerawanan/Kerentanan</li>
                                            <li>Data Dasar Gunungapi</li>
                                            <li>Data Pengamatan Rutin</li>
                                            <li>Laporan Tanggapan</li>
                                            <li>Laporan Pemeriksaan</li>
                                        </ul>
                                        <hr>
                                        <h4>ESRI Arcgisonline Mapserver</h4>
                                        <ul class="list-inline">
                                            <li>Peta Dasar/Basemap</li>
                                        </ul>
                                        <hr>
                                        <h4>Smithsonian Institution, Global Volcanism Program</h4>
                                        <ul class="list-inline">
                                            <li>Data Dasar Gunungapi</li>
                                        </ul>
                                        <hr>
                                        <h4>Badan Meteorologi Klimatologi Geofisika</h4>
                                        <ul class="list-inline">
                                            <li>Pusat Sumber Gempa</li>
                                        </ul>
                                        <hr>
                                        <h4>The Global Centroid Moment Tensor </h4>
                                        <ul class="list-inline">
                                            <li>Solusi Mekanisme Gempa</li>
                                        </ul>
                                        <hr>
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        <br> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======= -->
        <!-- Leaflet --> 
        <!-- ======= -->
        <script>

            var url = '{{ url('/') }}';
            console.log(screen.width);
            console.log(screen.height);

            // Icon Gunung Api
            var ga_icon = L.Icon.extend({options: {iconSize: [32, 32]}});
            var ga_icon_b = L.Icon.extend({options: {iconSize: [48, 58],className:'gb-blinking'}});
            var erupt = L.Icon.extend({options: {iconSize: [48, 72]}});
            var gempa_icon = L.Icon.extend({options: {iconSize: [32, 39]}});
            var gempa_icon_b = L.Icon.extend({options: {iconSize: [48, 58],className:'gb-blinking'}});

            here = new ga_icon({iconUrl: url+'/icon/here.png'}),
            ga_normal = new ga_icon({iconUrl: url+'/icon/1.png'}),
            ga_waspada = new ga_icon({iconUrl: url+'/icon/2.png'}),
            ga_siaga = new ga_icon({iconUrl: url+'/icon/3.png'}),
            ga_awas = new ga_icon({iconUrl: url+'/icon/4.png'}),
            erupt_4 = new erupt({iconUrl: url+'/icon/erupt4.gif'}),
            erupt_3 = new erupt({iconUrl: url+'/icon/erupt3.gif'}),
            erupt_2 = new erupt({iconUrl: url+'/icon/erupt2.gif'}),
            erupt_1 = new erupt({iconUrl: url+'/icon/erupt1.gif'}),
            icon_gertan = new ga_icon({iconUrl: url+'/icon/gt.png'}),
            icon_gertan_b = new ga_icon_b({iconUrl: url+'/icon/gt.png'}),
            icon_gertan_t = new gempa_icon({iconUrl: url+'/icon/gt-t.png'}),
            icon_gertan_t_b = new gempa_icon_b({iconUrl: url+'/icon/gt-t.png'}),
            icon_gempa = new ga_icon({iconUrl: url+'/icon/gb.png'}),
            icon_gempa_b = new gempa_icon_b({iconUrl: url+'/icon/gb.png'}),
            icon_gempa_t = new gempa_icon({iconUrl: url+'/icon/gb-t.png'}),
            icon_gempa_t_b = new gempa_icon_b({iconUrl: url+'/icon/gb-t.png'});

            // Batas Map Indonesia
            var bounds = new L.LatLngBounds(new L.LatLng(-21.41, 153.41), new L.LatLng(14.3069694978258, 73.65));

            //Zoom changer
            function zoomResponsive() {
                var width = screen.width;
                    if (width <= 767) {
                        return 4;
                    }
                    return 6;
            }
            
            // Map Inititiation
            var map = L.map('map', {
                        zoomControl: false,
                        center: [-4.26, 115.66],
                        zoom: zoomResponsive(),
                        attributionControl:false,
                    }).setMinZoom(5)
                    .setMaxZoom(12);
                    // .setMaxBounds(bounds);

            // Add Layers
            var layerNg = L.esri.basemapLayer('NationalGeographic');
            var layerEsriStreets = L.esri.basemapLayer('Imagery').addTo(map);
            var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);

            var layerLabels = null

            var latlongControl = L.control.coordinates({
                position:"bottomright", //optional default "bootomright"
                decimals:2, //optional default 4
                decimalSeperator:".", //optional default "."
                labelTemplateLat:"Latitude: {y}", //optional default "Lat: {y}"
                labelTemplateLng:"Longitude: {x}", //optional default "Lng: {x}"
                enableUserInput:false, //optional default true
                useDMS:false, //optional default false
                useLatLngOrder: true, //ordering of labels, default false-> lng-lat
                markerType: L.marker, //optional default L.marker
                markerProps: {} //optional default {},
            });

            var rulerControl = L.control.ruler({
                position: 'bottomright'
            });

            if (!(L.Browser.mobile)){
                latlongControl.addTo(map);
                rulerControl.addTo(map);
            };

            L.control.scale().addTo(map);
            L.control.defaultExtent().addTo(map);
            L.control.zoom({position:'bottomright',}).addTo(map);
            L.control.attribution({position:'bottomright'})
                .setPrefix('MAGMA Indonesia')
                .addAttribution('<a href="http://esdm.go.id" title="Badan Geologi, ESDM" target="_blank">Badan Geologi, ESDM</a>')
                .addTo(map);

            //Get High Accuracy Location
            // map.locate({enableHighAccuracy:true})
            //     .on('locationfound',function(e){
            //         L.marker([e.latitude, e.longitude],{icon: here})
            //         .addTo(map)
            //         .bindPopup('Anda Berada di Sini',{
            //             closeButton:false
            //         })
            //         .openPopup();
            //         map.flyTo([e.latitude, e.longitude], 8, {duration:5});
            // });
            
        </script>
        
        <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('vendor/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('js/calcitemaps-v0.3.js') }}"></script>

        <!-- ====== -->
        <!-- jQuery --> 
        <!-- ====== -->
        <script>
        $(document).ready(function () {

            // ==========
            // Gunung Api
            // ==========

            var markersGunungApi = @json($gadds),
                maxHeight = 0.7 * ($(window).height()),
                GunungapiNormal = [],
                GunungapiWaspada = [],
                GunungapiSiaga = [],
                GunungapiAwas = [],
                layerNormal,
                layerWaspada,
                layerSiaga,
                layerAwas,
                addKrb = [],
                markers = {};

            
            // Set Marker Gunung Api
            $.each(markersGunungApi, function(index, gunungapi) {
                var ga_code = gunungapi.ga_code,
                    setTitle = gunungapi.ga_nama_gapi,
                    setLongitude = gunungapi.ga_lon_gapi,
                    setLatitude = gunungapi.ga_lat_gapi,
                    hasVona = gunungapi.has_vona,
                    setStatus = gunungapi.ga_status;

                switch (setStatus) {
                    case 1:
                        var gaicon = hasVona ? erupt_1 : ga_normal,
                            status ='Level I (Normal)';
                        break;
                    case 2:
                        var gaicon = hasVona ? erupt_2 : ga_waspada,
                            status ='Level II (Waspada)';
                        break;
                    case 3:
                        var gaicon = hasVona ? erupt_3 : ga_siaga,
                            status ='Level III (Siaga)';
                        break;
                    default:
                        var gaicon = hasVona ? erupt_4 : ga_awas,
                        status ='Level IV (Awas)';
                };

                var markerId = L.marker([gunungapi.ga_lat_gapi, gunungapi.ga_lon_gapi], {
                                    icon: gaicon,
                                    title: setTitle,
                                    zIndexOffset: hasVona ? 100 : 0,
                                })
                                .bindPopup('Loading ...',{
                                    closeButton: true,
                                    maxHeight: maxHeight
                                })
                                .on('click ', function(e) {
                                    markerFunction(ga_code);
                                });

                switch (setStatus) {
                    case 1:
                        GunungapiNormal.push(markerId);
                        break;
                    case 2:
                        GunungapiWaspada.push(markerId);
                        break;
                    case 3:
                        GunungapiSiaga.push(markerId);
                        break;
                    default:
                        GunungapiAwas.push(markerId);
                        break;
                }
                
                if (!(L.Browser.mobile)) {
                    markerId.bindTooltip(setTitle+' - '+status);
                };

                markers[ga_code] = markerId;

            });

            layerNormal = L.layerGroup(GunungapiNormal).addTo(map);
            layerWaspada = L.layerGroup(GunungapiWaspada).addTo(map);
            layerSiaga = L.layerGroup(GunungapiSiaga).addTo(map);
            layerAwas = L.layerGroup(GunungapiAwas).addTo(map);

            $('#gunung_api').change(function () {
                markerFunction(this.value);
            });

            $("#selectStandardBasemap").on("change", function (e) {
                setBasemap($(this).val());
            });

            $(document).on('click', '#load_krb', function() {
                var $button = $(this);
                var layerKrb = L.esri.featureLayer({
                        url: "https://services9.arcgis.com/BvrmTdn7GU5knQXz/arcgis/rest/services/KRB_GA_ID/FeatureServer/0",
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

                var mapKrb = layerKrb.setWhere($(this).val())
                    .on('loading', function() {
                        $button.button('loading');
                    }).on('load', function() {
                        $button.button('reset');
                    });

                addKrb.push(mapKrb);
                console.log(addKrb);
                L.layerGroup(addKrb).addTo(map);
                // map.addLayer(mapKrb);
                
            });

            function markerFunction(ga_code) {
                var updateWidth = markers[ga_code]._popup.options;
                    updateWidth.maxWidth = maxPopUpWidth();
                    updateWidth.minWidth = maxPopUpWidth();
                var popup = markers[ga_code].getPopup(),
                    gempa = '';

                var newData;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    global: true,
                    url: '{{ route('v1.json.var.show') }}',
                    type: 'POST',
                    data: {ga_code:ga_code},
                    success: function(response) {
                        newData = response.data;
                        if (response.success) {
                            console.log(newData);
                        }

                        switch (newData.gunungapi.status) {
                            case 1:
                                var bg = 'text-green',
                                    status = 'Level I (Normal)';
                                break;
                            case 2:
                                var bg = 'text-yellow',
                                status = 'Level II (Waspada)';
                                break;
                            case 3:
                                var bg = 'text-orange',
                                status = 'Level III (Siaga)';
                                break;
                            default:
                                var bg = 'text-red',
                                status = 'Level IV (Awas)';
                        };

                        $.each(newData.gempa.deskripsi, function(index, value) {
                            gempa = gempa+'<p>'+ value +'</p>'
                        });

                        var setPopUpContent = '<div class="panel panel-default bg-black no-border"><div class="panel-heading bg-black text-bold ' + bg + '"><h4>' + newData.gunungapi.nama + '</h4></div><div class="panel-body"><img src="' + newData.visual.foto + '" class="img-thumbnail" alt="' + newData.gunungapi.nama + '"></div><ul class="list-group"><li class="list-group-item bg-black"><button type="button" id="load_krb" value="MAG_CODE=\''+ga_code+'\'" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">Peta KRB</button></li><li class="list-group-item bg-black"><h5><b>Lokasi Administratif dan Geografis :</b></h5> ' +newData.gunungapi.deskripsi+ '</li><li class="list-group-item bg-black"><h5><b>Periode Pengamatan :</b></h5> ' + newData.laporan.tanggal + '</li><li class="list-group-item bg-black"><h5><b>Klimatologi :</b></h5>' + newData.klimatologi.deskripsi+'</li><li class="list-group-item bg-black"><h5><b>Pengamatan Visual :</b></h5>' + newData.visual.deskripsi+'<h5><b>Visual Lainnya :</b></h5>' + newData.visual.lainnya + '</li><li class="list-group-item bg-black"><h5><b>Pengamatan Kegempaan :</b></h5>' + gempa +'<img src="' + newData.gempa.grafik +'" class="img-thumbnail" alt="' + newData.gunungapi.nama + '"></li><li class="list-group-item bg-black"><h5><b>Kesimpulan :</b></h5> Tingkat Aktivitas Gunungapi '+ newData.gunungapi.nama +' <b class="' + bg + '">'+status+'</b></li><li class="list-group-item bg-black"><h5><b>Rekomendasi :</b></h5>'+ newData.rekomendasi+'</li><li class="list-group-item bg-black"><h5><b>Pembuat Laporan :</b></h5>' + newData.laporan.pembuat + '</li></ul></div>';

                        popup.setContent(setPopUpContent);
                        popup.update();
                        markers[ga_code].openPopup();
                        map.flyTo(newData.gunungapi.koordinat, 12);

                        $('.panel-default').slimScroll({
                            height: maxHeight,
                            railVisible: false,
                            size: '10px',
                            alwaysVisible: false
                        });  
                    }
                });
            }

            function maxPopUpWidth() {
                var width = $(window).width(),
                    maxWidth = 0.25 * width;
                if (width <= 767) {
                    return 320;
                }
                return maxWidth;
            }

            function setBasemap(basemap) {
                console.log(basemap);
                if (layerEsriStreets) {
                    map.removeLayer(layerEsriStreets);
                }

                if (basemap === 'OpenStreetMap') {
                    layerEsriStreets = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
                }
                else {
                    layerEsriStreets = L.esri.basemapLayer(basemap);
                }

                map.addLayer(layerEsriStreets);
                if (layerLabels) {
                    map.removeLayer(layerLabels);
                }

                if (basemap === 'ShadedRelief' || basemap === 'Oceans' || basemap === 'Gray' || basemap === 'DarkGray' || basemap === 'Imagery' || basemap === 'Terrain') {
                    layerLabels = L.esri.basemapLayer(basemap + 'Labels');
                    map.addLayer(layerLabels);
                }
                    
                if (basemap === 'Imagery') {
                        worldTransportation.addTo(map);            
                    } else if (map.hasLayer(worldTransportation)) {
                        map.removeLayer(worldTransportation);
                    }
            }

            // =============
            // Gerakan Tanah
            // =============

            var markersGerakanTanah = @json($gertans),
                GerakanTanah = [],
                layerGertan,
                markers_gertan = {};

            // Set Marker Gerakan Tanah
            $.each(markersGerakanTanah, function(index, gertan) {
                var markerGertan = gertan.crs_ids,
                    setTooltips = 'Gerakan Tanah - '+gertan.crs_prv+', '+gertan.crs_cty,
                    setTitle = 'Gerakan Tanah '+gertan.crs_dtm+' '+gertan.crs_zon,
                    setLongitude = gertan.crs_lon,
                    setLatitude = gertan.crs_lat;

                if (gertan.tanggapan.rekomendasi) {
                    icongertan = icon_gertan_t;
                    if (index == 0) {
                        icongertan = icon_gertan_t_b;
                    }
                } else {
                    icongertan = icon_gertan;
                    if (index == 0) {
                        icongertan = icon_gertan_b;
                    }
                }

                var markerId = L.marker([setLatitude, setLongitude], {
                                    icon: icongertan,
                                    title: setTitle,
                                    zIndexOffset: index == 0 ? 100 : 0,
                                })
                                .bindPopup('Loading ...',{
                                    closeButton: true,
                                    maxHeight: maxHeight
                                })
                                .on('click ', function(e) {
                                    markerGertanFunction(markerGertan);
                                });

                GerakanTanah.push(markerId);

                if (!(L.Browser.mobile)) {
                    markerId.bindTooltip(setTooltips);
                };

                markers_gertan[markerGertan] = markerId;
            });

            layerGertan = L.layerGroup(GerakanTanah).addTo(map);

            function markerGertanFunction(markerGertan) {
                var updateWidth = markers_gertan[markerGertan]._popup.options;
                    updateWidth.maxWidth = maxPopUpWidth();
                    updateWidth.minWidth = maxPopUpWidth();
                var popup = markers_gertan[markerGertan].getPopup();
                    
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    global: true,
                    url: '{{ route('v1.json.sigertan.show') }}',
                    type: 'POST',
                    data: {id:markerGertan},
                    success: function(response) {
                        $gertan = response.data;
                        var setTitle = $gertan.laporan.judul,
                            setPeta = $gertan.laporan.peta,
                            setUpdatedAt = $gertan.laporan.updated_at,
                            setDeskripsi = $gertan.laporan.deskripsi,
                            setTipeGertan = $gertan.tanggapan.tipe,
                            setDampak = $gertan.tanggapan.dampak,
                            setMorfologi = $gertan.tanggapan.kondisi.morfologi,
                            setGeologi = $gertan.tanggapan.kondisi.geologi,
                            setKeairan = $gertan.tanggapan.kondisi.keairan,
                            setLahan = $gertan.tanggapan.kondisi.tata_guna_lahan,
                            setKerentanan = $gertan.tanggapan.kondisi.kerentanan,
                            setPenyebab = $gertan.tanggapan.kondisi.penyebab,
                            setRekomendasi = $gertan.rekomendasi;

                        var setPopUpContent = '<div class="panel panel-default bg-black no-border"><div class="panel-heading bg-black text-bold text-white"><h4>' + setTitle + '</h4></div><div class="panel-body"><img src="' + setPeta + '" class="img-thumbnail" alt="' + setTitle + '"><small>'+setUpdatedAt+'</small></div><ul class="list-group"><li class="list-group-item bg-black"><h5><b>Lokasi dan Waktu Kejadian :</b></h5> ' + setDeskripsi + '</li><li class="list-group-item bg-black"><h5><b>Tipe Gerakan Tanah :</b></h5> ' + setTipeGertan + '</li><li class="list-group-item bg-black"><h5><b>Dampak Gerakan Tanah :</b></h5> ' + setDampak + '</li><li class="list-group-item bg-black"><h5><b>Kondisi Daerah Bencana :</b></h5><p><b>Morfologi :</b> ' + setMorfologi + '</p><p><b>Geologi :</b> ' + setGeologi + '</p><p><b>Keairan :</b> ' + setKeairan + '</p><p><b>Tata Guna Lahan :</b> ' + setLahan + '</p><p><b>Kerentanan Gerakan Tanah :</b> ' + setKerentanan + '</p></li><li class="list-group-item bg-black"><h5><b>Faktor Penyebab Gerakan Tanah :</b></h5> ' + setPenyebab + '</li><li class="list-group-item bg-black"><h5><b>Rekomendasi :</b></h5> ' + setRekomendasi + '</li></ul></div>';

                        popup.setContent(setPopUpContent);
                        popup.update();
                        markers_gertan[markerGertan].openPopup();
                        // map.flyTo(newData.gunungapi.koordinat, 12);

                        $('.panel-default').slimScroll({
                            height: maxHeight,
                            railVisible: false,
                            size: '10px',
                            alwaysVisible: false
                        });  
                    },
                    error: function(error) {
                        console.log(markerGertan);
                        console.log(error);
                    }
                });
            };

            // ==========
            // Gempa Bumi
            // ==========

            var markersGempa = @json($gempas),
                GempaBumi = [],
                layerGempa,
                markers_gempa = {};

            $.each(markersGempa, function(index, gempa) {
                var markerGempa = gempa.no,
                    setTanggapan = gempa.roq_tanggapan,
                    setTitle = 'Gempa Bumi '+gempa.koter,
                    setMagnitudo = gempa.magnitude,
                    setTooltips = setTitle+', '+gempa.magnitude+'SR',
                    setLongitude = gempa.lon_lima,
                    setLatitude = gempa.lat_lima,
                    setWilayah = gempa.area,
                    setGunungapi = gempa.nearest_volcano,
                    setIntensitas = gempa.mmi;

                if (setTanggapan == 'YA') {
                    icongempa = icon_gempa_t;
                    if (index == 0) {
                        icongempa = icon_gempa_t_b;
                    }
                } else {
                    icongempa = icon_gempa;
                    if (index == 0) {
                        icongempa = icon_gempa_b;
                    }
                }

                var markerId = L.marker([setLatitude, setLongitude], {
                                    icon: icongempa,
                                    title: setTitle,
                                    zIndexOffset: index == 0 ? 100 : 0,
                                })
                                .bindPopup('Loading ...',{
                                    closeButton: true,
                                    maxHeight: maxHeight
                                })
                                .on('click ', function(e) {
                                    markerGempaFunction(markerGempa);
                                });

                GempaBumi.push(markerId);

                if (!(L.Browser.mobile)) {
                    markerId.bindTooltip(setTooltips);
                };

                markers_gempa[markerGempa] = markerId;
            });

            layerGempa = L.layerGroup(GempaBumi).addTo(map);

            function markerGempaFunction(markerGempa) {
                var updateWidth = markers_gempa[markerGempa]._popup.options;
                    updateWidth.maxWidth = maxPopUpWidth();
                    updateWidth.minWidth = maxPopUpWidth();
                var popup = markers_gempa[markerGempa].getPopup();
                    
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    global: true,
                    url: '{{ route('v1.json.gempa.show') }}',
                    type: 'POST',
                    data: {id:markerGempa},
                    success: function(response) {
                        $gempa = response.data;
                        var setTitle = $gempa.laporan.title,
                            setMagnitudo = $gempa.laporan.magnitude,
                            setPelapor = $gempa.laporan.pelapor,
                            setWaktu = $gempa.laporan.waktu,
                            setKoter = $gempa.laporan.kota_terdekat,
                            setKoordinat = $gempa.laporan.latitude+' '+$gempa.laporan.longitude+', '+$gempa.laporan.kedalaman,
                            setGunung = $gempa.laporan.gunung_terdekat,
                            setPeta = $gempa.laporan.map ? '<img src="'+$gempa.laporan.map +'" class="img-thumbnail" alt="'+ setTitle +'"><small>'+setWaktu+'</small>' : '<p>Belum ada data.</p>' ,
                            setSumber = $gempa.laporan.sumber,
                            setIntensitas = $gempa.laporan.intensitas,
                            setTsunami = $gempa.tanggapan.tsunami,
                            setDeskripsi = $gempa.tanggapan.pendahuluan,
                            setKondisi = $gempa.tanggapan.kondisi,
                            setMekanisme = $gempa.tanggapan.mekanisme,
                            setDampak = $gempa.tanggapan.efek,
                            setRekomendasi = $gempa.rekomendasi;

                        if($gempa.laporan.has_tanggapan) {
                            var setTanggapan = '<li class="list-group-item bg-black"><h5><b>Deskripsi :</b></h5> ' + setDeskripsi + '</li><li class="list-group-item bg-black"><h5><b>Kondisi Wilayah :</b></h5> ' + setKondisi + '</li><li class="list-group-item bg-black"><h5><b>Mekanisme :</b></h5> ' + setMekanisme + '</li><li class="list-group-item bg-black"><h5><b>Dampak :</b></h5> ' + setDampak + '</li></li><li class="list-group-item bg-black"><h5><b>Rekomendasi :</b></h5> ' + setRekomendasi + '</li><li class="list-group-item bg-black"><h5><b>Pelapor :</b></h5> ' + setPelapor + '</li>';
                        } else {
                            var setTanggapan = '<li class="list-group-item bg-black"><h5><b>Tanggapan :</b></h5>Belum ada tanggapan.</li>';
                        }
                        
                        var setPopUpContent = '<div class="panel panel-default bg-black no-border"><div class="panel-heading bg-black text-bold text-white"><h4><b>' + setTitle + '</b></h4></div><div class="panel-body"><h5><b>Peta :</b>'+setPeta+'</div><ul class="list-group"><li class="list-group-item bg-black"><h5><b>Magnitudo :</b></h5> ' + setMagnitudo + '</li><li class="list-group-item bg-black"><h5><b>Waktu Kejadian :</b></h5> ' + setWaktu + '</li><li class="list-group-item bg-black"><h5><b>Koordinat dan Kedalaman :</b></h5> ' + setKoordinat + '</li><li class="list-group-item bg-black"><h5><b>Wilayah :</b></h5> ' + setTitle + '</li><li class="list-group-item bg-black"><h5><b>Intensitas gempa (Skala MMI): :</b></h5> ' + setIntensitas + '</li><li class="list-group-item bg-black"><h5><b>Gunung Api Terdekat :</b></h5> ' + setGunung + '</li>' + setTanggapan + '<li class="list-group-item bg-black"><h5><b>Sumber Data :</b></h5> ' + setSumber + '</li></ul></div>';

                        popup.setContent(setPopUpContent);
                        popup.update();
                        markers_gempa[markerGempa].openPopup();
                        // map.flyTo(newData.gunungapi.koordinat, 12);

                        $('.panel-default').slimScroll({
                            height: maxHeight,
                            railVisible: false,
                            size: '10px',
                            alwaysVisible: false
                        });  

                    },
                    error: function(error) {
                        console.log(markerGempa);
                        console.log(error);
                    }
                });
            };

            L.control.layers(null, {
                    'Gerakan Tanah': layerGertan,
                    'Gempa Bumi': layerGempa,
                    'Gunung Api - Level I (Normal)': layerNormal,
                    'Gunung Api - Level II (Waspada)': layerWaspada,
                    'Gunung Api - Level III (Siaga)': layerSiaga,
                    'Gunung Api - Level IV (Awas)': layerAwas,
            }, {
                position: 'bottomleft'
            }).addTo(map);
        });
        </script>
    </body>
</html>