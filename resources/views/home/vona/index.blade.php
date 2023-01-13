@extends('layouts.slim')

@section('title')
Volcano Observatory Notice for Aviation (VONA)
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

        <div class="card pd-30 mg-b-20">
            <label class="slim-card-title">Volcanoes</label>
            <div class="row row-xs">
                <div class="col-xs-12">
                    <a href="{{ route('vona.index') }}" type="button" class="btn btn-sm btn-primary mg-b-10">All Volcanoes</a>
                    @foreach ($gadds as $gadd)
                    <a href="{{ route('vona.index',['code' => $gadd->code]) }}" type="button" class="btn btn-sm btn-primary mg-b-10">{{ $gadd->name }} ({{ $gadd->vonas_count }})</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card pd-30">
            <div class="mg-b-30">
                {{ $vonas->appends(Request::except('page'))->onEachSide(1)->links('vendor.pagination.slim-paginate') }}
            </div>

            <div class="timeline-group">
            @foreach ($grouped as $date => $vonas)
                <div class="timeline-item timeline-day">
                    <div class="timeline-time">&nbsp;</div>
                    <div class="timeline-body">
                        <p class="timeline-date">
                            @if ($date === now()->format('Y-m-d'))
                            Today
                            @else
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('jS \\of F Y')}},
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->diffForHumans()  }}
                            @endif
                        </p>
                    </div>
                </div>

                @foreach ($vonas as $vona)
                <div class="timeline-item">
                    <div class="timeline-time"><small>{{ $vona->issued}} UTC</small>
                    @switch($vona->current_code)
                        @case('GREEN')
                            <a href="#" class="btn btn-sm btn-success">Green</a>
                            @break
                        @case('YELLOW')
                            <a href="#" class="btn btn-sm bg-yellow" style="color: white;">Yellow</a>
                            @break
                        @case('ORANGE')
                            <a href="#" class="btn btn-sm btn-warning">Orange</a>
                            @break
                        @default
                            <a href="#" class="btn btn-sm btn-danger">Red</a>
                    @endswitch
                    </div>

                    <div class="timeline-body">

                        @if ($vona->type == 'EXERCISE')
                        <div class="alert alert-outline alert-warning" role="alert">
                            <strong>VA EXERCISE VA EXERCISE VA EXERCISE</strong>
                        </div>
                        @endif

                        <p class="timeline-title">
                            <span class="square-10 {{ $vona->colorCss($vona->current_code) }} mg-r-5 rounded-circle"></span>
                            <a href="{{ route('vona.index',['code' => $vona->code_id]) }}">{{ $vona->gunungapi->name }} - {{ $vona->issued_utc }}</a>
                        </p>

                        <p class="timeline-author">
                            <span class="tx-primary tx-medium">{{ $vona->user->name }}</span>,
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vona->issued_local)->setTimezone('UTC')->diffForHumans() }}
                            @if ($vona->old_ven_uuid)
                            <a href="{{ route('v1.gunungapi.ven.show', $vona->old_ven_uuid) }}" class="badge badge-primary tx-white">Auto</a>
                            @endif
                        </p>

                        <p class="timeline-text">
                            {{ $vona->volcanoActivitySummary($vona) }} {{ $vona->volcanicCloudHeight($vona) }}
                        </p>

                        <a class="card-link m-b-10" href="{{ URL::signedRoute('vona.show', $vona) }}">View</a>

                       @if ($vona->type == 'EXERCISE')
                        <div class="alert alert-outline alert-warning mg-t-20" role="alert">
                            <strong>VA EXERCISE VA EXERCISE VA EXERCISE</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @endforeach
            </div>
        </div>

    </div>

    <div class="col-lg-3 mg-t-20 mg-lg-t-0">

        @if (session('status'))
            <div class="alert alert-success">
                <strong>Well done!</strong> To complete the subscription process, please click the link in the email we just sent you.
                If it doesn't show up in a few minutes, check your spam folder.
            </div>
        @endif

        {{-- <div class="card mg-b-10">
            <div class="card-body">
                <h5 class="card-title tx-dark tx-medium mg-b-10">Subscribe to get updates</h5>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger mg-b-10" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                <form method="post" action="{{ route('v1.subscribe.store') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="captcha" placeholder="Captcha" required>
                    </div>
                    <div class="form-group">
                        {!!  captcha_img('flat') !!}
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mg-b-10">Join the list</button>
                </form>

                <a href=""><u>Unsubscribe here</u></a>
            </div>
        </div> --}}

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