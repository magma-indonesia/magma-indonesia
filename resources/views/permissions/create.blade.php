@extends('layouts.default') 

@section('title') 
    MAGMA | Create Permission 
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
						<span>Permissions</span>
					</li>
					<li class="active">
						<span>Create </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Create Permissions
			</h2>
			<small>Menu ini digunakan untuk menambahkan Permission pengguna MAGMA Indonesia</small>
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
                    Masukkan daftar Permission pengguna.
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('permissions.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Nama Permission</label> 
                            <input name="name" type="text" placeholder="Masukkan Nama Permission" class="form-control" value="{{ old('name') }}" required>
                            @if( $errors->has('name'))
                            <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                            @endif
                        </div>
                        
                        @if(!$roles->isEmpty())
                        <div class="form-group">
                            <label>Roles</label>
                            @foreach($roles as $role)
                                <div class="checkbox">
                                    <label><input name="roles[]" value="{{$role->id}}" type="checkbox" class="i-checks" checked> {{$role->name}} </label>                       
                                </div>
                            @endforeach
                            @if( $errors->has('roles'))
                            <label id="roles-error" class="error" for="roles">{{ ucfirst($errors->first('roles')) }}</label>
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
                    required: 'Harap Masukkan Nama Permission',
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