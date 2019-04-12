@extends('layouts.slim') 

@section('title')
VONA
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item">VONA</li>
<li class="breadcrumb-item active" aria-current="page">Issued</li>
@endsection

@section('page-title')
VONA
@endsection
 
@section('main')
<div class="row row-sm row-timeline">
    <div class="col-lg-9">
        <div class="card pd-30 mg-b-20">
            <div class="card-body">
                <h5 class="card-title tx-dark tx-medium mg-b-10">What is VONA?</h5>
                <p class="card-text tx-thin"><b>VONA stands for Volcano Observatory Notice for Aviation</b>. It issues reports for changes, both increases and decreases, in volcanic activities, providing a description on the nature of the unrest or eruption, potential or current hazards as well as likely outcomes. See the following link (USGS) for further details. The Center for Volcanology and Geological Hazard Mitigation (CVGHM) under the Geological Agency of the Indonesian Ministry of Energy and Mineral Resources produced VONA's reports based on analysis of data from the agency's monitoring networks as well as from direct observations. VONA's alert levels are color-coded to indicate the different types of notifications addressing specific informative needs. The reports are disseminated via email to national and international stakeholders in the aviation sector. Other interested parties can avail of them through email subscription. All notifications are publicly available online.</p>
                <a href="https://volcanoes.usgs.gov/vhp/notifications.html" class="card-link">USGS</a>
            </div>
        </div>
        @if (!$grouped->isEmpty())
        <div class="card pd-30">
            <div class="mg-b-30">
                {{ $vonas->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
            </div>
            <div class="timeline-group">

                @foreach ($grouped as $date => $grouped_vonas)

                @if ($date != now()->format('Y-m-d'))
                <div class="timeline-item timeline-day">
                    <div class="timeline-time">&nbsp;</div>
                    <div class="timeline-body">
                    <p class="timeline-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('jS \\of F Y')}}, {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->diffForHumans()  }}</p>
                     </div>
                </div>
                @else
                <div class="timeline-item timeline-day">
                    <div class="timeline-time">&nbsp;</div>
                    <div class="timeline-body">
                        <p class="timeline-date">Today</p>
                    </div>
                </div>
                @endif

                @foreach ($grouped_vonas as $vona)
                <div class="timeline-item">
                    <div class="timeline-time"><small>{{ $vona->issued_time}} UTC</small>
                    @switch($vona->cu_avcode)
                        @case('GREEN')
                            <a class="btn btn-sm btn-success">Green</a>
                            @break
                        @case('YELLOW')
                            <a class="btn btn-sm bg-yellow" style="color: white;">Yellow</a>
                            @break
                        @case('ORANGE')
                            <a class="btn btn-sm btn-warning">Orange</a>
                            @break
                        @default
                            <a class="btn btn-sm btn-danger">Red</a>
                    @endswitch
                    </div>

                    <div class="timeline-body">
                        <p class="timeline-title"><a href="#">{{ $vona->ga_nama_gapi }} - {{ $vona->issued }}</a>&nbsp;
                        <p class="timeline-author"><a href="#">{{ $vona->nama }}</a>, {{ $vona->source }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vona->issued_time)->addHours(7)->diffForHumans() }} </p>
                        <p class="timeline-text">{{ ucfirst($vona->volcanic_act_summ).' '.$vona->vc_height_text.' '.$vona->other_vc_info }}.</p>
                        <a class="card-link" href="{{ URL::signedRoute('v1.vona.show',['id' => $vona->no ]) }}">View</a>
                    </div>
                </div>
                @endforeach

                @endforeach
            </div>
            <div class="mg-t-30">
                {{ $vonas->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
            </div>
        </div>
        @else
        <div class="alert alert-danger pd-30 mg-b-30" role="alert">
            <strong>No VONA found.</strong> Please check your search parameters.
        </div>
        @endif
    </div>
    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title tx-dark tx-medium mg-b-10">VONA Color Code</h5>
                <button class="btn btn-sm btn-success mg-b-10">Green</button>
                <p class="card-text tx-thin">Volcano is in normal, non-eruptive state, or, after a change from a higher level: Volcanic activity considered to have ceased, and volcano reverted to its normal, non-eruptive state.</p>
                <hr>
                <button class="btn btn-sm bg-yellow mg-b-10" style="color: white;">Yellow</button>
                <p class="card-text tx-thin">Volcano is experiencing signs of elevated unrest above known background levels, or, after a change from higher level: Volcanic activity has decreased significantly but continues to be closely monitored for possible renewed increase.</p>
                <hr>
                <button class="btn btn-sm btn-warning mg-b-10">Orange</button>
                <p class="card-text tx-thin"> Volcano is exhibiting heightened unrest with increased likelihood of eruption with column height <b>below</b> 6000 meter above sea level.</p>
                <hr>
                <button class="btn btn-sm btn-danger mg-b-10">Red</button>
                <p class="card-text tx-thin">Eruption is forecast to be imminent with significant emission of ash with column height <b>above</b> 6000 meter above sea level.</p>
            </div>
        </div>
    </div>
</div>
@endsection