@extends('layouts.default')

@section('title')
    v1 | Absensi Pegawai 
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/fooTable/css/footable.bootstrap.min.css') }}" />
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
                        <li class="active">
                            <span>Absensi </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Daftar Absensi Pegawai PVMBG
                </h2>
                <small>Data absensi Pegawai PVMBG, Badan Geologi, ESDM</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Daftar Absensi Hari Ini
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            {{ $absensis->links() }}
                            <table id="absensi" class="footable table table-stripped toggle-arrow-tiny" data-sorting="true" data-show-toggle="true" data-paging="false" data-filtering="false" data-paging-size="31">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nip</th>
                                        <th>Nama</th>
                                        <th>Pos PGA</th>
                                        <th>Tanggal</th>
                                        <th>Checkin</th>
                                        <th>Checkout</th>
                                        <th>Durasi Kerja</th>
                                        <th>Radius Check In</th>
                                        <th>Radius Check Out</th>
                                        <th>Kehadiran</th>
                                        <th>Action</th>
                                        <th data-breakpoints="all" data-title="Foto Checkin">Foto Checkin</th>
                                        <th data-breakpoints="all" data-title="Foto Checkout">Foto Checkout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($absensis as $key => $absensi)
                                    <tr>
                                        <td></td>
                                        <td>{{ $absensi->vg_nip }}</td>
                                        <td>{{ $absensi->user->vg_nama }}</td>
                                        <td>{{ $absensi->pos->observatory }}</td>
                                        <td>{{ $absensi->date_abs }}</td>
                                        <td>{{ $absensi->checkin_time }}</td>
                                        <td>{{ $absensi->checkout_time == '00:00:00' ? '-' : $absensi->checkout_time}}</td>
                                        <td>{{ $absensi->length_work ? $absensi->length_work.' menit' : '-'}}</td>
                                        <td>{{ $absensi->checkin_dist ? number_format($absensi->checkin_dist,0,',','.').' m' : '-'}}</td>
                                        <td>{{ $absensi->checkout_dist ? number_format($absensi->checkout_dist,0,',','.').' m' : '-' }}</td>
                                        <td>
                                            @switch($absensi->ket_abs)
                                                @case(0)
                                                    <span class="label label-danger">Alpha</span>
                                                    @break
                                                @case(1)
                                                    <span class="label label-success">Hadir</span>
                                                    @break
                                                @default
                                                    <span class="label label-danger">Tidak ada dalam daftar</span>
                                            @endswitch
                                        </td>
                                        <td>View</td>
                                        <td><img class="img-responsive m-t-md" src="{{ $absensi->checkin_image }}"></td>
                                        <td>
                                        @if($absensi->checkout_image)
                                        <img class="img-responsive m-t-md" src="{{ $absensi->checkout_image }}">
                                        @else
                                        -
                                        @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $absensis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <script src="{{ asset('vendor/fooTable/js/footable.min.js') }}"></script>
@endsection

@section('add-script')
<script>

    $(document).ready(function() {

        $('#absensi').footable();

    });

</script>
@endsection