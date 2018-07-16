@extends('layouts.default')

@section('title')
    Pengajuan
@endsection

@section('content-body')
    <div class="content animate-panel content-boxed">
        <div class="row small-header">
            <div class="col-lg-offset-2 col-lg-8">
                <div class="hpanel">
                    <div class="panel-body">
                        <div id="hbreadcrumb" class="pull-right">
                            <ol class="hbreadcrumb breadcrumb">
                                <li><a href="{{ route('chambers.index') }}">Chambers</a></li>
                                <li class="active">
                                    <span>Board Pengajuan </span>
                                </li>
                            </ol>
                        </div>
                        <h2 class="font-light m-b-xs">
                            Social board
                        </h2>
                        <small>Message board for social interactions.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center">
            {{ $pengajuans->links() }}
        </div>
        <div class="row">
            <div class="col-lg-offset-2 col-lg-8">
                @foreach($pengajuans as $pengajuan)
                <div class="hpanel {{ $pengajuan->panel}}">
                    <div class="panel-body">
                        <div class="media social-profile clearfix">
                            <a class="pull-left">
                                <img src="{{ route('user.photo',['id' => $pengajuan->pelapor->id]) }}" alt="profile-picture">
                            </a>
                            <div class="media-body">
                                <h5>{{ $pengajuan->pelapor->name }}</h5>
                                <small class="text-muted">{{ $pengajuan->created_at->formatLocalized('%A, %d %B %Y').', '.$pengajuan->created_at->format('H:i:s') }}</small>
                                <br>
                                <span class="label {{ $pengajuan->label}}">{{ title_case($pengajuan->topik )}}</span>
                            </div>
                        </div>

                        <div class="social-content m-t-md">
                            {!! nl2br($pengajuan->pertanyaan) !!}
                            @if(!empty($pengajuan->foto_pertanyaan))
                            <hr>
                            <a href="{{ $pengajuan->foto_pertanyaan }}" target="_blank"><img src="{{ $pengajuan->foto_pertanyaan }}" alt="noone" style="max-height:180px;"></a>
                            @endif
                        </div>
                    </div>
                    <div class="panel-footer comment-{{ $pengajuan->id }}">
                        @if(!empty($pengajuan->jawaban))                        
                        <div class="social-talk">
                            <div class="media social-profile clearfix">
                                <a class="pull-left">
                                    <img src="{{ route('user.photo',['id' => $pengajuan->penjawab->id]) }}" alt="profile-picture">
                                </a>

                                <div class="media-body">
                                    <span class="font-bold">{{ $pengajuan->penjawab->name }}</span>
                                    <small class="text-muted">{{ $pengajuan->answered_at->formatLocalized('%A, %d %B %Y') }}</small>

                                    <div class="social-content">
                                        {{ ucfirst($pengajuan->jawaban) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="social-form">
                            <form id="{{ $pengajuan->id }}" role="form" method="POST" action="{{ route('chambers.pengajuan.update',['id' => $pengajuan->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input name="jawab" class="form-control" type="text" style="z-index: 1;"> 
                                    <span class="input-group-btn">
                                        <label class="btn btn-outline btn-primary btn-file">
                                            <span class="label-file-{{ $pengajuan->id }}">Browse </span> 
                                            <input id="file-{{ $pengajuan->id }}" accept="image/*" class="file" name="file" type="file" style="display: none;">
                                        </label>
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="row text-center">
            {{ $pengajuans->links() }}
        </div>
    </div>
@endsection

@section('add-script')
<script>
    $(document).ready(function() {
        $('input.file').on('change', function() {
            console.log($(this).attr('id'));
            var input = $(this),
                $id = input.attr('id'),
                label = $('#'+$id).val().replace(/\\/g, '/').replace(/.*\//, '');
            $('.label-'+$id).html(label);
        });
    });
</script>
@endsection