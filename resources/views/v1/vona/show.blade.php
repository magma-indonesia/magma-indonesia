@extends('layouts.default')

@section('title')
    v1 | VONA 
@endsection

@section('content-header')
<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>MAGMA v1</span>
                        </li>
                        <li>
                            <span>VONA </span>
                        </li>
                        <li>
                            <span>{{ $vona->ga_nama_gapi}}</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    {{ $vona->ga_nama_gapi.' '.$vona->issued_time }} UTC
                </h2>
                <small>VONA untuk Gunung Apu {{ $vona->ga_nama_gapi }} diterbitkan pada {{ $vona->issued_time }} (UTC) oleh {{ $vona->nama }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel blog-article-box">
                    <div class="panel-body">
                        <h3>{{ $vona->ga_nama_gapi.' '.$vona->issued }}</h3>
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
                                        <td>{{ $vona->issued }}</td>
                                    </tr>
                                    <tr>
                                        <td>(3)</td>
                                        <td><b>Volcano</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->ga_nama_gapi.' ('.$vona->ga_id_smithsonian.')' }}</td>
                                    </tr>
                                    <tr>
                                        <td>(4)</td>
                                        <td><b>Current Aviation Colour Code</b></td>
                                        <td><b>:</b></td>
                                        <td><b>{{ $vona->cu_avcode }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>(5)</td>
                                        <td><b>Previous Aviation Colour Code</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ strtolower($vona->pre_avcode) }}</td>
                                    </tr>
                                    <tr>
                                        <td>(6)</td>
                                        <td><b>Source</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->ga_nama_gapi }} Volcano Observatory</td>
                                    </tr>
                                    <tr>
                                        <td>(7)</td>
                                        <td><b>Notice Number</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->notice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>(8)</td>
                                        <td><b>Volcano Location</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->volcano_location }}</td>
                                    </tr>
                                    <tr>
                                        <td>(9)</td>
                                        <td><b>Area</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->area }}, Indonesia</td>
                                    </tr>
                                    <tr>
                                        <td>(10)</td>
                                        <td><b>Summit Elevation</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ round($vona->summit_elevation*3.3) }} FT ({{ $vona->summit_elevation }} M)</td>
                                    </tr>
                                    <tr>
                                        <td>(11)</td>
                                        <td><b>Volcanic Activity Summary</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->volcanic_act_summ }}</td>
                                    </tr>
                                    <tr>
                                        <td>(12)</td>
                                        <td><b>Volcanic Cloud Height</b></td>
                                        <td><b>:</b></td>
                                        <td>
                                        @if($vona->vch_height > 0)
                                            {{ $vona->vch_height_text }}
                                        @else
                                        Volcanic ash is not visible/observed.
                                        @endif</td>
                                    </tr>
                                    <tr>
                                        <td>(13)</td>
                                        <td><b>Other Volcanic Cloud Information</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $vona->other_vc_info ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>(14)</td>
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
                                        Link: <a href="{{ route('chambers.v1.vona.index')}}">{{ route('chambers.v1.vona.index')}}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @role('Super Admin')
                        <hr>
                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.v1.vona.destroy',['no'=>$vona->no]) }}" accept-charset="UTF-8">
                            @method('DELETE')
                            @csrf
                            <button value="Delete" class="m-t-xs btn btn-danger btn-outline delete" type="submit">Delete VONA</button>
                            </form>
                        @endrole
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-right">
                                    <i class="fa fa-user"> </i> {{ $vona->nama }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection