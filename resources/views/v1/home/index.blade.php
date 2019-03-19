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
                    <li><a role="button" data-target="#panelInfo" aria-haspopup="true"><span class="glyphicon glyphicon-tasks"></span> Legenda</a></li>
                    <li><a class="visible-xs" role="button" data-target="#panelSearch" aria-haspopup="true"><span class="glyphicon glyphicon-search"></span> Search</a></li>
                    <li><a role="button" data-target="#panelVolcanoes" aria-haspopup="true"><span class="glyphicon glyphicon-th-list"></span> Gunungapi</a></li>
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
                <span class="calcite-title-sub hidden-xs"><a href="#">Gunung Api</a></span>
                <span class="calcite-title-divider hidden-xs"></span>
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
                        <a class="panel-toggle" role="button" data-toggle="collapse" href="#collapseInfo" aria-expanded="true" aria-controls="collapseInfo"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span class="panel-label">Info</span></a> 
                        <a class="panel-close" role="button" data-toggle="collapse" data-target="#panelInfo"><span class="esri-icon esri-icon-close" aria-hidden="true"></span></a>  
                    </div>
                </div>
                <div id="collapseInfo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingInfo">
                    <div class="panel-body">            
                        <p>The main styles used in this app are:</p>
                        Body
                        <li>calcite-nav-bottom</li>
                        <li>calcite-layout-large-title</li>
                        <br>
                        Nav
                        <li>calcite-bgcolor-dark-green</li>
                        <li>calcite-text-light</li>
                        <br>
                        Panels
                        <li>calcite-panels-left</li>
                        <li>calcite-bgcolor-dark-green</li>
                        <li>calcite-bg-custom</li>
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
                            <option value="NAMOBJ='AGUNG'">Agung TEST</option>
                            @foreach ($gadds as $key => $gadd)
                            <option value="{{ $gadd->ga_code }}">{{ $gadd->ga_nama_gapi }}</option>
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
                            <option selected value="Imagery">Satellite</option>
                            <option value="NationalGeographic">National Geographic</option>
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
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Sumber Data</a>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Penghargaan</a>
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
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Close</a>
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

        <script>

            var url = '{{ url('/') }}'; 

            // Icon Gunung Api
            var ga_icon = L.Icon.extend({
                    options: {
                        iconSize: [32, 32]
                    }
                });

            var here = new ga_icon({iconUrl: url+'/icon/here.png'}),
            ga_normal = new ga_icon({iconUrl: url+'/icon/1.png'}),
            ga_waspada = new ga_icon({iconUrl: url+'/icon/2.png'}),
            ga_siaga = new ga_icon({iconUrl: url+'/icon/3.png'}),
            ga_awas = new ga_icon({iconUrl: url+'/icon/4.png'});

            // Batas Map Indonesia
            var bounds = new L.LatLngBounds(new L.LatLng(-14.349547837185362, 88.98925781250001), new L.LatLng(14.3069694978258, 149.01855468750003));

            //Zoom changer
            function zoomResponsive() {
                var width = screen.width;
                    if (width <= 767) {
                        return 10;
                    }
                    return 3;
            }
            
            // Map Inititiation
            var map = L.map('map', {
                        zoomControl: false,
                        center: [0, 116.1475],
                        zoom: zoomResponsive(),
                        attributionControl:false
                    }).setMinZoom(5)
                    .setMaxZoom(11);

            // Add Layers
            var layerEsriStreets = L.esri.basemapLayer('Imagery').addTo(map);
            var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);
            var layerKrb = L.esri.featureLayer({
                        url: "https://services9.arcgis.com/BvrmTdn7GU5knQXz/arcgis/rest/services/Kawasan_Rawan_Bencana_Gunungapi/FeatureServer/0",
                    });
            var layerLabels = null

            layerKrb.bindPopup(function(layer) {
                console.log(layer);
                return L.Util.template('<h3>LCODE nya {LCODE}</h3><hr /><p>Remarknya {REMARK}</p>', layer.feature.properties);
            });

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

            L.control.defaultExtent().addTo(map);
            L.control.scale().addTo(map);
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
        <script>
        $(document).ready(function () {

            var gunung_api = $('#gunung_api'),
                markersGunungApi = @json($gadds),
                maxHeight = 0.7 * ($(window).height()),
                markers = {};

            gunung_api.on('change', function() {
                layerKrb.setWhere($(this).val()).addTo(map);
            });

            // Set Marker Gunung Api
            $.each(markersGunungApi, function(index, gunungapi) {
                var ga_code = gunungapi.ga_code,
                    setTitle = gunungapi.ga_nama_gapi,
                    setLongitude = gunungapi.ga_lon_gapi,
                    setLatitude = gunungapi.ga_lat_gapi,
                    setStatus = gunungapi.ga_status;

                switch (setStatus) {
                    case 1:
                        var gaicon = ga_normal,
                            status ='Level I (Normal)';
                        break;
                    case 2:
                        var gaicon = ga_waspada,
                            status ='Level II (Waspada)';
                        break;
                    case 3:
                        var gaicon = ga_siaga,
                            status ='Level III (Siaga)';
                        break;
                    default:
                        var gaicon = ga_awas,
                        status ='Level IV (Awas)';
                };

                var markerId = L.marker([gunungapi.ga_lat_gapi, gunungapi.ga_lon_gapi], {
                                    icon: gaicon,
                                    title: setTitle
                                })
                                .addTo(map)
                                .bindPopup('Loading',{
                                    closeButton: true,
                                    maxHeight: maxHeight
                                })
                                .on('click ', function(e) {
                                    markerFunction(ga_code);
                                });

                if (!(L.Browser.mobile)) {
                    markerId.bindTooltip(setTitle+' - '+status);
                };

                markers[ga_code] = markerId;

            });

            function markerFunction(ga_code) {
                var updateWidth = markers[ga_code]._popup.options;
                    updateWidth.maxWidth = maxPopUpWidth();
                    updateWidth.minWidth = maxPopUpWidth();
                var popup = markers[ga_code].getPopup(),
                    gempa = '';

                var newData;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    global: true,
                    url: '{{ route('v1.json.var.show') }}',
                    type: 'POST',
                    data: {ga_code:ga_code},
                    success: function(response) {
                        newData = response;
                        if (response.success) {
                            console.log(newData);
                        }

                        switch (newData.data.status) {
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

                        $.each(newData.data.gempa, function(index, value) {
                            gempa = gempa+'<p>'+ value +'</p>'
                        });

                        var setPopUpContent = '<div class="panel panel-default bg-black no-border"><div class="panel-heading bg-black text-bold ' + bg + '"><h4>' + newData.data.nama + '</h4></div><div class="panel-body"><img src="' + newData.data.image + '" class="img-thumbnail" alt="' + newData.data.nama + '"></div><ul class="list-group"><li class="list-group-item bg-black"><button type="button" id="load_krb" value="' + ga_code + '" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">Peta KRB</button></li><li class="list-group-item bg-black"><h5><b>Lokasi Administratif dan Geografis :</b></h5> ' +newData.data.gunungapi+ '</li><li class="list-group-item bg-black"><h5><b>Periode Pengamatan :</b></h5> ' + newData.data.laporan + '</li><li class="list-group-item bg-black"><h5><b>Pengamatan Visual :</b></h5>' + newData.data.visual+'<h5><b>Visual Lainnya :</b></h5>' + newData.data.visual_lainnya + '</li><li class="list-group-item bg-black"><h5><b>Pengamatan Kegempaan :</b></h5>' + gempa +'<img src="' + newData.data.grafik_gempa +'" class="img-thumbnail" alt="' + newData.data.nama + '"></li><li class="list-group-item bg-black"><h5><b>Kesimpulan :</b></h5> Tingkat Aktivitas Gunungapi '+ newData.data.nama +' <b class="' + bg + '">'+status+'</b></li><li class="list-group-item bg-black"><h5><b>Rekomendasi :</b></h5>'+ newData.data.rekomendasi+'</li><li class="list-group-item bg-black"><h5><b>Pembuat Laporan :</b></h5>' + newData.data.pembuat_laporan + '</li></ul></div>';

                        popup.setContent(setPopUpContent);
                        popup.update();
                        markers[ga_code].openPopup();

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

            $('#gunung_api').change(function () {
                markerFunction(this.value);
            });

            $("#selectStandardBasemap").on("change", function (e) {
                setBasemap($(this).val());
            });
        });
        </script>
    </body>
</html>