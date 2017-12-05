@extends('layouts.default') 

@section('title') 
    MAGMA | Edit User 
@endsection

@section('nav-edit-user')
                        <li class="{{ active('users.*') }}">
                            <a href="{{ route('users.edit',$user->id) }}">Edit User</a>
                        </li>
@endsection

@section('content-header')
<div class="small-header">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li>
						<a href="{{ route('chamber') }}">Chamber</a>
					</li>
					<li>
						<span>Users</span>
					</li>
					<li class="active">
						<span>Edit </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Edit User
			</h2>
			<small>Menu ini untuk digunakan untuk merubah informasi pengguna MAGMA Indonesia</small>
		</div>
	</div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>
                    Edit Data Dasar Pengguna
                </div>
                <div class="panel-body">
                    <p>
                        Masukkan semua data-data yang dibutuhkan untuk merubah pengguna ke dalam MAGMA.
                    </p>

                    <form role="form" id="form" method="POST" action="{{ route('users.update',$user->id) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input name="_type" value="base" type="hidden">

                        <div class="form-group">
                            <label>Nama</label> 
                            <input name="name" type="text" placeholder="Masukkan Nama" class="form-control" value="{{ $user->name }}" required>
                            @if( $errors->has('name'))
                            <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>NIP</label> 
                            <input name="nip" type="text" placeholder="Masukkan NIP" class="form-control" value="{{ $user->nip }}"required>
                            @if( $errors->has('nip'))
                            <label id="nip-error" class="error" for="nip">{{ ucfirst($errors->first('nip')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Email</label> 
                            <input name="email" type="email" placeholder="Masukkan email" class="form-control" value="{{ $user->email }}" required>
                            @if( $errors->has('email'))
                            <label id="email-error" class="error" for="email">{{ ucfirst($errors->first('email')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Phone</label> 
                            <input name="phone" type="text" placeholder="Masukkan No Handphone" class="form-control" value="{{ $user->phone }}" required>
                            @if( $errors->has('phone'))
                            <label id="phone-error" class="error" for="phone">{{ ucfirst($errors->first('phone')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status User</label>
                            <div>
                                <label class="checkbox-inline"> 
                                <input name="status" class="i-checks" type="radio" value="1" id="status" {{ $user->status ? 'checked' : '' }}> Aktif </label> 
                                <label class="checkbox-inline">
                                <input name="status" class="i-checks" type="radio" value="0" id="status" {{ $user->status ? '' : 'checked' }}> Tidak Aktif </label> 
                            </div>
                        </div>
                        @if(!$roles->isEmpty())
                        <div class="form-group">
                            <label>Roles</label>
                            @foreach($roles as $role)
                                <div class="checkbox">
                                    <label><input name="roles[]" value="{{$role->id}}" type="checkbox" class="i-checks" {{ $user->hasRole($role->name) ? 'checked':''}}> {{$role->name}} </label>                       
                                </div>
                            @endforeach
                            @if( $errors->has('roles'))
                            <label id="roles-error" class="error" for="roles">{{ ucfirst($errors->first('roles')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Pilih Semua</label> 
                            <div class="checkbox">
                                <label><input type="checkbox" class="i-checks all"> Check All</label>    
                            </div>
                        </div>
                        @endif
                        <div class="hr-line-dashed"></div>
                        <div>
                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>Submit</strong></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection 

@section('add-script')
<script>
	$(function(){

        $("#form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 4
                },
                nip: {
                    required: true,
                    digits: true,
                    minlength: 18,
                    maxlength: 18
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 12
                }
            },
            messages: {
                name: {
                    required: 'Harap Masukkan Nama Anda',
                    minlength: 'Minimal 4 karakter'
                },
                nip: {
                    required: 'Harap Masukkan NIP Anda',
                    digits: 'NIP hanya menerima dalam bentuk karakter angka saja',
                    minlength: 'Panjang karakter NIP adalah 18 karakter',
                    maxlength: 'Panjang karakter NIP adalah 18 karakter'
                },
                email: {
                    required: 'Harap Masukkan email valid Anda',
                    email: 'Format email Anda belum benar'
                },
                phone: {
                    required: 'Harap Masukkan email valid Anda',
                    digits: 'Format nomor telpon belum benar',
                    minlength: 'Panjang karakter minimal No HP adalah 10 karakter',
                    maxlength: 'Panjang karakter maksimal No HP adalah 12 karakter'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

    });

</script>
@endsection