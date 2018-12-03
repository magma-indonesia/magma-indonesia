@extends('layouts.default')

@section('title')
    Step 3 - Klimatologi
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
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.laporan.create.var.klimatologi') }}">Step 3 - Klimatologi</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru Input data Klimatologi.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Form MAGMA-VAR data Klimatologi
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.var.klimatologi') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 1 - Data Laporan</a>
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 2 - Visual</a>
                                <a class="btn btn-primary" href="#">Step 3 - Klimatologi</a>
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 4 - Kegempaan</a>
                            </div>
                            <hr>
                            <div class="tab-content">
                                <div id="step1" class="p-m tab-pane active">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class="pe-7s-cloud fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Buat Laporan MAGMA-VAR</strong>, form ini digunakan untuk input data pengamatan klimatologi.
                                                <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                            </p>
                                        </div>
                                        <div class="col=lg-9">
                                            @if ($errors->any())
                                            <div class="row m-b-md">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <p>{!! $error !!}</p>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            {{-- Button Footer --}}
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <a href="{{ route('chambers.laporan.create.var') }}" type="button" class="btn btn-default">Step 1 - Data Laporan</a>
                                                <a href="{{ route('chambers.laporan.create.var.visual') }}" type="button" class="btn btn-default">Step 2 - Visual</a>
                                                <a href="{{ route('chambers.laporan.create.var.klimatologi') }}" type="button" class="btn btn-default">Step 3 - Klimatologi</a>
                                                <button type="submit" class="submit btn btn-primary">Step 4 - Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection