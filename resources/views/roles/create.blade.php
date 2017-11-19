@extends('layouts.default') 

@section('title') 
    MAGMA | Create User 
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
						<span>Roles</span>
					</li>
					<li class="active">
						<span>Create </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Create Roles
			</h2>
			<small>Menu ini untuk digunakan untuk menambahkan pengguna MAGMA Indonesia</small>
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
                    Masukkan daftar role pengguna.
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('roles.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Nama Role</label> 
                            <input name="name" type="text" placeholder="Masukkan Nama Role" class="form-control" value="{{ old('name') }}" required>
                            @if( $errors->has('name'))
                            <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                            @endif
                        </div>
                        
                        @if(!$permissions->isEmpty())
                        <div class="form-group">
                            <label>Permissions</label>
                            @foreach($permissions as $permission)
                                <div class="checkbox">
                                    <label><input name="permissions[]" value="{{$permission->id}}" type="checkbox" class="i-checks" checked> {{$permission->name}} </label>    
                                </div>
                            @endforeach
                            @if( $errors->has('permissions'))
                            <label id="permissions-error" class="error" for="permissions">{{ ucfirst($errors->first('permissions')) }}</label>
                            @endif
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
                    minlength: 3
                }
            },
            messages: {
                name: {
                    required: 'Harap Masukkan Nama Role',
                    minlength: 'Minimal 3 karakter'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

    });

</script>
@endsection