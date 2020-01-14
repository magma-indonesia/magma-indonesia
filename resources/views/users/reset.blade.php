@extends('layouts.default')

@section('title')
    {{ config('app.name') }}
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
@endsection

@section('content-body')
<div class="content content-boxed">
    <div class="row">
        <div class="col-lg-12 text-center m-t-md">
            <h2>Reset Password</h2>
        </div>
    </div>

    @if ($errors->any())
    <div class="row m-t-lg">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row m-t-md">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="hpanel">
                <div class="panel-body">
                    <form action="{{ route('chambers.users.reset') }}" method="post" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama</label>
                            <div class="col-sm-9">
                                <select name="nip" class="form-control selectpicker" data-live-search="true">
                                    @foreach ($users as $user)
                                    <option value="{{ $user->nip }}">{{ $user->nip }} - {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Password Baru</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password">
                                <span class="help-block m-b-none">Minimal 6 karakter.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Konfirmasi Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">Reset Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/bootstrap/bootstrap-select.min.js') }}"></script>
@endsection