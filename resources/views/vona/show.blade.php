@extends('layouts.default')

@section('title')
    VONA | Volcano Observatory Notice for Aviation
@endsection

@section('nav-show-vona')
    <li class="{{ active('chambers.vona.*') }}">
        <a href="{{ route('chambers.vona.show',['uuid' => $vona->uuid]) }}">{{$vona->gunungapi->name}}</a>
    </li>
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('chambers.index') }}">Chamber</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.vona.index') }}">VONA</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.vona.show',['uuid' => $vona->uuid ]) }}">{{ $vona->gunungapi->name }}</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    {{ $vona->gunungapi->name.' '.$vona->issued_utc }}
                </h2>
                <small>VONA untuk Gunung {{ $vona->gunungapi->name }} diterbitkan pada {{ $vona->issued }} oleh {{ $vona->user->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel blog-article-box">
                    <div class="panel-footer">
                        <span class="pull-right">
                            <i class="fa fa-user"> </i> {{ $vona->user->name }}
                        </span>
                        <i class="fa fa-eye"> </i> {{ $vona->getViews() }}
                    </div>
                    <div class="panel-body">
                        @if ($vona->sent == 0) 
                        <h3><span class="label label-danger">DRAFT VONA</span></h3>
                        <div class="hr-line-dashed"></div>
                        @endif
                        <h4>{{ $vona->gunungapi->name.' '.$vona->issued_utc }}</h4>
                        <div class="table-responsive">
                            <table id="table-vona" class="table table-striped table-hover">
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
                                        <td>{{ $vona->gunungapi->name.' ('.$vona->gunungapi->smithsonian_id.')' }}</td>
                                    </tr>
                                    <tr>
                                        <td>(4)</td>
                                        <td><b>Current Aviation Colour Code</b></td>
                                        <td><b>:</b></td>
                                        <td><b>{{ $vona->cu_code }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>(5)</td>
                                        <td><b>Previous Aviation Colour Code</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->prev_code }}</td>
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
                                        <td>{{ $vona->location }}</td>
                                    </tr>
                                    <tr>
                                        <td>(9)</td>
                                        <td><b>Area</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->gunungapi->province }}, Indonesia</td>
                                    </tr>
                                    <tr>
                                        <td>(10)</td>
                                        <td><b>Summit Elevation</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ round($vona->gunungapi->elevation*3.3) }} FT ({{ $vona->gunungapi->elevation }} M)</td>
                                    </tr>
                                    <tr>
                                        <td>(11)</td>
                                        <td><b>Volcanic Activity Summary</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->vas }}</td>
                                    </tr>
                                    <tr>
                                        <td>(12)</td>
                                        <td><b>Volcanic Cloud Height</b></td>
                                        <td><b>:</b></td>
                                        <td>Best estimate of ash-cloud top is around {{ round($vona->vch_asl*3.3) }} FT ({{ $vona->vch_asl }} M) above sea level, may be higher than what can be observed clearly. Source of height data: ground observer. </td>
                                    </tr>
                                    <tr>
                                        <td>(13)</td>
                                        <td><b>Remarks</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->remarks ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>(15)</td>
                                        <td><b>Contacts</b></td>
                                        <td><b>:</b></td>
                                        <td>Center for Volcanology and Geological Hazard Mitigation (CVGHM).<br> Tel: +62-22-727-2606.<br> Facsimile: +62-22-720-2761.<br> email : vsi@vsi.esdm.go.id</td>
                                    </tr>
                                    <tr>
                                        <td>(16)</td>
                                        <td><b>Next Notice</b></td>
                                        <td><b>:</b></td>
                                        <td>A new VONA will be issued if conditions change significantly or the colour code is changes.<br>
                                        Latest Volcanic information is posted at <b>VONA | MAGMA Indonesia</b> Website.<br>
                                        Link: <a href="{{ route('chambers.vona.index')}}">{{ config('app.url') }}/vona</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h4>{{ $vona->gunungapi->name.' '.$vona->issued_utc }}</h4>
                        @if ($vona->sent == 0) 
                        <div class="hr-line-dashed"></div>
                        <h3><span class="label label-danger">DRAFT VONA</span></h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection