@extends('layouts.default')

@section('title')
Assign Role
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap-select.min.css') }}" />
@endsection

@section('content-header')
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li>
                        <a href="{{ route('chambers.index') }}">Chamber</a>
                    </li>
                    <li>
                        <span>Roles</span>
                    </li>
                    <li class="active">
                        <span>Assign </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Assign Roles
            </h2>
            <small>Menambahkan Roles kepada User tertentu</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>
                    Assign role pengguna.
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('chambers.roles.assign') }}">
                        @csrf
                        <div class="form-group">
                            <label>User</label>
                            <select name="nip" class="form-control selectpicker" data-live-search="true">
                                @foreach ($users as $user)
                                <option value="{{ $user->nip }}">{{ $user->nip }} - {{ $user->name }}</option>
                                @endforeach
                            </select>
                            @if( $errors->has('nip'))
                            <label class="error" for="nip">{{ ucfirst($errors->first('nip')) }}</label>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Roles</label>
                            @foreach($roles as $role)
                                <div class="checkbox">
                                    <label><input name="roles[]" value="{{$role->id}}" type="checkbox" class="i-checks roles" {{ $user->hasRole($role->name) ? 'checked':''}}> {{$role->name}} </label>
                                </div>
                            @endforeach
                            @if( $errors->has('roles'))
                            <label id="roles-error" class="error" for="roles">{{ ucfirst($errors->first('roles')) }}</label>
                            @endif
                        </div>

                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-sm btn-primary m-t-n-xs pull-right"
                            type="submit"><strong>Submit</strong></button>
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