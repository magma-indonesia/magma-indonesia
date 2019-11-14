@extends('layouts.slim') 

@section('title') 
Laporan Tanggapan Gempa Bumi {{ $roq->laporan->title }}, {{ $roq->laporan->pelapor }}
@endsection

@section('add-vendor-css')
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
<li class="breadcrumb-item"><a href="#">Gempa Bumi</a></li>
<li class="breadcrumb-item active" aria-current="page">Tanggapan Kejadian</li>
@endsection
 
@section('page-title')
Tanggapan Kejadian
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-8">
        <div class="card mg-b-30">
            <div id="map" class="ht-250 ht-sm-300 ht-md-400 bd-0"></div>
            
            <div class="card-body pd-30">
                <h5 class="card-title tx-dark tx-medium mg-b-10">{{ $roq->laporan->title }}</h5>
                <p class="card-subtitle tx-normal mg-b-15">Tanggapan dibuat oleh <a href="#">{{ $roq->laporan->pelapor }}</a><span class="visible-md visible-lg">, {{ $roq->laporan->waktu->formatLocalized('%d %B %Y').' pukul '.$roq->laporan->waktu->format('H:i:s').' WIB' }}</span></p>
                <p class="card-text tx-16">
                    <span class="badge badge-warning pd-10 mg-t-10">{{ $roq->laporan->magnitude }}</span>
                    @if ($roq->laporan->intensitas AND ($roq->laporan->intensitas != '-belum ada keterangan-'))
                    <span class="badge badge-primary pd-10 mg-t-10">{{ $roq->laporan->intensitas }}</span>
                    @endif
                    <span class="badge badge-primary pd-10 mg-t-10">{{ $roq->tanggapan->tsunami }}</span>
                </p>
                <label class="slim-card-title">Lokasi dan Waktu Kejadian</label>
                <p class="card-text pd-r-30">{{ $roq->tanggapan->pendahuluan }}</p>
                <hr>
                <label class="slim-card-title">Kondisi Wilayah</label>
                <p class="card-text pd-r-30">{{ $roq->tanggapan->kondisi }}</p>
                <hr>
                <label class="slim-card-title">Mekanisme</label>
                <p class="card-text pd-r-30">{{ $roq->tanggapan->mekanisme }}</p>
                <hr>
                <label class="slim-card-title">Dampak</label>
                <p class="card-text pd-r-30">{{ $roq->tanggapan->efek }}</p>
                <hr>
                <label class="slim-card-title">Rekomendasi</label>
                <p class="card-text pd-r-30">{!! $roq->rekomendasi !!}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @include('includes.slim-sosmed')
    </div>

</div>
@endsection

@section('add-script')
<script>
    $(function() {

        var url = '{{ url('/') }}';
        var map = L.map('map', {
                        zoomControl: false,
                        center: @json($roq->laporan->loc),
                        zoom: 6,
                        attributionControl:false,
                    }).setMinZoom(5).setMaxZoom(10);

        var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
        var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);
        var ga_icon = L.Icon.extend({options: {iconSize: [32, 32]}});
        var icon_gempa = new ga_icon({iconUrl: url+'/icon/gb.png'});

        L.marker(@json($roq->laporan->loc), {
            icon: icon_gempa,
            title: '{{ $roq->laporan->title }}',
        }).addTo(map)
        .bindPopup('<b>{{ $roq->laporan->title }}</b><br />{{ $roq->laporan->waktu }} WIB, {{ $roq->laporan->magnitude }}.',{
            closeButton: false
        }).openPopup();

        L.control.defaultExtent({position:'topleft'}).addTo(map);
        L.control.zoom({position:'topright'}).addTo(map);
        L.control.attribution({position:'bottomright'})
                .setPrefix('MAGMA Indonesia')
                .addAttribution('<a href="http://esdm.go.id" title="Badan Geologi, ESDM" target="_blank">Badan Geologi, ESDM</a>')
                .addTo(map);
    });
</script>
@endsection