@extends('layouts.default')

@section('title')
Buat Press Release
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote-bs3.css') }}" />
@endsection

@section('content-header')
<div class="normalheader content-boxed">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-ribbon fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Buat Press Release</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                    <li><a href="{{ route('chambers.press-release.index') }}">Press Release</a></li>
                    <li class="active">
                        <span>Create</span>
                    </li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Gunakan menu ini untuk membuat press release.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed no-top-padding">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel hred">
                <div class="panel-body">
                    <form action="{{ route('chambers.press-release.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="p-m tab-pane active">

                            <div class="row">
                                <div class="col-lg-12 text-left">
                                    <h4 class="m-t-md">Konten dari Press Release</h4>
                                    <p>
                                        Gunakan judul yang jelas, tuliskan deskripsi dengan menggunakan bahasa Indonesia yang baku dan sesuai dengan EYD.
                                    </p>
                                </div>
                            </div>

                            <div class="row m-b-lg">

                                <div class="col-lg-12">

                                </div>

                                <div class="col-lg-12">

                                    @if ($errors->any())
                                    <div class="row">
                                        @foreach ($errors->all() as $error)
                                        <div class="form-group col-lg-12">
                                            <div class="alert alert-danger">
                                                {{ $error }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Judul</label>
                                            <input type="text" value="{{ old('judul') }}" class="form-control" name="judul" placeholder="Istilah yang akan dijelaskan" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Konten</label>
                                            <textarea name="deskripsi" class="summernote">{{ old('deskripsi') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <div class="text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-outline btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
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
<script src="{{ asset('vendor/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function() {
    $('input.file').on('change', function(e) {
        var input = $(this),
            label = input.val()
                        .replace(/\\/g, '/')
                        .replace(/.*\//, '');

        input.siblings('.label-file').html(label);
    });

    $('.clear-file').on('click', function(e) {
        $('.label-file').html('Browse');
    });

    $('.summernote').summernote({
        height: '450px',
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
});
</script>
@endsection