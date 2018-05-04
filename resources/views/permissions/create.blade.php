@extends('layouts.default') 

@section('title') 
    Create Permission 
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
                    Masukkan daftar Permission pengguna.
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('permissions.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nama Permission</label>
                        </div>
                        @if (count($errors)==0)
                        <div class="form-group">
                            <div class="input-group">
                                <input name="name[]" type="text" placeholder="Min 3 Karakter, Maksimal 10 Karakter" class="form-control" value="" required>
                                <span class="input-group-btn"> 
                                    <button type="button" class="btn btn-primary add-permission" style="width:40px;"> + </button>
                                </span>
                            </div>
                        </div>
                        @else
                        @for($i = 0; $i < count(old('name')); $i++)
                        <div class="form-group">
                            <div class="input-group">
                                <input name="name[]" type="text" placeholder="Min 3 Karakter, Maksimal 10 Karakter" class="form-control" value="{{ old('name.'.$i) }}" required>
                                <span class="input-group-btn">
                                @if($i>0)                  
                                    <button type="button" class="btn btn-danger remove-permission" style="width:40px;"> - </button>
                                @else
                                    <button type="button" class="btn btn-primary add-permission" style="width:40px;"> + </button>                        
                                @endif
                                </span>
                            </div>
                            @if($errors->has('name.*'.$i))
                            <label id="name-error" class="error" for="name[]">{{ ucfirst($errors->first('name.'.$i)) }}</label>
                            @endif
                        </div>
                        @endfor
                        @endif
                        
                        @if(!$roles->isEmpty())
                        <div class="form-group">
                            <label>Roles</label>
                            @foreach($roles as $role)
                                <div class="checkbox">
                                    <label><input name="roles[]" value="{{$role->id}}" type="checkbox" class="i-checks"> {{$role->name}} </label>                       
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
                        <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>Submit</strong></button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section('add-vendor-script')
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
@endsection 

@section('add-script')
<script>
    
	$(document).ready(function(){

        var $checkAll = $('input.all'),
            $checkboxes = $('input.i-checks');

        $checkAll.on('ifChecked ifUnchecked', function(event) {        
            if (event.type == 'ifChecked') {
                $checkboxes.iCheck('check');
            } else {
                $checkboxes.iCheck('uncheck');
            }
        });

        $checkboxes.on('ifChanged', function(event){
            if($checkboxes.filter(':checked').length == $checkboxes.length) {
                $checkAll.prop('checked', 'checked');
            } else {
                $checkAll.removeProp('checked');
            }
            $checkAll.iCheck('update');
        });

        $('.add-permission').on('click', function(){

            var $ele = $(this).closest('.form-group').clone(true),
                $removePlus  = $ele.find('.input-group-btn').remove(),
                $remove = '<span class="input-group-btn"><button type="button" class="btn btn-danger remove-permission" style="width:40px;"> - </button></span>',
                $addRemove = $ele.find('input').after($remove);
            
            $(this).closest('.form-group').after($ele);

        });

        $('form').on('click','.remove-permission',function(){
            $(this).closest('.form-group').remove();
        });

        $("#form").validate({
            errorPlacement: function(error, element) {
                console.log(element);
                if (element.attr('name') == 'name[]' ){
                    $(element).closest('.input-group').after(error);
                }
            },
            ignore: [],
            rules: {
                'name[]': {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                'name[]': {
                    required: 'Nama Permission tidak boleh kosong',
                    minlength: 'Minimal 4 karakter'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

    });

</script>
@endsection