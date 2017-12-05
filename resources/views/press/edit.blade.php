@extends('layouts.default') 

@section('title') 
    Edit Press Release 
@endsection

@section('nav-edit-press')
                        <li class="{{ active('press.*') }}">
                            <a href="{{ route('press.edit',$press->id) }}">Edit Press Release</a>
                        </li>
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote-bs3.css') }}" />
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
						<span>Press Release</span>
					</li>
					<li class="active">
						<span>Edit </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Edit Press Release
			</h2>
			<small>Menu ini digunakan untuk merubah data Press Release terkait berita kebencanaan</small>
		</div>
	</div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <form role="form" id="form" method="POST" action="{{ route('press.update',$press->id) }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                        Content dari Press Release
                    </div>
                    <div class="panel-body">
                        @role('Super Admin|Admin MGA')
                        <div class="form-group">
                            <label>Pembuat Laporan</label>
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                <option value="{{ $user->id}}" {{ $user->id == $press->user->id ? 'selected' : ''}}>{{ $user->name }}</option>      
                                @endforeach
                            </select>
                            @if( $errors->has('user_id'))
                            <label class="error" for="user_id">{{ ucfirst($errors->first('user_id')) }}</label>
                            @endif
                        </div>
                        @endrole
                        <div class="form-group">
                            <label>Judul Press Release</label>
                            <input name="title" type="text" class="form-control" value="{{ $press->title }}" required>
                            @if( $errors->has('title'))
                            <label class="error" for="title">{{ ucfirst($errors->first('title')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Isi Press Release</label>
                            @if( $errors->has('body'))
                            <label class="error" for="body">{{ ucfirst($errors->first('body')) }}</label>
                            @endif<textarea name="body" class="summernote">{{ $press->body }}</textarea>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-sm btn-primary submit" type="submit">Submit</button>     
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
<script>

    $(document).ready(function () {

        // Initialize summernote plugin
        $('.summernote').summernote({
            height: '300px',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['hr']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ]
        });

        $("#form").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                title: {
                    required: 'Harap Masukkan Nama Role',
                    minlength: 'Minimal 10 karakter'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });


    });

</script>
@endsection