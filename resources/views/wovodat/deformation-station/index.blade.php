@extends('layouts.default')

@section('title')
    WOVOdat | Deformation Stations
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Leaflet.Coordinates-0.1.5.css') }}">
@endsection

@section('content-body')
    <div class="content content-boxed animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md m-b-md">
                <h2>
                    Deformation Stations
                </h2>
                <p>
                    This table stores information such as a location, name, and description for stations where deformation or geodetic data are collected.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Deformation Stations Map
                    </div>
                    <div class="panel-body">
                        <section id="map-section">
                            <div id="map" style="height: 480px;"></div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">

                    <div class="panel-heading">
                        Deformation Stations
                    </div>

                    <div class="panel-body float-e-margins">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a href="{{ route('chambers.wovodat.common-network.deformation-station.tilt.index') }}" class="btn btn-outline btn-danger btn-block" type="button">Tilt Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-deformations" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano</th>
                                        <th>Station Code</th>
                                        <th>Station Name</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Elevation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 1;
                                    @endphp
                                    @foreach ($volcanoes as $key => $volcano)
                                        @foreach ($volcano->deformation_stations as $key1 => $station)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $volcano->vd_name }}</td>
                                            <td>{{ $station->ds_code }}</td>
                                            <td>{{ $station->ds_name }}</td>
                                            <td>{{ $station->ds_nlat }}</td>
                                            <td>{{ $station->ds_nlon }}</td>
                                            <td>{{ $station->ds_nelev ?: '-' }}</td>
                                        </tr>
                                        @php
                                            $index = $index+1;
                                        @endphp
                                        @endforeach
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
    <!-- DataTables -->
    <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src="https://unpkg.com/esri-leaflet@2.0.8"></script>
    <script src="https://unpkg.com/esri-leaflet-renderers@2.0.6/dist/esri-leaflet-renderers.js"
    integrity="sha512-mhpdD3igvv7A/84hueuHzV0NIKFHmp2IvWnY5tIdtAHkHF36yySdstEVI11JZCmSY4TCvOkgEoW+zcV/rUfo0A=="
    crossorigin=""></script>
    <script src="{{ asset('js/Leaflet.Coordinates-0.1.5.min.js') }}"></script>
    <script src="{{ asset('js/leaflet.defaultextent.js') }}"></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
@endsection

@section('add-script')
<script>
    var url = '{{ url('/') }}';
    var icon = L.Icon.extend({options: {iconSize: [32, 32]}});
    volcano_icon = new icon({iconUrl: url+'/icon/volcano.png'});

    function zoomResponsive() {
        var width = screen.width;
            if (width <= 767) {
                return 7;
            }
            return 5;
    }

    var map = L.map('map', {
                fullscreenControl: true,
                zoomControl: false,
                center: [-1.18058, 115.66406],
                zoom: zoomResponsive(),
                attributionControl:false,
            }).setMinZoom(5);

    var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
    var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);

    var latlongControl = L.control.coordinates({
        position:'bottomright',
        decimals:5,
        decimalSeperator:'.',
        labelTemplateLat:'Lat: {y}',
        labelTemplateLng:'Lon: {x}',
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

    function plot_stations(stations, volcano_name)
    {
        $.each(stations, function (index, station) {
            var station_code = station.ds_code,
                station_name = station.ds_name,
                station_lat = station.ds_nlat,
                station_lon = station.ds_nlon,
                station_elevation = station.ds_nelev;

            var station_marker = L.marker([station_lat, station_lon], {
                title: station_name
            }).bindPopup(volcano_name+' - '+station_name+' ('+station_code+')', {
                closeButton: true,
            });

            station_marker.addTo(map);
        });
    }

    $(document).ready(function () {

        $('#table-deformations').dataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [[30, 50, -1], [30, 50, "All"]],
            buttons: [
                { extend: 'copy', className: 'btn-sm'},
                { extend: 'csv', title: 'Deformation Table', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]} },
                { extend: 'pdf', title: 'Deformation Table', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]} },
                { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]} }
            ]
        });

        var volcanoes = @json($volcanoes);

        $.each(volcanoes, function (index, volcano) {
            var volcano_name = volcano.vd_name,
                volcano_lat = volcano.information.vd_inf_slat,
                volcano_lon = volcano.information.vd_inf_slon,
                stations = volcano.deformation_stations;

            var volcano_marker = L.marker([volcano_lat, volcano_lon], {
                icon: volcano_icon,
                title: volcano_name
            }).bindPopup(volcano_name, {
                closeButton: true,
            });

            volcano_marker.addTo(map);

            plot_stations(stations, volcano_name);

        });

    });
</script>
@endsection