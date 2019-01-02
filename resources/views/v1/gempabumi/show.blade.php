@extends('layouts.default') 

@section('title') 
    Gempa Bumi
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel blog-box blog-article-box">
                <div class="panel-heading">
                    <h4>{{ $roq->roq_title }}</h4>
                    <div class="text-muted small">
                    Tanggapan dibuat oleh: <span class="font-bold">{{ $roq->roq_nama_pelapor }}</span>
                    </div>
                </div>
                <div class="panel-image">
                    <img class="img-responsive" src="{{ $roq->roq_maplink ? $roq->roq_maplink : 'https://magma.vsi.esdm.go.id/img/empty-esdm.jpg'}}" style="margin: auto;max-height: 360px;">
                </div>
                <div class="panel-body press-release" style="text-align: justify;">
                    <h3 class="font-bold">Deskripsi</h3>
                    <p class="m-b-lg">{!! htmlspecialchars_decode($roq->roq_intro) !!}</p>
                    <h3 class="font-bold">Kondisi Wilayah</h3>
                    <p class="m-b-lg">{!! htmlspecialchars_decode($roq->roq_konwil) !!}</p>
                    <h3 class="font-bold">Mekanisme</h3>
                    <p class="m-b-lg"> {!! htmlspecialchars_decode($roq->roq_mekanisme) !!}</p>
                    <h3 class="font-bold">Dampak</h3>
                    <p class="m-b-lg">{!! htmlspecialchars_decode($roq->roq_efek) !!}</p>
                </div>
                <div class="panel-body press-release" style="text-align: justify;">
                    <h3 class="font-bold">Rekomendasi</h3>
                    <p class="m-b-lg">{!! nl2br($roq->roq_rekom) !!}</p>
                </div>
                <div class="panel-footer">
                    <b>Sumber Data : </b>{{ $roq->roq_source}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection