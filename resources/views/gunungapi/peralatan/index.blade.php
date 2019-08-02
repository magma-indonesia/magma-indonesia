@extends('layouts.default')

@section('title')
    Gunung Api | Peralatan Monitoring
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Leaflet.Coordinates-0.1.5.css') }}">
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
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
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Peralatan Monitoring</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Peralatan Monitoring Gunung Api
                </h2>
                <small>Meliputi peralatan visual, seismik, deformasi, dan geokimia</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">

            @role('Super Admin')
            <div class="col-md-12">
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
                        Data Peralatan 
                    </div>
                    <div class="panel-body no-padding">
                        <section id="map-section">
                            <div id="map" style="height: 600px"></div>
                        </section>
                    </div>
                    <div class="panel-body">
                        <div class="text-left">
                            <a href="{{ route('chambers.peralatan.create') }}" type="button" class="btn btn-magma btn-outline">Tambah Alat</a>
                            <a href="{{ route('chambers.peralatan.jenis.create') }}" type="button" class="btn btn-magma btn-outline">Tambah Jenis Alat</a>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            {{-- {{ $gadds->links() }} --}}
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gunung Api</th>
                                        <th>Nama Stasiun</th>
                                        <th>Kode Stasiun</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Elevasi</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $last_no = 0;
                                    @endphp
                                    @foreach ($gadds as $gadd)
                                    @php
                                        $no = $last_no;
                                    @endphp
                                        @foreach ($gadd->alat as $key => $alat)
                                        @php
                                            $last_no = $no+$key+1;
                                        @endphp
                                        <tr>
                                                <td>{{ $last_no }}</td>
                                                <td>{{ $gadd->name }}</td>
                                                <td>{{ $alat->nama_alat }}</td>
                                                <td>{{ $alat->kode_alat ?? '-' }}</td>
                                                <td>{{ $alat->latitude ?? '-' }}</td>
                                                <td>{{ $alat->longitude ?? '-' }}</td>
                                                <td>{{ $alat->elevasi ?? '-' }}</td>
                                                <td>{{ $alat->jenis->jenis_alat }}</td>
                                                <td>{{ $alat->status ? 'Aktif' : 'Tidak Aktif' }}</td>
                                        </tr>
                                        @endforeach
                                    @endforeach 
                                </tbody>
                            </table>
                            {{-- {{ $gadds->links() }} --}}
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
    var bounds = new L.LatLngBounds(new L.LatLng(-10.41, 93.41), new L.LatLng(7.3069694978258, 141.65));

    var ga_icon = L.Icon.extend({options: {iconSize: [42, 42]}});
    var iconSize = L.Icon.extend({options: {iconSize: [32, 42]}});
    var iconSize58 = L.Icon.extend({options: {iconSize: [48, 58]}});
    
    here = new ga_icon({iconUrl: url+'/icon/volcano.png'});
    seismic_on = new iconSize({iconUrl: url+'/icon/seismic_1.png'});
    seismic_off = new iconSize({iconUrl: url+'/icon/seismic_0.png'});
    tilt_on = new iconSize58({iconUrl: url+'/icon/tilt_1.png'});
    tilt_off = new iconSize58({iconUrl: url+'/icon/tilt_0.png'});
    gps_on = new iconSize({iconUrl: url+'/icon/gps_1.png'});
    gps_off = new iconSize({iconUrl: url+'/icon/gps_0.png'});
    
    function zoomResponsive() {
        var width = screen.width;
            if (width <= 767) {
                return 4;
            }
            return 5;
    }

    var map = L.map('map', {
                fullscreenControl: true,
                zoomControl: false,
                center: [-1.6000285, 120.015776],
                zoom: zoomResponsive(),
                attributionControl:false,
            }).setMinZoom(5).setMaxZoom(12).setMaxBounds(bounds);

    var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
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

</script>

<script>
$(document).ready(function () {

    @role('Super Admin')
    $('#json-renderer').jsonViewer(@json($gadds), {collapsed: true});
    @endrole
    
    var layerGunungApi = [],
        layerAlat = [],
        layerSeismik = [],
        layerDeformasi = [],
        layerCtd = [];

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '{{ URL::signedRoute('chambers.json.peralatan.index') }}',
        type: 'POST',
        success: function(data) {
            plotMap(data);
        },
    });

    function plotMap(data)
    {
        var markersGa = data;

        $.each(markersGa, function (index, gunungapi) {
            plotGunungApi(gunungapi);
            $.each(gunungapi.alat, function(index, alat) {
                plotAlat(alat, gunungapi);
            });
        });

        L.control.layers(null, {
                    'Peralatan': L.layerGroup(layerAlat).addTo(map),
                    'Gunung Api': L.layerGroup(layerGunungApi).addTo(map),
                    'Seismik' : L.layerGroup(layerSeismik).addTo(map),
                    'Deformasi' : L.layerGroup(layerDeformasi).addTo(map)
            }, {
                position: 'bottomleft'
            }).addTo(map);
    }

    function plotGunungApi(gunungapi)
    {
        var ga_latitude = gunungapi.latitude,
            ga_longitude = gunungapi.longitude,
            nama_gapi = gunungapi.name;

        var markerGa = L.marker([ga_latitude, ga_longitude], {
            icon: here,
            title: nama_gapi
        }).bindPopup(nama_gapi, {
            closeButton: true,
        });

        layerGunungApi.push(markerGa);
    }

    function plotAlat(alat, gunungapi)
    {
        var nama_gapi = gunungapi.name,
            status = alat.status == 1 ? true : false,
            status_deskripsi = alat.status == 1 ? 'Aktif' : 'Tidak Aktif';

        switch (alat.jenis_id) {
            case 1:
                icon_alat = alat.status == 1 ? seismic_on : seismic_off;
                plotSeismik(alat, icon_alat, nama_gapi);
                break;
            case 2:
                icon_alat = alat.status == 1 ? tilt_on : tilt_off;
                plotDeformasi(alat, icon_alat, nama_gapi);
                break;
            case 3:
                icon_alat = alat.status == 1 ? gps_on : gps_off;
                plotDeformasi(alat, icon_alat, nama_gapi);
                break;
            case 4:
                icon_alat = alat.status == 1 ? gps_on : gps_off;
                plotDeformasi(alat, icon_alat, nama_gapi);
                break;
            default:
                icon_alat = status ? seismic_on : seismic_off;
                break;
        }
    }

    function setMarkerId(alat,icon,nama_gapi)
    {
        var latitude = alat.latitude,
            longitude = alat.longitude,
            nama_alat = alat.nama_alat,
            jenis_alat = alat.jenis.jenis_alat,
            status_deskripsi = alat.status == 1 ? 'Aktif' : 'Tidak Aktif';

        var markerId = L.marker([latitude, longitude], {
            icon: icon,
            title: nama_gapi+' '+jenis_alat+', '+nama_alat
        }).bindPopup('['+nama_gapi+'] '+status_deskripsi+' - '+jenis_alat+', '+nama_alat, {
            closeButton: true,
        });

        if (!(L.Browser.mobile)) {
            markerId.bindTooltip('['+nama_gapi+'] '+status_deskripsi+' - '+jenis_alat+', '+nama_alat);
        };

        layerAlat.push(markerId);

        return markerId;
    }

    function plotSeismik(alat,icon,nama_gapi)
    {
        var markerId = setMarkerId(alat,icon,nama_gapi);
        layerSeismik.push(markerId);
    }

    function plotDeformasi(alat,icon,nama_gapi)
    {
        var markerId = setMarkerId(alat,icon,nama_gapi);
        layerDeformasi.push(markerId);
    }
});
</script>
@endsection