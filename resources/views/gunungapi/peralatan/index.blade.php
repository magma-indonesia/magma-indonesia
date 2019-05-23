@extends('layouts.default')

@section('title')
    Gunung Api | Peralatan Monitoring
@endsection

@section('add-vendor-css')
    <link rel`"stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('css/leaflet.defaultextent.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Leaflet.Coordinates-0.1.5.css') }}">
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
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
                        </div>
                        <hr>
                        <div class="table-responsive">
                            {{-- {{ $alats->links() }} --}}
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gunung Api</th>
                                        <th>Nama Stasiun</th>
                                        <th>Kode Stasiun</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($alats as $key => $alat)
                                   <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $alat->gunungapi->name }}</td>
                                        <td>{{ $alat->nama_alat }}</td>
                                        <td>{{ $alat->kode_alat ?? '-' }}</td>
                                        <td>{{ $alat->jenis->jenis_alat }}</td>
                                        <td>{{ $alat->status ? 'Aktif' : 'Tidak Aktif' }}</td>
                                   </tr>
                                   @endforeach 
                                </tbody>
                            </table>
                            {{-- {{ $alats->links() }} --}}
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
@endsection

@section('add-script')
<script>
    var url = '{{ url('/') }}';
    var bounds = new L.LatLngBounds(new L.LatLng(-10.41, 93.41), new L.LatLng(7.3069694978258, 141.65));

    var ga_icon = L.Icon.extend({options: {iconSize: [32, 32]}});
    
    here = new ga_icon({iconUrl: url+'/icon/1.png'});
    
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
    var markersAlat = @json($alats),
        layerAlat = [],
        layerGunungApi = [];

    $.each(markersAlat, function (index, alat) {
        var nama_gapi = alat.gunungapi.name,
            nama_alat = alat.nama_alat,
            latitude = alat.latitude,
            longitude = alat.longitude,
            status = alat.status;

        var ga_latitude = alat.gunungapi.latitude,
            ga_longitude = alat.gunungapi.longitude;

        var markerId = L.marker([latitude, longitude], {
            title: nama_alat
        }).bindPopup(nama_gapi+' - '+nama_alat, {
            closeButton: true,
        });

        var markerGa = L.marker([ga_latitude, ga_longitude], {
            icon: here,
            title: nama_gapi
        }).bindPopup(nama_gapi, {
            closeButton: true,
        });

        layerAlat.push(markerId);
        layerGunungApi.push(markerGa);

        if (!(L.Browser.mobile)) {
            markerId.bindTooltip(nama_gapi+' - '+nama_alat);
        };

    });

    gunungapi = L.layerGroup(layerGunungApi).addTo(map);
    alat = L.layerGroup(layerAlat).addTo(map);

});
</script>
@endsection