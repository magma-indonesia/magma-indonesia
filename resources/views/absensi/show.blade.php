@extends('layouts.default')

@section('title')
    {{ $user->name }}
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/fooTable/css/footable.bootstrap.min.css') }}" />
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <img alt="logo" class="img-circle m-b m-t-md" src="http://chamber.localhost/images/user/photo/1">
                        <h3><a href="">{{ $user->name }}</a></h3>
                        <dl>
                            <dt>Penempatan</dt>
                            <dd>{{ $kantor->nama }}</dd>
                            <dt>Alamat</dt>
                            <dd>{{ $kantor->address }}</dd>
                            <dt>Ketinggian</dt>
                            <dd>{{ $kantor->elevation }} meter</dd>
                            <dt>Koordinat</dt>
                            <dd>{{ $kantor->longitude.' °BT' }}, {{ $kantor->latitude.' °LU' }}</dd>
                        </dl>
                    </div>
                    <div class="border-right border-bottom border-left p-m bg-light text-center">
                        <i class="pe-7s-display1 fa-4x"></i>

                        <h1 class="m-xs">{{ $durasi }} Jam</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            Durasi Kerja
                        </h3>
                        <small>Dihitung dari penjumlahan selisih waktu checkin dan checkout</small>
                    </div>
                    <div class="border-right border-bottom border-left p-m bg-white">
                        <div class="stats-title pull-left">
                            <h4>Persentase Kehadiran</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-graph3 fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h3 class="m-b-xs jumlah-vars">{{ $jumlah['total'] }} ({{ round($jumlah['hadir']/$jumlah['total']*100,2) }}%)</h3>
                            <span class="font-bold no-margins">
                                Jumlah Absensi
                            </span>
                            <div class="progress m-t-xs full progress-small">
                                <div style="width: {{ $jumlah['hadir']/$jumlah['total']*100 }}%" aria-valuemax="{{ $jumlah['total'] }}" aria-valuemin="0" aria-valuenow="{{ $jumlah['hadir'] }}" role="progressbar" class=" progress-bar progress-bar-magma">
                                    <span class="sr-only">{{ $jumlah['hadir']/$jumlah['total']*100 }}% Hadir</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <small class="stats-label">Kehadiran</small>
                                    <h4>{{ $jumlah['hadir'] }}</h4>
                                </div>
                                <div class="col-xs-3">
                                    <small class="stats-label">Alpha</small>
                                    <h4>{{ $jumlah['alpha'] }}</h4>
                                </div>
                                <div class="col-xs-3">
                                    <small class="stats-label">Sakit</small>
                                    <h4>{{ $jumlah['sakit'] }}</h4>
                                </div>
                                <div class="col-xs-3">
                                    <small class="stats-label">Ijin</small>
                                    <h4>{{ $jumlah['izin'] }}</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-3">
                                    <small class="stats-label">Cuti</small>
                                    <h4>{{ $jumlah['cuti'] }}</h4>
                                </div>
                                <div class="col-xs-3">
                                    <small class="stats-label">Tugas Belajar</small>
                                    <h4>{{ $jumlah['tugas_belajar'] }}</h4>
                                </div>
                                <div class="col-xs-3">
                                    <small class="stats-label">Dinas Luar</small>
                                    <h4>{{ $jumlah['dinas_luar'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-xs-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Export Table
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                <a id="csv" href='#' type="button" class="btn btn-outline btn-block btn-magma">Export to CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="m-b" id="filter"></div>
                        <hr>
                        <div id="paging"></div>
                        <table id="absensi" class="footable table table-stripped toggle-arrow-tiny" data-sorting="true" data-show-toggle="true" data-paging="true" data-filtering="true" data-filter-container="#filter" data-filter-delay="1" data-filter-position="left" data-paging-container="#paging" data-paging-position="left" data-paging-size="31">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Checkin</th>
                                    <th>Checkout</th>
                                    <th>Duration</th>
                                    <th>Kehadiran</th>
                                    <th>Keterangan</th>
                                    <th data-breakpoints="all" data-title="Foto Checkin">Foto Checkin</th>
                                    <th data-breakpoints="all" data-title="Foto Checkout">Foto Checkout</th>
                                    <th data-breakpoints="all" data-title="Radius Checkin">Radius Checkin</th>
                                    <th data-breakpoints="all" data-title="Radius Checkout">Radius Checkout</th>
                                    <th data-breakpoints="all" data-title="Koordinat Checkin">Koordinat Checkin</th>
                                    <th data-breakpoints="all" data-title="Koordinat Checkout">Koordinat Checkout</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absensi as $key => $value)
                                @if(!empty($value))
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ optional($value[0]->checkin)->formatLocalized('%H:%M:%S') ?? 'Manual'}}</td>
                                    <td>{{ optional($value[0]->checkout)->formatLocalized('%H:%M:%S') ?? '-'}}</td>
                                    <td>{{ optional($value[0]->checkout)->diffInHours($value[0]->checkin) ?? '-'}}</td>
                                    <td>{!! $value[0]->keterangan_label !!}</td>
                                    <td>{{ str_replace_last(', ',' dan ', implode(', ',$value[0]->keterangan_tambahan)) }}</td>
                                    <td><img class="img-responsive m-t-md" src="{{ $value[0]->checkin_image }}"></td>
                                    <td>
                                        {!!
                                            $value[0]->checkout ?
                                            '<img class="img-responsive m-t-md" src="'.$value[0]->checkout_image.'"':
                                            '-'
                                        !!}
                                    </td>
                                    <td>{{ $value[0]->checkin_distance }} meter</td>
                                    <td>{{ $value[0]->distance }} meter</td>
                                    <td>{{ $value[0]->checkin_longitude.'°BT, '.$value[0]->checkin_latitude.'°LU' }}</td>
                                    <td>{{ $value[0]->checkout ? $value[0]->checkout_longitude.'°BT, '.$value[0]->checkout_latitude.'°LU' : '-'}} </td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="label label-danger">Alpha</span></td>
                                    <td></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
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

        var csv = FooTable.get("#absensi").toCSV(true);
        
        $('#csv').attr('href','data:text/csv;charset=utf-8,' + encodeURI(csv))
                .attr('target','_blank')
                .attr('download','Absensi {{ $user->name }}.csv');

    });

</script>
@endsection