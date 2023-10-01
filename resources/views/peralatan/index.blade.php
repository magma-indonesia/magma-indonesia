@extends('layouts.default')

@section('title')
    Peralatan Pemantauan Bencana Geologi
@endsection

@section('content-body')
<div class="content content-boxed">
    <div class="row">
        <div class="col-lg-12 text-center m-t-md">
            <img src="{{ asset('peralatan.png') }}" alt="Peralatan Logo" style="height: 160px;">
            <h2>
                Tata Kelola Peralatan Pemantauan Bencana Geologi
            </h2>
            <p class="p-md">
                Pemantauan bencana geologi dapat dilakukan dengan beberapa metode pemantauan, di antaranya adalah metode pemantauan visual, geofisika, geodesi, geologi, geokimia, dan remote sensing. Untuk menunjang kegiatan pemantauan tersebut, maka diperlukan sejumlah peralatan yang disesuaikan dengan metode pemantauan masing-masing. Menu ini membantu dalam melakukan inventarisasi peralatan pemantauan bencana geologi.
            </p>

            @include('includes.alert')

        </div>
    </div>

    {{-- Peralatan Pemantauan --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>

                    Peralatan Pemantauan
                </div>

                <div class="panel-body" style="background-color: transparent; border: none; padding: 0;">
                    <div class="row">

                        {{-- Metode Pemantauan --}}
                        <div class="col-md-4">
                            <div class="hpanel">
                                <div class="panel-body">
                                    <div class="stats-title pull-left">
                                        <h4>Peralatan Pemantauan</h4>
                                    </div>

                                    <div class="stats-icon pull-right">
                                        <i class="pe-7s-info fa-4x"></i>
                                    </div>

                                    <div class="m-t-xl">
                                        <div class="m-b-sm">
                                            <a href="{{ route('chambers.peralatan.pemantauan.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a>

                                            <a href="{{ route('chambers.peralatan.pemantauan.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                                        </div>

                                        <small>
                                            Memberikan informasi dan daftar peralatan pemantauan bencana geologi
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Metode dan Sub Metode --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>

                    Informasi Metode Pemantauan
                </div>

                <div class="panel-body" style="background-color: transparent; border: none; padding: 0;">
                    <div class="row">

                        {{-- Metode Pemantauan --}}
                        <div class="col-md-4">
                            <div class="hpanel">
                                <div class="panel-body">
                                    <div class="stats-title pull-left">
                                        <h4>Metode Pemantauan</h4>
                                    </div>

                                    <div class="stats-icon pull-right">
                                        <i class="pe-7s-info fa-4x"></i>
                                    </div>

                                    <div class="m-t-xl">
                                        <div class="m-b-sm">
                                            <a href="{{ route('chambers.peralatan.metode.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a>

                                            <a href="{{ route('chambers.peralatan.metode.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                                        </div>

                                        <small>
                                            Memberikan informasi dan daftar metode pemantauan
                                            bencana geologi
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Sub Metode Pemantauan --}}
                        <div class="col-md-4">
                            <div class="hpanel">
                                <div class="panel-body">
                                    <div class="stats-title pull-left">
                                        <h4>Sub Metode Pemantauan</h4>
                                    </div>

                                    <div class="stats-icon pull-right">
                                        <i class="pe-7s-info fa-4x"></i>
                                    </div>

                                    <div class="m-t-xl">
                                        <div class="m-b-sm">
                                            <a href="{{ route('chambers.peralatan.sub-metode.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a>

                                            <a href="{{ route('chambers.peralatan.sub-metode.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                                        </div>


                                        <small>
                                            Memberikan informasi dan daftar sub metode pemantauan bencana geologi
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Katalog dan Kategori Peralatan --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>

                    Katalog Barang/Peralatan
                </div>

                <div class="panel-body" style="background-color: transparent; border: none; padding: 0;">
                    <div class="row">

                        {{-- Katalog --}}
                        <div class="col-md-4">
                            <div class="hpanel">
                                <div class="panel-body">
                                    <div class="stats-title pull-left">
                                        <h4>Katalog Barang</h4>
                                    </div>

                                    <div class="stats-icon pull-right">
                                        <i class="pe-7s-info fa-4x"></i>
                                    </div>

                                    <div class="m-t-xl">
                                        <div class="m-b-sm">
                                            <a href="{{ route('chambers.peralatan.katalog-barang.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a>

                                            <a href="{{ route('chambers.peralatan.katalog-barang.create') }}" class="btn btn-outline btn-danger" target="_blank"><i class="fa fa-plus"></i> Create</a>
                                        </div>

                                        <small>
                                            Memberikan daftar katalog peralatan yang pernah dibuat
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="col-md-4">
                            <div class="hpanel">
                                <div class="panel-body">
                                    <div class="stats-title pull-left">
                                        <h4>Kategori Barang</h4>
                                    </div>

                                    <div class="stats-icon pull-right">
                                        <i class="pe-7s-info fa-4x"></i>
                                    </div>

                                    <div class="m-t-xl">
                                        <div class="m-b-sm">
                                            <a href="{{ route('chambers.peralatan.kategori-peralatan.index') }}" class="btn btn-outline btn-danger" target="_blank">View</a>

                                            <a href="{{ route('chambers.peralatan.kategori-peralatan.create') }}" class="btn btn-outline btn-danger" target="_blank"><i class="fa fa-plus"></i> Create</a>
                                        </div>

                                        <small>
                                            Pengelompokkan barang berdasarkan kategori. Satu barang bisa memiliki lebih dari satu kategori
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection