@extends('layouts.default') 

@section('title') 
    Create Press Release 
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
						<a href="{{ route('chambers.index') }}">Chamber</a>
					</li>
					<li>
						<span>Press Release</span>
					</li>
					<li class="active">
						<span>Create </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Create Press Release
			</h2>
			<small>Menu ini digunakan untuk mmembuat Press Release terkait berita kebencanaan</small>
		</div>
	</div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <form role="form" id="form" method="POST" action="{{ route('chambers.v1.press.store') }}">
                @csrf
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                        Content dari Press Release
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Pembuat Laporan</label>
                            <select id="nama" class="form-control" name="nama">
                                @foreach($users as $user)
                                <option value="{{ $user->name}}" {{ $user->id == auth()->user()->id ? 'selected' : ''}}>{{ $user->name }}</option>      
                                @endforeach
                            </select>
                            @if( $errors->has('nama'))
                            <label class="error" for="nama">{{ ucfirst($errors->first('nama')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Judul Press Release</label>
                            <input name="judul" type="text" class="form-control" value="{{ old('judul') }}" required>
                            @if( $errors->has('judul'))
                            <label class="error" for="judul">{{ ucfirst($errors->first('judul')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Isi Press Release</label>
                            @if( $errors->has('deskripsi'))
                            <label class="error" for="deskripsi">{{ ucfirst($errors->first('deskripsi')) }}</label>
                            @endif
                            <textarea name="deskripsi" class="summernote">{{ old('deskripsi') }}</textarea>
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
                judul: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                judul: {
                    required: 'Harap Masukkan Judul Press Release',
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