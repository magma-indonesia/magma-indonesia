@extends('layouts.default')

@section('title')
    MAGMA-SIGERTAN
@endsection

@section('content-header')
    <div class="normalheader transition animated fadeIn">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Laporan Gerakan Tanah
                </h2>
                <p><strong>Sistem Informasi Gerakan Tanah - SIGERTAN</strong></p>
                <p>Memberikan informasi tanggapan yang dibuat oleh Tim Gerakan Tanah </p>
            </div>
        </div>
    </div>
    <div class="content animate-panel content-boxed">
        <div class="row">
            <div class="col-md-12">
                @include('includes.alert')
                <div class="hpanel">
                    <div class="text-center">
                    {{ $sigertans->links() }}
                    </div>
                    <div class="v-timeline vertical-container animate-panel"  data-child="vertical-timeline-block" data-delay="1">
                        @foreach($sigertans as $qls)
                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon">
                                <i class="fa fa-newspaper-o"></i>
                            </div>
                            <div class="vertical-timeline-content">
                                <div class="bg-white p-m">
                                    <div class="pull-right text-right">
                                        <button class="btn w-xs btn-sm btn-outline btn-info" type="button">Detail</button>
                                        <button class="btn w-xs btn-sm btn-outline btn-warning" type="button">Edit</button>
                                        @role('Super Admin')
                                        <button class="btn w-xs btn-sm btn-outline btn-danger" type="button">Delete</button>
                                        @endrole
                                    </div>
                                    <img alt="Ketua Tim - {{ $qls->ketua->name }}" class="img-circle m-b m-t-md" src="{{ route('user.photo',['id' => auth()->user()->id]) }}">
                                    <h4><a href="#">{{ $qls->ketua->name }}</a></h4>
                                    <div class="text-muted m-b-xs">Diperbarui pada tanggal {{ $qls->updated_at->formatLocalized('%d %B %Y') }}, pukul {{ $qls->updated_at->format('H:i:s') }} WIB,                               @if($qls->verifikator)
                                    dan diverifikasi oleh <span class="font-bold">{{ $qls->verifikator->user->name }} <i class="text-success fa fa-check-circle"></i></span>
                                    @else
                                    <span class="font-bold">laporan belum diverifikasi <i class="text-danger fa fa-close"></i></span>
                                    @endif
                                    </div>
                                    <label class="label label-{{ optional($qls->status)->status ? 'success' : 'danger'}}">{{ optional($qls->status)->status ? 'Diterbitkan oleh '.$qls->status->user->name : 'Belum terbit' }}</label>
                                    <div class="hr-line-dashed"></div>
                                    <h4>Kejadian</h4>
                                    <p>
                                        Gerakan tanah terjadi di {{ $qls->crs->kecamatan.', '.$qls->crs->kota.', '.$qls->crs->provinsi }} pada tanggal {{ $qls->crs->waktu_kejadian->formatLocalized('%d %B %Y') }}. Secara geografis, lokasi kejadian gerakan tanah terletak pada posisi <a href="http://maps.google.com/maps?q={{ $qls->crs->latitude }},{{ $qls->crs->longitude }}" class="btn btn-xs btn-magma" target="_blank">{{ $qls->crs->latitude < 0 ? abs($qls->crs->latitude).' LS' : $qls->crs->latitude.' LU' }}, {{ $qls->crs->longitude }} BT</a>
                                    </p>
                                </div>
                                <div class="border-top bg-light p-m">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 border-right">
                                            <dl>
                                                <dt>Tipe Gerakan Tanah</dt>
                                                <dd>{{ $qls->kondisi->tipe_gerakan ?? 'Belum ada data' }}</dd>
                                                <dt>Prakiraan Kerentanan</dt>
                                                <dd>{{ $qls->kondisi->prakiraan_kerentanan ? 'Daerah ini memiliki potensi '.str_replace_last(', ',' hingga ', strtolower(implode(', ',$qls->kondisi->prakiraan_kerentanan))).' untuk terjadi gerakan tanah.' : 'Belum ada data'}}</dd>
                                                @if($qls->kondisi->prakiraan_kerentanan)
                                                <div class="progress m-t-xs full progress-small">
                                                    <span title="Rendah" style="width: 33%" aria-valuemax="33" aria-valuemin="0" aria-valuenow="33" role="progressbar" class=" progress-bar progress-bar-success">
                                                    </span>
                                                    @if(last($qls->kondisi->prakiraan_kerentanan) == 'MENENGAH' || last($qls->kondisi->prakiraan_kerentanan) == 'TINGGI')
                                                    <span title="Menengah" style="width: 33%" aria-valuemax="66" aria-valuemin="33" aria-valuenow="66" role="progressbar" class=" progress-bar progress-bar-warning">
                                                    </span>
                                                    @endif
                                                    @if(last($qls->kondisi->prakiraan_kerentanan) == 'TINGGI')
                                                    <span title="Tinggi" style="width: 34%" aria-valuemax="100" aria-valuemin="66" aria-valuenow="100" role="progressbar" class=" progress-bar progress-bar-danger">
                                                    </span>
                                                    @endif
                                                </div>
                                                @endif
                                            </dl>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <dl>
                                                <dt>Material</dt>
                                                <dd>{{ $qls->kondisi->material ?? 'Belum ada data' }}</dd>
                                                <dt>Faktor Penyebab</dt>
                                                <dd>{{ $qls->kondisi->faktor_penyebab ? str_replace_last(', ',' dan ', title_case(implode(', ',$qls->kondisi->faktor_penyebab))) : 'Belum ada data' }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top bg-white p-m">
                                    <h4>Rekomendasi</h4>
                                    @if($qls->rekomendasi)
                                    <div>
                                    <button class="m-b btn btn-default btn-sm" type="button" data-toggle="collapse" data-target="#collapseRekomendasi{{ $qls->id }}" aria-expanded="false" aria-controls="collapseRekomendasi{{ $qls->id }}">
                                            Lihat Rekomendasi
                                    </button>
                                    </div>
                                    <div class="collapse" id="collapseRekomendasi{{ $qls->id }}">
                                        <div class="well well-lg">
                                            <p>{!! nl2br($qls->rekomendasi->rekomendasi) !!}</p>
                                        </div>
                                    </div>
                                    @else
                                    <p>Belum ada rekomendasi</p>
                                    @endif
                                </div>
                                <div class="panel-footer contact-footer bg-white">
                                    <div class="row">
                                        <div class="col-xs-offset-1 col-xs-2 border-right">
                                            <div title="Korban Meninggal" class="contact-stat"><span>Meninggal: </span> <strong>{{ $qls->kerusakan->meninggal}}</strong></div>
                                        </div>
                                        <div class="col-xs-2 border-right">
                                            <div title="Korban Luka-luka" class="contact-stat"><span>Luka-Luka: </span> <strong>{{ $qls->kerusakan->luka}}</strong></div>
                                        </div>
                                        <div class="col-xs-2 border-right">
                                            <div title="Rumah Rusak" class="contact-stat"><span><i class="text-danger fa fa-home"></i> Rusak: </span> <strong>{{ $qls->kerusakan->rumah_rusak}}</strong></div>
                                        </div>
                                        <div class="col-xs-2 border-right">
                                            <div title="Rumah Hancur" class="contact-stat"><span><i class="text-danger fa fa-home"></i> Hancur: </span> <strong>{{ $qls->kerusakan->rumah_hancur}}</strong></div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div title="Rumah Terancam" class="contact-stat"><span><i class="text-danger fa fa-home"></i> Terancam: </span> <strong>{{ $qls->kerusakan->rumah_terancam}}</strong></div>
                                        </div>
                                    </div>
                                    <div class="row border-top">
                                        <div class="col-xs-offset-1 col-xs-2 border-right">
                                            <div title="Lahan Rusak (dalam hektar)" class="contact-stat"><span>Lahan: </span> <strong>{{ $qls->kerusakan->lahan_rusak}}</strong></div>
                                        </div>
                                        <div class="col-xs-2 border-right">
                                            <div title="Jalan Rusak" class="contact-stat"><span>Jalan: </span> <strong>{{ $qls->kerusakan->jalan_rusak}}</strong></div>
                                        </div>
                                        <div class="col-xs-2 border-right">
                                            <div title="Bangunan Rusak" class="contact-stat"><span><i class="text-danger fa fa-building"></i> Rusak: </span> <strong>{{ $qls->kerusakan->bangunan_rusak}}</strong></div>
                                        </div>
                                        <div class="col-xs-2 border-right">
                                            <div title="Bangunan Hancur" class="contact-stat"><span><i class="text-danger fa fa-building"></i> Hancur: </span> <strong>{{ $qls->kerusakan->bangunan_hancur}}</strong></div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div title="Bangunan Terancam" class="contact-stat"><span><i class="text-danger fa fa-building"></i> Terancam: </span> <strong>{{ $qls->kerusakan->bangunan_terancam}}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center">
                    {{ $sigertans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection