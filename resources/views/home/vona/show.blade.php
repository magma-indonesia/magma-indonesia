@extends('layouts.slim')

@section('title')
{{ $vona->current_code }} | {{ strtoupper($vona->gunungapi->name) }} | {{ $vona->issued_utc }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">VONA</li>
<li class="breadcrumb-item active" aria-current="page">{{ strtoupper($vona->gunungapi->name) }} {{ $vona->issued_utc }}</li>
@endsection

@section('page-title')
{{ strtoupper($vona->gunungapi->name) }} {{ $vona->issued_utc }}
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-12">
        <div class="card card-table pd-l-30 pd-r-30">
            <div class="card-header">
                @if ($vona->type === 'EXERCISE')
                <h4 class="slim-card-title tx-18"> VA EXERCISE APAC VOLCEX 22/01</h4>
                @else
                <h4 class="slim-card-title tx-18">{{ $vona->gunungapi->name.' '.$vona->issued_utc }}</h4>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-mg-b">
                    <tbody>
                        <tr>
                            <td>(1)</td>
                            <td colspan="3" style="word-wrap:break-word;"><b>VOLCANO OBSERVATORY NOTICE FOR AVIATION - VONA</b></td>
                        </tr>
                        <tr>
                            <td>(2)</td>
                            <td><b>Issued</b></td>
                            <td><b>:</b></td>
                            <td>{{ $vona->issued_utc }}</td>
                        </tr>
                        <tr>
                            <td>(3)</td>
                            <td><b>Volcano</b></td>
                            <td><b>:</b></td>
                            <td>{{ $vona->gunungapi->name.' ('.$vona->ga_id_smithsonian.')' }}</td>
                        </tr>
                        <tr>
                            <td>(4)</td>
                            <td><b>Current Aviation Colour Code</b></td>
                            <td><b>:</b></td>
                            <td><b>{{ $vona->current_code }}</b></td>
                        </tr>
                        <tr>
                            <td>(5)</td>
                            <td><b>Previous Aviation Colour Code</b></td>
                            <td><b>:</b></td>
                            <td>{{ strtolower($vona->previous_code) }}</td>
                        </tr>
                        <tr>
                            <td>(6)</td>
                            <td><b>Source</b></td>
                            <td><b>:</b></td>
                            <td>{{ $vona->gunungapi->name }} Volcano Observatory</td>
                        </tr>
                        <tr>
                            <td>(7)</td>
                            <td><b>Notice Number</b></td>
                            <td><b>:</b></td>
                            <td>{{ $vona->noticenumber }}</td>
                        </tr>
                        <tr>
                            <td>(8)</td>
                            <td><b>Volcano Location</b></td>
                            <td><b>:</b></td>
                            <td>{{ $location }}</td>
                        </tr>
                        <tr>
                            <td>(9)</td>
                            <td><b>Area</b></td>
                            <td><b>:</b></td>
                            <td>{{ $vona->gunungapi->province_en }}, Indonesia</td>
                        </tr>
                        <tr>
                            <td>(10)</td>
                            <td><b>Summit Elevation</b></td>
                            <td><b>:</b></td>
                            <td>{{ round($vona->summit_elevation) }} FT ({{ round($vona->summit_elevation/3.2) }} M)</td>
                        </tr>
                        <tr>
                            <td>(11)</td>
                            <td><b>Volcanic Activity Summary</b></td>
                            <td><b>:</b></td>
                            <td>{{ $volcano_activity_summary }}</td>
                        </tr>
                        <tr>
                            <td>(12)</td>
                            <td><b>Volcanic Cloud Height</b></td>
                            <td><b>:</b></td>
                            <td>{{ $volcanic_cloud_height }}</td>
                        </tr>
                        <tr>
                            <td>(13)</td>
                            <td><b>Other Volcanic Cloud Information</b></td>
                            <td><b>:</b></td>
                            <td>{{ $other_volcanic_cloud_information }}</td>
                        </tr>
                        <tr>
                            <td>(14)</td>
                            <td><b>Remarks</b></td>
                            <td><b>:</b></td>
                            <td>
                                {{ blank($remarks) ? '-' : $remarks }}
                                @if ($vona->old_ven_uuid)
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{ route('v1.gunungapi.ven.show', $ven->uuid) }}"><img class="img-responsive" src="{{ $ven->erupt_pht }}" alt="" /></a>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>(15)</td>
                            <td><b>Contacts</b></td>
                            <td><b>:</b></td>
                            <td>Center for Volcanology and Geological Hazard Mitigation (CVGHM).<br> Tel: +62-22-727-2606.<br> Facsimile: +62-22-720-2761.<br> email : pvmbg@esdm.go.id</td>
                        </tr>
                        <tr>
                            <td>(16)</td>
                            <td><b>Next Notice</b></td>
                            <td><b>:</b></td>
                            <td>A new VONA will be issued if conditions change significantly or the colour code is changes.<br>
                            Latest Volcanic information is posted at <b>VONA | MAGMA Indonesia</b> Website.<br>
                            Link: <a href="{{ route('vona.index')}}">{{ route('vona.index') }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection