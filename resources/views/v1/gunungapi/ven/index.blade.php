@extends('layouts.default')

@section('title')
    v1 | Laporan Letusan (VEN)
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
                            <span>Gunung Api</span>
                        </li>
                        <li class="active">
                            <span>Laporan Letusan (VEN) </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Laporan Letusan Gunung Api (Volcano Eruption Notice)
                </h2>
                <small>Meliputi laporan letusan seluruh gunung api</small>
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
                        Volcano Eruption Notice
                    </div>
                    <div class="panel-body">
                        @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                            <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                        </div>
                        @endif
                        {{ $vens->links() }}
                        <div class="table-responsive">
                            <table id="table-ven" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Gunung Api</th>
                                        <th>Waktu Letusan</th>
                                        <th>Visual</th>
                                        <th>Tinggi Letusan (m)</th>
                                        <th>Warna Abu</th>
                                        <th>Arah Abu (ke)</th>
                                        <th>Durasi (detik)</th>
                                        <th>Pelapor</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vens as $key => $ven)
                                    <tr>
                                        <td>{{ $vens->firstItem()+$key }}</td>
                                        <td>{{ $ven->gunungapi->ga_nama_gapi }}</td>
                                        <td>{{ $ven->erupt_tgl.' '.$ven->erupt_jam }}</td>
                                        <td>{{ $ven->erupt_vis ? 'Teramati' : 'Tidak Teramati'}}</td>
                                        <td>{{ $ven->erupt_tka }}</td>
                                        <td>{{ $ven->erupt_wrn ? implode(', ',$ven->erupt_wrn) : 'Tidak teramati' }}</td>
                                        <td>{{ $ven->erupt_arh ? implode(', ',$ven->erupt_arh) : 'Tidak teramati' }}</td>
                                        <td>{{ $ven->erupt_drs }}</td>
                                        <td>{{ $ven->user->vg_nama }}</td>
                                        <td>
                                            <a href="{{ route('chambers.v1.gunungapi.ven.show',['id' => $ven->erupt_id]) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
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