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
    <div class="col-lg-9">
        <div class="card pd-30">
            {{ $vens->onEachSide(1)->links() }}
            <div class="timeline-group mg-t-20">
                <div class="timeline-item timeline-day">
                    <div class="timeline-time"><small>{{ now() }} WIB</small></div>
                    <div class="timeline-body">
                        <p class="timeline-date">Hari Ini</p>
                        <hr>
                    </div>
                </div>

                @foreach ($grouped as $date => $grouped_vens)
                <div class="timeline-item timeline-day">
                    <div class="timeline-time"><small>{{ $date }}</small></div>
                    <div class="timeline-body">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y').', '.
                     \Carbon\Carbon::createFromFormat('Y-m-d', $date)->diffForHumans() }}</div>
                </div>
                @foreach($grouped_vens as $ven)
                <div class="timeline-item">
                    <div class="timeline-time"><small>{{ $ven->erupt_jam}}</small></div>
                    <div class="timeline-body">
                        <p class="timeline-title"><a href="#">{{ $ven->gunungapi->ga_nama_gapi }}</a></p>
                        <p class="timeline-author">Dibuat oleh <a href="#">{{ $ven->user->vg_nama }}</a></p>
                        <p class="timeline-text">
                            @if ($ven->erupt_vis)
                                Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }} dengan tinggi kolom abu teramati &plusmn; {{ $ven->erupt_tka }} m di atas puncak (&plusmn; {{ $ven->erupt_tka+$ven->gunungapi->ga_elev_gapi }} m di atas permukaan laut). Kolom abu teramati berwarna {{ str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_wrn))) }} dengan intensitas {{ str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_int)))  }} ke arah {{ str_replace_last(', ',' dan ', strtolower(implode(', ',$ven->erupt_arh))) }}.
                            @else
                                Terjadi erupsi G. {{ $ven->gunungapi->ga_nama_gapi }} pada hari {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->formatLocalized('%A, %d %B %Y') }}, pukul {{ $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea }}. Visual letusan tidak teramati. Erupsi ini terekam di seismograf dengan amplitudo maksimum {{ $ven->erupt_amp }} mm dan durasi {{ $ven->erupt_amp }} detik.
                            @endif
                        </p>
                        <div class="row mg-b-15">
                            <div class="col-6">
                            <a href="#"><img src="{{ $ven->erupt_pht }}" class="img-fluid" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>
            {{ $vens->onEachSide(1)->links() }}
        </div>

    </div>
</div>
@endsection