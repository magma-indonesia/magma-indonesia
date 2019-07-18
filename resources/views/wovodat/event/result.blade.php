@extends('layouts.default')

@section('title')
    WOVOdat | Seismic Events
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Leaflet.Coordinates-0.1.5.css') }}">
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
@endsection

@section('content-header')
<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>WOVOdat</span>
                        </li>
                        <li class="active">
                            <span>Seismic Events</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Generate Seismic Event from specific seismic network
                </h2>
                <small>Base on WOVOdat database</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            @role('Super Admin')
            <div class="col-lg-12">
            @component('components.json-var')
                @slot('title')
                    For Developer
                @endslot
            @endcomponent
            </div>
            @endrole
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Events Plotting
                    </div>
                    <div class="panel-body">
                        <section id="map-section">
                            <div id="map" style="height: 480px;"></div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Event Data - ({{ count($network->events) }} Events)
                    </div>
                    <div class="panel-body">
                        <div class="table-repsonsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Event Time</th>
                                        <th>Duration (s)</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Kedalaman</th>
                                        <th>Magnitude</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($network->events as $key => $event)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $event->sd_evn_time }}</td>
                                        <td>{{ $event->sd_evn_dur }}</td>
                                        <td>{{ $event->sd_evn_elat }}</td>
                                        <td>{{ $event->sd_evn_elon }}</td>
                                        <td>{{ $event->sd_evn_edep }}</td>
                                        <td>{{ $event->sd_evn_pmag }}</td>
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

@section('add-vendor-script')
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src="https://unpkg.com/esri-leaflet@2.0.8"></script>
    <script src="https://unpkg.com/esri-leaflet-renderers@2.0.6/dist/esri-leaflet-renderers.js"
    integrity="sha512-mhpdD3igvv7A/84hueuHzV0NIKFHmp2IvWnY5tIdtAHkHF36yySdstEVI11JZCmSY4TCvOkgEoW+zcV/rUfo0A=="
    crossorigin=""></script>
    <script src="{{ asset('js/Leaflet.Coordinates-0.1.5.min.js') }}"></script>
    <script src="{{ asset('js/leaflet.defaultextent.js') }}"></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
@endsection

@section('add-script')
<script>
    var url = '{{ url('/') }}';
    var volcano_icon = L.Icon.extend({options: {iconSize: [32, 32]}});
    volcano = new volcano_icon({iconUrl: url+'/icon/1.png'});

    function zoomResponsive() {
        var width = screen.width;
            if (width <= 767) {
                return 7;
            }
            return 10;
    }

    var map = L.map('map', {
                fullscreenControl: true,
                zoomControl: false,
                center: [{{ $network->volcano->information->vd_inf_slat }}, {{ $network->volcano->information->vd_inf_slon }}],
                zoom: zoomResponsive(),
                attributionControl:false,
            }).setMinZoom(5);

    var layerEsriStreets = L.esri.basemapLayer('Imagery').addTo(map);
    var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);

    var latlongControl = L.control.coordinates({
        position:"bottomright",
        decimals:5,
        decimalSeperator:".",
        labelTemplateLat:"Lat: {y}",
        labelTemplateLng:"Lon: {x}",
        enableUserInput:false,
        useDMS:false,
        useLatLngOrder: true,
        markerType: L.marker,
        markerProps: {}
    });

    if (!(L.Browser.mobile)){
        latlongControl.addTo(map);
    };
    L.control.defaultExtent().addTo(map);
    L.control.zoom({position:'bottomright',}).addTo(map);


    $(document).ready(function () {
        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($network), {collapsed: true});
        @endrole

        var data = @json($network),
            volc_name = data.volcano.vd_name,
            volc_lat = data.volcano.information.vd_inf_slat,
            volc_lon = data.volcano.information.vd_inf_slon,
            events = data.events,
            event_layer = [];

        $.each(events, function (index, event) {
            var event_time = event.sd_evn_time,
                event_lat = event.sd_evn_elat,
                event_lon = event.sd_evn_elon,
                event_depth = event.sd_evn_edep,
                event_duration = event.sd_evn_dur,
                event_mag = event.sd_evn_pmag;

            var event_marker = L.marker([event_lat, event_lon], {
                title: event_time,
            }).bindPopup(event_time+' - (Mag: '+event_depth+')', {
                closeButton: true,
            });

            event_layer.push(event_marker);
        });

        var volc_marker = L.marker([volc_lat, volc_lon], {
            icon: volcano,
            title: volc_name
        }).bindPopup(volc_name, {
            closeButton: true,
        });

        volc_marker.addTo(map);
        L.layerGroup(event_layer).addTo(map);
    });
</script>
@endsection