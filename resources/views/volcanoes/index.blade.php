@extends('layouts.default')

@section('title')
    Data Dasar - Gunung Api
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chamber') }}">Chamber</a></li>
                        <li>
                            <span>Data Dasar</span>
                        </li>
                        <li class="active">
                            <span>Gunung Api </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Gunung Api
                </h2>
                <small>Daftar data dasar Gunung Api Indonesia, mohon diupdate jika ada data yang tidak sesuai.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-sm-12">
                {{ $gadds->links() }}                
            </div>            
        </div>
        @foreach($gadds as $gadd)
            @if($loop->first or $loop->iteration % 3 == 0)
                <div class="row">
            @endif
                <div class="col-xs-12 col-md-4 col-lg-4">
                    <div class="hpanel plan-box hgreen active">
                        <div class="panel-heading hbuilt text-center">
                            <h4 class="font-bold">{{ $gadd->name }}</h4>
                        </div>
                        <div class="panel-body">
                            <h5>Pendahuluan</h5>
                            <p class="text-muted">
                                @if(!empty($gadd->history))
                                    {!! $gadd->history->body !!}
                                @else
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam a nunc varius, bibendum tortor a, iaculis mauris. Phasellus venenatis magna vel purus efficitur malesuada. Integer in metus id neque vehicula dictum vel nec augue. Mauris a magna eget enim feugiat ullamcorper non sed purus. Aliquam erat volutpat.
                                @endif
                            </p>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>
                                            <h5>Data Dasar</h5>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Tipe Gunung Api</b></td>
                                        <td>{{ $gadd->volc_type }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Ketinggian Puncak</b></td>
                                        <td>{{ $gadd->elevation.' mdpl' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Posisi Geografis</b></td>
                                        <td>{!! $gadd->longitude.'&deg;BT, '.$gadd->latitude.'&deg;LU' !!}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Kota/Kabupaten</b></td>
                                        <td>{!! $gadd->district !!}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Provinsi</b></td>
                                        <td>{!! $gadd->province !!}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Pos</b></td>
                                        <td>
                                            <ul class="list-unstyled">
                                                @foreach($gadd->pos as $pos)
                                                <li>{{ $pos->observatory }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5>Keterangan Lainnya</h5>                            
                            <p class="text-muted">
                                Diisi keterangan lainnya
                            </p>

                            <a href="{{ route('volcanoes.edit',['id'=>$gadd->id]) }}" class="btn btn-success btn-sm m-t-xs">Edit</a>
                        </div>
                    </div>
                </div>
            @if($loop->last == true or $loop->iteration % 3 ==0 )
                </div>
            @endif
        @endforeach
        <div class="row">
            <div class="col-sm-12">
                {{ $gadds->links() }}                
            </div>            
        </div>
    </div>
@endsection