@extends('layouts.default') 

@section('title') 
    MAGMA | Edit {{ auth()->user()->name }} 
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.1/croppie.min.css" />
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
						<span>Pegawai</span>
					</li>
					<li class="active">
						<span>Edit </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Edit Pegawai
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

                    @if(Session::has('flash_message'))
                    <div class="alert alert-danger">
                        <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                    </div>
                    @endif

                    <div class="panel-body">
                        <p>
                            Masukkan semua data-data yang dibutuhkan untuk merubah pengguna ke dalam MAGMA.
                        </p>

                        <form role="form" id="form" method="POST" action="{{ route('chambers.users.update',$user->id) }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
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
                                <label>Bidang</label> 
                                <select id="bidang" class="form-control" name="bidang">
                                    @foreach($bidangs as $bidang)
                                    @if(optional($user->bidang)->user_bidang_desc_id)
                                    <option value="{{ $bidang->id }}" {{ $user->bidang->user_bidang_desc_id == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                                    @else
                                    <option value="{{ $bidang->id }}" {{ (empty(old('bidang')) AND $loop->first) || old('bidang') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @if( $errors->has('bidang'))
                                <label id="nip-bidang" class="error" for="bidang">{{ ucfirst($errors->first('bidang')) }}</label>
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
                                <label>Password</label> 
                                <input id="password" name="password" type="password" placeholder="Masukkan Password" class="form-control" required>
                                @if( $errors->has('password'))
                                <label id="password-error" class="error" for="password">{{ ucfirst($errors->first('password')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Konfirmasi Password</label> 
                                <input name="password_confirmation" type="password" placeholder="Konfirmasi Password" class="form-control" required>
                                @if( $errors->has('password_confirmation'))
                                <label id="password_confirmation-error" class="error" for="password_confirmation">{{ ucfirst($errors->first('password_confirmation')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Upload Photo</label>
                                <div class="upload-message">Upload Foto Profil Anda</div>
                                <div class="upload-photo" style="display:none"></div>
                                <label class="form-control btn btn-primary btn-file">
                                    <span class="label-file">Browse </span> 
                                    <input accept="image/*" class="file" name="file" type="file" style="display: none;">
                                    <input type="hidden" id="imagebase64" name="imagebase64">
                                    <input type="hidden" id="filetype" name="filetype">                                  
                                </label>
                                @if( $errors->has('file'))
                                <label id="file-error" class="error" for="file">{{ ucfirst($errors->first('file')) }}</label>
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
                            @role('Super Admin')
                            @if(!$roles->isEmpty())
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
                            <div class="form-group">
                                <label>Pilih Semua</label> 
                                <div class="checkbox">
                                    <label><input type="checkbox" class="i-checks all"> Check All</label>    
                                </div>
                            </div>
                            @endif
                            @endrole
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
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.1/croppie.min.js"></script>
<script src="{{ asset('vendor/exif-js/exif.js') }}"></script>
@endsection 

@section('add-script')
<script>
	$(function(){

        var $checkAll = $('input.all'),
            $checkboxes = $('input.roles');

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

        $uploadCrop = $('.upload-photo').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300
            },
            boundary: {
                width:310,
                height:310
            }
        });

        $('input.file').on('change', function() {
             $('.upload-message').hide();
             $('.upload-photo').show();
        
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, ''),
                filetype = '.'+label.split('.').pop();
                reader = new FileReader();

            $('.label-file').html(label);
            console.log('Nama file : '+label+', tipe : '+filetype);
            $('#filetype').val(filetype);

            reader.onload = function (e) {
                $uploadCrop.croppie('bind', {
                    url: e.target.result,
                    orientation: 1
                }).then(function(){
                    console.log('Binding Berhasil');
                });
            }

            reader.readAsDataURL(this.files[0]);
            
        });

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
                },
                password: {
                    required: true,
                    minlength: 4
                },
                password_confirmation: {
                    required: true,
                    equalTo: '#password'
                },

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
                    required: 'Nomor HP Anda belum benar',
                    digits: 'Format nomor telpon belum benar',
                    minlength: 'Panjang karakter minimal No HP adalah 10 karakter',
                    maxlength: 'Panjang karakter maksimal No HP adalah 12 karakter'
                },
                password: {
                    required: 'Password belum diisi',
                    minlength: 'Minimal 4 karakter'
                },
                password_confirmation: {
                    required: 'Konfirmasi Password tidak boleh kosong',
                    equalTo: 'Password tidak sama'
                }
            },
            submitHandler: function(form) {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp){
                    $('input.file').remove();
                    $('#imagebase64').val(resp);
                    form.submit();
                });
            }
        });

    });

</script>
@endsection