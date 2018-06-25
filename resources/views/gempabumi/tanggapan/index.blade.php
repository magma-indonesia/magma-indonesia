@extends('layouts.default')

@section('title')
    Gempa Bumi | Daftar Tanggapan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>
                            <a href="{{ route('chambers.index') }}">Chambers</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.gempabumi.index') }}">Gempa Bumi</a>
                        </li>
                        <li class="active">
                                <a href="{{ route('chambers.gempabumi.tanggapan.index') }}">Tanggapan</a>
                            </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Kejadian Gempa Bumi
                </h2>
                <small>Daftar data laporan Gempa Bumi berdasarkan data BMKG</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        MAGMA ROQ - Gempa Bumi
                    </div>
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body">
                        {{ $responses->links() }}
                        <div class="table-responsive">
                            <table id="table-ven" class="table  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Info Gempa</th>
                                        <th>Pelapor</th>
                                        <th>Pemeriksa</th>
                                        <th style="min-width: 180px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($responses as $key => $res)
                                    <tr>
                                        <td>{{ $responses->firstItem()+$key }}</td>
                                        <td>{{ 'Gempa terjadi pada '.$res->roq->wib.' WIB dengan magnitude '.$res->roq->magnitude.' SR '. ($res->roq->mmi ? 'dengan skala '.$res->roq->mmi : '').' dengan kota terdeket '.$res->roq->kota_terdekat }}</td>
                                        <td>{{ $res->pelapor->name }}</td>
                                        <td>{{ $res->pemeriksa->name ?? 'Belum ada' }}</td>
                                        <td>
                                            <a href="{{ route('chambers.gempabumi.tanggapan.show',$res->noticenumber_id) }}" class="btn btn-sm btn-info btn-outline" style="margin-right: 3px;">View</a>
                                            <a href="{{ route('chambers.gempabumi.tanggapan.edit',$res->noticenumber_id) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a> 
                                            @role('Super Admin')
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.gempabumi.tanggapan.destroy',['noticenumber_id' => $res->noticenumber]) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                            @endrole   
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
@endsection