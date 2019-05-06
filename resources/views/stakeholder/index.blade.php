@extends('layouts.default')

@section('title')
    Stakeholder
@endsection

@section('add-vendor-css')
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
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
                        <li class="active">
                            <a href="{{ route('chambers.stakeholder.index') }}">Stakeholder</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Stakeholder 
                </h2>
                <small>Daftar Stakeholder untuk akses MAGMA.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                @role('Super Admin')
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent
                @endrole

                <div class="hpanel">
                    <div class="panel-heading">
                        Stakeholder
                    </div>
                    <div class="panel-body">
                        <div class="text-left">
                            <a href="{{ route('chambers.stakeholder.create') }}" type="button" class="btn btn-magma btn-outline">Tambah Stakeholder</a>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    @role('Super Admin')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    @endrole
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            @role('Super Admin')
            $('#json-renderer').jsonViewer(@json($stakeholders), {collapsed: true});
            @endrole
        });
    </script>
@endsection