@extends('layouts.slim') 

@section('title')
Informasi Letusan
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item">Gunung Api</li>
<li class="breadcrumb-item active" aria-current="page">Letusan</li>
@endsection

@section('page-title')
Informasi Letusan
@endsection

@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-8">
        <div class="card pd-30 mg-b-20">
            <label class="slim-card-title">Filter Gunung Api</label>
            <div class="row row-xs">
                <div class="col-xs-12">
                    <a href="{{ route('v1.gunungapi.ven') }}" type="button" class="btn btn-primary mg-b-10">Semua Gunung Api</a>
                    @foreach ($records as $record)
                    <a href="{{ route('v1.gunungapi.ven',['code' => $record->ga_code]) }}" type="button" class="btn btn-primary mg-b-10">{{ $record->gunungapi->ga_nama_gapi }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card pd-30">
            {{ $vens->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
            <div class="timeline-group mg-t-20 mg-b-20">    
                @foreach ($grouped as $date => $grouped_vens)

                @if ($date != now()->format('Y-m-d'))
                <div class="timeline-item timeline-day">
                    <div class="timeline-time"></div>
                    <div class="timeline-body">
                        <p class="timeline-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y').', '.
                     \Carbon\Carbon::createFromFormat('Y-m-d', $date)->diffForHumans() }}</p></div>
                </div>
                @else
                <div class="timeline-item timeline-day">
                    <div class="timeline-time">&nbsp;</div>
                    <div class="timeline-body">
                        <p class="timeline-date">Hari Ini, {{ now()->formatLocalized('%A, %d %B %Y') }}</p>
                    </div>
                </div>
                @endif

                @foreach($grouped_vens as $ven)
                <div class="timeline-item">
                    <div class="timeline-time"><small>{{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea}} </small></div>
                    <div class="timeline-body">
                        <p class="timeline-title"><a href="#">{{ $ven->gunungapi->ga_nama_gapi }}</a></p>
                        <p class="timeline-author">Dibuat oleh <a href="#">{{ $ven->user->vg_nama }}</a></p>
                        <p class="timeline-text">
                            @if ($ven->erupt_vis)
                                Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }} dengan tinggi kolom abu teramati &plusmn; {{ $ven->erupt_tka }} m di atas puncak (&plusmn; {{ $ven->erupt_tka+$ven->gunungapi->ga_elev_gapi }} m di atas permukaan laut). Kolom abu teramati berwarna {{ str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_wrn))) }} dengan intensitas {{ str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_int)))  }} ke arah {{ str_replace_last(', ',' dan ', strtolower(implode(', ',$ven->erupt_arh))) }}. 
                            @else
                                Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }}. Visual letusan tidak teramati. 
                            @endif
                            @if ($ven->erupt_amp)
                            Erupsi ini terekam di seismograf dengan amplitudo maksimum {{ $ven->erupt_amp }} mm dan durasi {{ $ven->erupt_drs }} detik.
                            @endif
                        </p>
                        <div class="row mg-b-15">
                            <div class="col-6">
                            <a href="#"><img src="{{ $ven->erupt_pht }}" class="img-fluid" alt=""></a>
                            </div>
                        </div>

                        <div class="row mg-b-15">
                            <div class="col-12">
                                <a href="{{ URL::signedRoute('v1.gunungapi.ven.show', $ven) }}" class="btn btn-sm btn-outline-primary">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @endforeach
            </div>
            {{ $vens->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
        </div>

    </div>

    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
        <div class="card card-connection">
            <label class="slim-card-title">Jumlah Letusan Tahun {{ now()->format('Y') }}</label>
            @foreach ($counts as $ven)
            
                <div class="row row-xs">
                    <div class="col-4 tx-primary">{{ $ven->total }}</div>
                    <div class="col-8">Jumlah letusan <b class="tx-orange">Gunung {{ $ven->gunungapi->ga_nama_gapi }}</b> yang pernah tercatat.</div>
                </div>
                @if (!$loop->last)
                <hr>
                @endif

            @endforeach
        </div>

        <div class="card pd-20 mg-t-20">
            <h6 class="slim-card-title">Follow Kami</h6>
            <p>Pilih salah satu akun sosial media kami untuk mendapatkan update terkini tentang bahaya geologi di Indonesia.</p>
            <hr>
            <h6 class="slim-card-title">Pusat Vulkanologi dan Mitigasi Bencana Geologi</h6>
            <div class="tx-20">
              <a href="https://www.facebook.com/pvmbg" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
              <a href="https://twitter.com/vulkanologi_mbg" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
              <a href="https://www.instagram.com/pvmbg_kesdm/" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
            </div>
            <hr>
            <h6 class="slim-card-title">Badan Geologi</h6>
            <div class="tx-20">
              <a href="https://www.facebook.com/Badan-Geologi-401815270183848/" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
              <a href="https://twitter.com/kabargeologi" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
              <a href="https://www.instagram.com/kabargeologi/" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
            </div>
            <hr>
            <h6 class="slim-card-title">Kementerian ESDM</h6>
            <div class="tx-20">
              <a href="https://www.facebook.com/kesdm/" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
              <a href="https://twitter.com/kementerianesdm" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
              <a href="https://www.instagram.com/kesdm/" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection