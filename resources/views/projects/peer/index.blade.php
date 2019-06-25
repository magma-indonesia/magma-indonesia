@extends('layouts.slim') 

@section('title')
{{ config('app.name') }} - {{ config('app.tag_line') }}
@endsection

@section('add-vendor-css')
<!-- Load Leaflet CSS and JS from CDN-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
@endsection

@section('page-title')
PEER DEMO
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card card-blog bd-0">
            <div id="map" class="ht-250 ht-sm-300 ht-md-600 bd-0"></div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
<!-- Load Esri Leaflet from CDN -->
<script src="https://unpkg.com/esri-leaflet@2.0.8"></script>
<script src="https://unpkg.com/esri-leaflet-renderers@2.0.6/dist/esri-leaflet-renderers.js"
integrity="sha512-mhpdD3igvv7A/84hueuHzV0NIKFHmp2IvWnY5tIdtAHkHF36yySdstEVI11JZCmSY4TCvOkgEoW+zcV/rUfo0A=="
crossorigin=""></script>
<script src="https://unpkg.com/geotiff@0.4.1/dist/geotiff.browserify.js "></script>
<script src="https://unpkg.com/plotty@0.2.0/src/plotty.js"></script>
<script src="https://stuartmatthews.github.io/leaflet-geotiff/leaflet-geotiff.js"></script>
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
        var map = L.map('map').setView([-33, 147],4);
        var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
        var marker;

        var windSpeed = L.leafletGeotiff('https://stuartmatthews.github.io/leaflet-geotiff/tif/wind_speed.tif',{
            band: 0,
            displayMin: 0,
            displayMax: 30,
            name: 'Wind speed',
            // colorScale: 'rainbow',
            clampLow: false,
            clampHigh: true,
            //vector:true,
            arrowSize: 20,
        }).addTo(map);

        var windDirection = L.leafletGeotiff(
            url='https://stuartmatthews.github.io/leaflet-geotiff/tif/wind_direction.tif',
            options={band: 0,
                displayMin: 0,
                displayMax: 360,
                name: 'Wind direction',
                colorScale: 'rainbow',
                //clampLow: false,
                //clampHigh: true,
                vector:true,
                arrowSize: 20,
            }
        ).addTo(map);

        map.on('click', function(e) {
            popup = L.popup()
                    .setLatLng([e.latlng.lat,e.latlng.lng])
                    .setContent('Koordinat  = '+e.latlng.lat.toFixed(3)+', '+e.latlng.lng.toFixed(3)+'<br>Wind Speed = '+windSpeed.getValueAtLatLng(e.latlng.lat,e.latlng.lng).toFixed(1)+' knot<br>Wind Direction = '+windDirection.getValueAtLatLng(e.latlng.lat,e.latlng.lng).toFixed(0)+' degree')
                    .openOn(map);
            // if (!marker) {
            //     marker = L.marker([e.latlng.lat,e.latlng.lng]).addTo(map);

            // } else {
            //     marker.setLatLng([e.latlng.lat,e.latlng.lng]);
            // }
        });
    });
</script>
@endsection