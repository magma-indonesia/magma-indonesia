@extends('layouts.default')
@section('title') Create Press Release
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
            <div class="alert alert-info">
                <i class="fa fa-bolt"></i>
                <strong> Mohon dibaca kembali sebelum membuat press release kepada publik. </strong>
            </div>
        </div>
        <div class="col-lg-12">
            <form role="form" id="form" method="POST" action="{{ route('chambers.v1.press.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                        Content dari Press Release
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Pembuat Laporan</label>
                            <select id="nama" class="form-control" name="nama">
                                @foreach($users as $user)
                                <option value="{{ $user->name}}" {{ $user->id == auth()->user()->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Judul Press Release</label>
                            <input name="judul" type="text" class="form-control" value="{{ old('judul') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Isi Press Release</label>
                            <textarea name="deskripsi" class="summernote">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="form-group">
                            <img class="img-responsive border-top border-bottom border-right border-left p-xs image-file" src="#" style="display:none;max-width: 200px;">
                        </div>
                        <div class="form-group">
                            <label>Upload Foto</label>
                            <br>
                            <label class="w-xs btn btn-outline btn-default btn-file">
                                <i class="fa fa-upload"></i>
                                <span class="label-file">Browse </span>
                                <input accept="image/jpeg" class="file" name="file" type="file" style="display: none;">
                                <input id="file" type="hidden" name="foto">
                            </label>
                            <button type="button" class="w-xs btn btn-danger clear-file"><i class="fa fa-trash"></i> Hapus Foto</button>
                            <br>
                            <label class="error">Maximum 700KB, disarankan <b>Landscape</b></label>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="w-xs btn btn-primary submit" type="submit">Submit</button>
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

        var height = window.screen.height;
        console.log(height > 480);

        height = height > 480 ? height/4 : 360;

        $('input.file').on('change', function(e) {
            var input = $(this),
                label = input.val()
                            .replace(/\\/g, '/')
                            .replace(/.*\//, ''),
                reader = new FileReader();

            $('.label-file').html(label);
            reader.onload = function (e) {
                $('.image-file')
                    .show()
                    .attr('src',e.target.result)
                    .css('max-height', height+'px');
                $('#file').val(e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        });

        $('.clear-file').on('click', function(e) {
            $('.image-file').hide();
            $('.file').val(null);
            $('.label-file').html('Browse');
        })

        // Initialize summernote plugin
        $('.summernote').summernote({
            height: '600',
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
            ],
            onpaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
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