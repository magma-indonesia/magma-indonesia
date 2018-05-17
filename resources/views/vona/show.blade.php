@extends('layouts.default')

@section('title')
    VONA | Volcano Observatory Notice for Aviation
@endsection

@section('nav-show-vona')
    <li class="{{ active('chambers.vona.*') }}">
        <a href="{{ route('chambers.vona.show',['uuid' => $vona->uuid]) }}">Draft VONA</a>
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
                    Daftar Draft VONA
                </h2>
                <small>Draft VONA yang pernah dibuat dan belum dikirim kepada stakeholder.</small>
            </div>
        </div>
    </div>
@endsection