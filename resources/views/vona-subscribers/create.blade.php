@extends('layouts.default')

@section('title')
Add Subscriber
@endsection

@section('content-header')
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li>
                        <a href="{{ route('chambers.index') }}">Chambers</a>
                    </li>
                    <li>
                        <a href="{{ route('chambers.datadasar.index') }}">Gunung Api</a>
                    </li>
                    <li>
                        <a href="{{ route('chambers.vona.index') }}">VONA</a>
                    </li>
                    <li class="active">
                        <span>Add Subscriber</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Form untuk menambahkan subscriber penerima notifikasi VONA
            </h2>
            <small>Volcano Observatory Notice for Aviation</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Add Subscriber
                </div>

                <div class="panel-body">

                    @if ($errors->any())
                    <div class="form-group col-sm-12">
                        <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                        </div>
                    </div>
                    @endif

                    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.subscribers.store') }}">
                        @csrf

                        <div class="tab-content">
                            <div class="p-m tab-pane active">

                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Masukkan email dan pilih group notifikasi email.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Email --}}
                                        <div class="form-group col-sm-12">
                                            <label>Email</label>
                                            <input name="email" class="form-control" type="text" value="{{ old('email') }}" required>

                                            @if( $errors->has('email'))
                                            <label class="error" for="email">{{ ucfirst($errors->first('email')) }}</label>
                                            @endif
                                        </div>

                                        {{-- Pilih Group Notifikasi --}}
                                        <div class="form-group col-sm-12">
                                            <label>Pilih Group Notifikasi</label>

                                            @foreach ($groups as $group)
                                            <div class="checkbox">
                                                <label><input name="groups[]" value="{{ $group }}" type="checkbox" class="i-checks groups" {{
                                                        (is_array(old('groups')) AND in_array(strtoupper($group), old('groups'))) ? 'checked' : ''
                                                        }}> {{ ucwords($group) }}</label>
                                            </div>
                                            @endforeach

                                            @if( $errors->has('groups'))
                                            <label class="error" for="groups">{{ ucfirst($errors->first('groups')) }}</label>
                                            @endif
                                            @if($errors->has('groups.*'))
                                            @foreach($errors->get('groups.*') as $error)
                                            <label class="error" for="groups">{{ $error[0] }}</label>
                                            @break
                                            @endforeach
                                            @endif
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label>Apakah notifikasi akan diaktifkan?</label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox-inline"><input name="status" value="1" type="radio" class="i-checks draft"
                                                            {{ (old('status') == '1' OR empty(old('status'))) ? 'checked' : ''}}> Ya </label>
                                                    <label class="checkbox-inline"><input name="status" value="0" type="radio" class="i-checks draft"
                                                            {{ old('status') == '0' ? 'checked' : ''}}> Tidak </label>

                                                    @if( $errors->has('status'))
                                                    <label class="error" for="status">{{ ucfirst($errors->first('status')) }}</label>
                                                    @endif

                                                    <div class="hr-line-dashed"></div>

                                                    <button class="btn btn-primary" type="submit">Tambahkan Subscriber</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection