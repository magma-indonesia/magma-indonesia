@extends('layouts.slim')

@section('title')
Letusan Gunung Api {{ $ven->gunungapi->ga_nama_gapi }}, {{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') }}
@endsection

@section('description')
Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }}.
@endsection

@section('add-vendor-css')
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
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Gunung Api</a></li>
<li class="breadcrumb-item"><a href="#">Letusan</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $ven->gunungapi->ga_nama_gapi }}</li>
@endsection

@section('page-title')
Informasi Letusan
@endsection

@section('main')
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card card-blog bd-0">
            <div id="map" class="ht-250 ht-sm-300 ht-md-400 bd-0"></div>
            <div class="card-body bd-t-0">               
                <div class="card card-blog-split">
                    <div class="row no-gutters">
                        @if ($ven->erupt_pht)
                        <div class="col-md-5 col-lg-6 col-xl-5">
                            <figure>
                                <img src="{{ $ven->erupt_pht }}" class="img-fit-cover" alt="Letusan {{ $ven->gunungapi->ga_nama_gapi }}">
                            </figure>
                        </div>
                        @endif
                        <div class="col-md-7 col-lg-6 col-xl-7">
                            <p class="blog-category tx-danger">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y').', '.\Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->diffForHumans() }}</p>
                            <h5 class="blog-title"><a>Gunung Api {{ $ven->gunungapi->ga_nama_gapi }}</a></h5>
                            <p class="card-subtitle tx-normal mg-b-15">Dibuat oleh, {{ $ven->user->vg_nama }}</p>
                            <p>
                            @if ($ven->erupt_vis)
                                Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }} dengan tinggi kolom abu teramati &plusmn; {{ $ven->erupt_tka }} m di atas puncak (&plusmn; {{ $ven->erupt_tka+$ven->gunungapi->ga_elev_gapi }} m di atas permukaan laut). Kolom abu teramati berwarna {{ str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_wrn))) }} dengan intensitas {{ str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_int)))  }} ke arah {{ str_replace_last(', ',' dan ', strtolower(implode(', ',$ven->erupt_arh))) }}. 
                            @else
                                Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }}. Visual letusan tidak teramati. 
                            @endif
                            @if ($ven->erupt_amp)
                            Erupsi ini terekam di seismograf dengan amplitudo maksimum {{ $ven->erupt_amp }} mm dan durasi {{ $ven->erupt_drs }} detik.
                            @endif
                            </p>
                            <h5 class="blog-title"><a>Rekomendasi</a></h5>                            
                            <p class="blog-text">
                                {!! nl2br($ven->erupt_rek) !!}
                            </p>
                            <div class="btn-wrapper mg-t-30">
                                <a id="tweet" href="" role="button" class="btn btn-primary" data-text="@if($ven->erupt_vis)Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }} tinggi kolom abu teramati &plusmn; {{ $ven->erupt_tka }} m di atas puncak.@else Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }}.@endif @if($ven->erupt_amp)Erupsi terekam di seismograf dengan amplitudo maksimum {{ $ven->erupt_amp }} mm dan durasi {{ $ven->erupt_drs }} detik.@endif"><i class="fa fa-twitter mg-r-5"></i>Tweet</a>
                            </div>
                        </div><!-- col-8 -->
                    </div><!-- row -->
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('add-script')
<script>
$(document).ready(function () {

    $('#tweet').on('click', function(e) {
        var pageUrl = window.location.href;
        var text = $(this).data('text');
        var tweetAbleUrl = makeTweetAbleUrl(text, pageUrl)

        $(this).attr('href',tweetAbleUrl);
        console.log(tweetAbleUrl);

        window.open(
            e.target.getAttribute('href'),
            'twitterwindow', 
            'height=450, width=550, toolbar=0, location=0, menubar=0, directories=0, scrollbars=0'
        );
    });

    function makeTweetAbleUrl(text, pageUrl)
    {
        return 'https://twitter.com/intent/tweet?url=' + pageUrl + '&text=' + encodeURIComponent(text)+ '&via=id_magma&lang=id';
    }

    var url = '{{ url('/') }}';
    var krb_esri = 'https://services7.arcgis.com/Y24oyWJVNs6VLjiH/arcgis/rest/services/KRB_GA_ID/FeatureServer/0';
    var query = "MAG_CODE='{{ $ven->ga_code }}'";
    var map = L.map('map', {
                    zoomControl: false,
                    center: [{{ $ven->gunungapi->ga_lat_gapi }},{{ $ven->gunungapi->ga_lon_gapi }}],
                    zoom: 11,
                    attributionControl:false,
                }).setMinZoom(10).setMaxZoom(12);

    var layerEsriStreets = L.esri.basemapLayer('NationalGeographic').addTo(map);
    var layerWorldTransportation = L.esri.basemapLayer('ImageryTransportation',{attributionControl: false}).addTo(map);

    var erupt = L.Icon.extend({options: {iconSize: [48, 72]}});
    var erupt_4 = new erupt({iconUrl: url+'/icon/erupt4.gif'});

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

    L.marker([{{ $ven->gunungapi->ga_lat_gapi }},{{ $ven->gunungapi->ga_lon_gapi }}], {
        icon: erupt_4,
        title: '{{ $ven->gunungapi->ga_nama_gapi }}',
    }).addTo(map)
    .bindPopup('<h4 class="tx-center">{{ $ven->gunungapi->ga_nama_gapi }}</h4>',{
        closeButton: true
    }).openPopup();

    L.control.defaultExtent({position:'topleft'}).addTo(map);
    L.control.zoom({position:'topright'}).addTo(map);
    L.control.attribution({position:'bottomleft'})
            .addAttribution('MAGMA Indonesia - <a href="http://esdm.go.id" title="Badan Geologi, ESDM" target="_blank">Badan Geologi, ESDM</a>')
            .addTo(map);
});
</script>
@endsection