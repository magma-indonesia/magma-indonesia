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
                {{ $vona->gunungapi->name.' '.$vona->issued }}
            </h2>
            <small>VONA untuk Gunung {{ $vona->gunungapi->name }} diterbitkan pada {{ $vona->issued_utc }} oleh {{ $vona->user->name }}</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">

                <div class="panel-footer">
                    <span class="pull-right">
                        <i class="fa fa-user"> </i> {{ $vona->user->name }}
                    </span>
                    <i class="fa fa-eye"> </i> {{ $vona->gunungapi->name.' '.$vona->issued_utc }}
                </div>

                <div class="panel-body p-xl">

                    @if ($vona->is_sent === false)
                    <div class="alert alert-outline alert-danger" role="alert">
                        <strong>VONA ini masih dalam bentuk draft dan belum dikirim</strong>
                    </div>
                    <div class="hr-line-dashed"></div>
                    @else
                    <div class="alert alert-outline alert-success" role="alert">
                        <strong>VONA telah terkirim pada {{ $vona->updated_at }} WIB</strong>
                    </div>
                    <div class="hr-line-dashed"></div>
                    @endif

                    @if ($vona->type === 'EXERCISE')
                    <h3> VA EXERCISE APAC VOLCEX 22/01</h3>
                    @else
                    <h4>{{ $vona->gunungapi->name.' '.$vona->issued_utc }}</h4>
                    @endif

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
                                    <td><b>{{ $vona->current_code }}</b></td>
                                </tr>
                                <tr>
                                    <td>(5)</td>
                                    <td><b>Previous Aviation Colour Code</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $vona->previous_code }}</td>
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
                                    <td>{{ round($vona->gunungapi->elevation*3.3) }} FT ({{ $vona->gunungapi->elevation }} M)</td>
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
                                    <td>{{ blank($remarks) ? '-' : $remarks }}</td>
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
                                    Link: <a href="{{ route('v1.vona.index')}}">{{ config('app.url') }}/v1/vona</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if ($vona->type === 'EXERCISE')
                    <h3> VA EXERCISE VA EXERCISE VA EXERCISE </h3>
                    @else
                    <h4>{{ $vona->gunungapi->name.' '.$vona->issued_utc }}</h4>
                    @endif

                    <div class="hr-line-dashed"></div>
                    @if ($vona->is_sent === false)
                    <div class="alert alert-outline alert-danger" role="alert">
                        <strong>VONA ini masih dalam bentuk draft dan belum dikirim</strong>
                    </div>
                    @else
                    <div class="alert alert-outline alert-success" role="alert">
                        <strong>VONA telah terkirim pada {{ $vona->updated_at }} WIB</strong>
                    </div>
                    @endif
                </div>

                <div class="panel-body float-e-margins p-xl">
                    <div class="row">

                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <form method="post" action="{{ route('chambers.vona.pdf', $vona) }}">
                                @csrf
                                <button class="btn btn-outline btn-block btn-magma" type="submit">Download PDF</a>
                            </form>
                        </div>

                        @if ($vona->is_sent === false)

                            <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <form method="post" action="{{ route('chambers.vona.send-email', $vona) }}">
                                    @csrf
                                    <button name="group" value="send" class="btn btn-outline btn-block btn-magma" type="submit">Simpan tanpa Kirim</a>
                                </form>
                            </div>

                            @if ($vona->type === 'EXERCISE')
                            <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <form method="post" action="{{ route('chambers.vona.send-email', $vona) }}">
                                    @csrf
                                    <button name="group" value="exercise" class="btn btn-outline btn-block btn-magma" type="submit">Krim VONA Exercise</a>
                                </form>
                            </div>
                            @else
                            <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <form method="post" action="{{ route('chambers.vona.send-email', $vona) }}">
                                    @csrf
                                    <button name="group" value="real" class="btn btn-outline btn-block btn-magma" type="submit">Krim VONA</a>
                                </form>
                            </div>
                            @endif

                        @else
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <form method="post" action="{{ route('chambers.vona.send-email', $vona) }}">
                                @csrf
                                <button name="group" value="unsend" class="btn btn-outline btn-block btn-danger" type="submit">Unsend</a>
                            </form>
                        </div>
                        @endif

                        @role('Super Admin')
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <form method="post" action="{{ route('chambers.vona.send-email', $vona) }}">
                                @csrf
                                <button name="group" value="telegram" class="btn btn-outline btn-block btn-magma" type="submit">Krim VONA ke Telegram</a>
                            </form>
                        </div>

                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <form method="post" action="{{ route('chambers.vona.send-email', $vona) }}">
                                @csrf
                                <button name="group" value="pvmbg" class="btn btn-outline btn-block btn-magma" type="submit">Krim VONA ke Grup PVMBG</a>
                            </form>
                        </div>
                        @endrole

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection