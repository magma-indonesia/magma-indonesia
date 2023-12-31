@extends('layouts.default')

@section('title')
Edit {{ $glossary->judul }}
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
                <strong>Edit {{ $glossary->judul }}</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                    <li><a href="{{ route('chambers.glossary.index') }}">Glossary</a></li>
                    <li class="active">
                        <span>Edit</span>
                    </li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Gunakan menu ini merubah deskripsi istilah.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan performa bisa terjadi sewaktu-waktu
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed no-top-padding">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel hred">
                <div class="panel-body">
                    <form action="{{ route('chambers.glossary.update', $glossary->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="p-m tab-pane active">

                            <div class="row m-b-lg">
                                <div class="col-lg-4 text-center m-b-lg">
                                    <i class="pe-7s-ribbon fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Glossary</strong>
                                    </p>
                                    <p>
                                        Gunakan judul yang jelas, tuliskan deskripsi dengan menggunakan bahasa Indonesia yang baku dan sesuai dengan EYD.
                                    </p>
                                </div>
                                <div class="col-lg-8">

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
                                            <label>Istilah</label>
                                            <input type="text" value="{{ old('judul') ?: $glossary->judul }}" class="form-control" name="judul" placeholder="Istilah yang akan dijelaskan" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="summernote">{{ old('deskripsi') ?: $glossary->deskripsi }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Referensi/Rujukan/Sumber (optional)</label>
                                            <p>Referensi tambahan bacaan terkait istilah yang sedang dijelaskan. <b>Gunakan titik koma (;)</b> sebagai pemisah jika lebih dari satu, Referensi bisa dalam bentuk URL atau artikel</p>
                                            <textarea type="text" rows="4" class="form-control" name="referensi" placeholder="Contoh: google.com; Bulletin Volcanology; usgs.gov">{{ old('referensi') ?: $glossary->reference }}</textarea>
                                        </div>
                                    </div>

                                    @if ($glossary->glossary_files->isNotEmpty())
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>File exist yang sedang digunakan</label>
                                            <div><p>Pilih file yang akan dihapus</p></div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="hpanel">
                                                        <div class="panel-body list">
                                                            <div class="table-responsive project-list">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Hapus?</th>
                                                                            <th>Preview</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($glossary->glossary_files as $file)
                                                                        <tr>
                                                                            <td><input type="checkbox" name="delete_files[]" class="i-checks" value="{{ $file->id }}"></td>
                                                                            <td><img class="img-responsive" src="{{ $file->thumbnail }}" alt="{{ $glossary->judul.'_'.$file->id }}">
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Upload Files Pendukung (optional)</label>
                                            <div><p>Bisa dalam bentuk Infografis, Poster, Leaflet, Flyer atau Publikasi lainnya. Format yang diterima adalah format gambar. Per file <strong>maksimal 3MB.</strong></p></div>
                                            <div class="m-b-sm">
                                                @for ($i = 0; $i < 5; $i++)
                                                <label class="w-xs m-t-sm btn btn-outline btn-default btn-file">
                                                    <i class="fa fa-upload"></i>
                                                    <span class="label-file">Browse </span>
                                                    <input id="file_{{ $i }}" accept="image/x-png,image/gif,image/jpeg" class="file" name="files[]" type="file" style="display: none;">
                                                </label>
                                                @endfor
                                            </div>
                                            <div class="m-t-sm">
                                                <button type="button" class="w-xs btn btn-danger clear-file"><i class="fa fa-trash"></i> Hapus File</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <hr>
                                            <label>Publikasikan?</label>
                                            <div><p>Segera publikasikan informasi</p></div>
                                            <div>
                                                <label class="checkbox-inline">
                                                <input name="is_published" class="i-checks" type="radio" value="1" id="status" {{ $glossary->is_published ? 'checked' : '' }}> Ya </label>
                                                <label class="checkbox-inline">
                                                <input name="is_published" class="i-checks" type="radio" value="0" id="status" {{ $glossary->is_published ? '' : 'checked' }}> Tidak </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <div class="text-left">
                                        <a href="{{ url()->previous() }}" class="btn btn-outline btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-danger next">Submit</button>
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
    })

    $('.summernote').summernote({
            height: '600px',
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