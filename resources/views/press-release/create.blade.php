@extends('layouts.default')

@section('title')
Buat Press Release
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/summernote/dist/summernote-bs3.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
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
    <form action="{{ route('chambers.press-release.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-b-lg">
                                <div class="col-lg-4 text-center m-b-lg">
                                    <i class="pe-7s-ribbon fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Judul dan Kategori</strong>
                                    </p>
                                    <p>
                                        Gunakan judul yang jelas dan pilih kategori press release yang sesuai.
                                    </p>
                                </div>

                                <div class="col-lg-8">

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Judul</label>
                                            <input type="text" value="{{ old('judul') }}" class="form-control" name="judul" placeholder="Judul dari Press Release" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Tanggal Press Release</label>
                                            <input name="datetime" id="datepicker" class="form-control" type="text" value="{{ empty(old('datetime')) ? now()->format('Y-m-d H:i') : old('date') }}">
                                            @if( $errors->has('datetime'))
                                            <label class="error" for="datetime">{{ ucfirst($errors->first('datetime')) }}</label>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>No. Surat (optional)</label>
                                            <input type="text" value="{{ old('no_surat') }}" class="form-control" name="no_surat" placeholder="No. Surat Press Release jika ada">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Pilih Kategori</label>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">

                                                    @foreach ($categories as $value => $name)
                                                    <div class="checkbox">
                                                        <label><input
                                                        id="{{ $value }}"
                                                        name="categories[]" value="{{ $value }}" type="checkbox" class="i-checks categories" {{
                                                                (is_array(old('categories')) AND in_array($value, old('categories'))) ? 'checked'
                                                                : '' }}> {{ $name }} </label>
                                                    </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 lainnya" style="display: {{ (is_array(old('categories')) AND in_array('lainnya', old('categories'))) ? 'block' :'none'}};">
                                            <label>Kategori Lainya</label>

                                           <div><p>Jika tidak ada pilihan kategori yang terdaftar, silahkan masukkan kategori lainnya di sini</p></div>

                                            <input type="text" value="{{ old('lainnya') }}" class="form-control" name="lainnya" placeholder="Kategori lainnya">

                                            @if( $errors->has('lainnya'))
                                            <label class="error" for="lainnya">{{ ucfirst($errors->first('lainnya')) }}</label>
                                            @endif
                                        </div>

                                        <div class="form-group col-sm-12 gunung-api" style="display: {{ (is_array(old('categories')) AND in_array('gunung_api', old('categories'))) ? 'block' :'none'}};">
                                            <label>Gunung Api</label>
                                            <select id="code" class="form-control" name="code">
                                                @foreach($gadds as $gadd)
                                                <option value="{{ $gadd->code }}" {{ old('code') == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}</option>
                                                @endforeach
                                            </select>
                                            @if( $errors->has('code'))
                                            <label class="error" for="code">{{ ucfirst($errors->first('code')) }}</label>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Pilih Label</label>
                                            <div><p>Label digunakan untuk melakukan grouping jenis berita. Berguna untuk dilakukan filtering. Jika label belum ada, bisa ditambahkan melalui <a href="{{ route('chambers.tag.index') }}" target="_blank"><b>tautan berikut ini</b></a> </div>
                                            <div class="row">
                                                @foreach ($tags->chunk(5) as $tagsChunk)
                                                <div class="col-sm-12 col-md-6">
                                                    @foreach ($tagsChunk as $tag)
                                                    <div class="checkbox">
                                                        <label><input name="tags[]" value="{{ $tag->id }}" type="checkbox" class="i-checks tags" {{
                                                                (is_array(old('tags')) AND in_array($tag->id, old('tags'))) ? 'checked'
                                                                : '' }}> {{ $tag->name }} </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-b-lg">
                                <div class="col-lg-4 text-center m-b-lg">
                                    <i class="pe-7s-ribbon fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Dokumen dan Gambar Pendukung</strong>
                                    </p>
                                    <p>
                                        Upload dokumen dan gambar pendukung. File dokumen maksimal yang bisa diupload adalah sebesar 3MB sementara gambar, maksimal 1MB per gambar.
                                    </p>
                                </div>

                                <div class="col-lg-8">
                                    {{-- Dokumen --}}
                                    <div class="form-group">
                                        <label>Dokumen</label>
                                        <div><p>Format dokumen yang diterima adalah format PDF dengan ukuran per filenya <strong>maksimal 5MB.</strong></p></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group btn-file">
                                            <span class="input-group-btn">
                                                <label class="btn btn-primary">
                                                    <i class="fa fa-upload"></i>
                                                    <span class="label-file">Browse </span>
                                                    <input id="file_" accept=".pdf" class="file" name="files[]" type="file" style="display: none;">
                                                </label>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger clear-file"><i class="fa fa-trash"></i></button>
                                            </span>
                                            <input class="form-control overviews-files" name="overviews[files][]" type="text" placeholder="(Optional) Keterangan file">
                                            <span class="input-group-btn add-remove-button">
                                                <button type="button" class="btn btn-primary add-file">+</button>
                                            </span>

                                        </div>
                                        <span class="span-file"></span>
                                    </div>
                                    <hr>

                                    {{-- Peta KRB --}}
                                    <div class="form-group">
                                        <label>Peta KRB/Grafik/Hasil Pemodelan</label>
                                        <div><p>Gunakan menu ini untuk mengupload file hasil olahan data pemantauan. Format yang diterima adalah format gambar. Per file <strong>maksimal 3MB.</strong></p></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group btn-file">
                                            <span class="input-group-btn">
                                                <label class="btn btn-primary">
                                                    <i class="fa fa-upload"></i>
                                                    <span class="label-file">Browse </span>
                                                    <input id="file_" accept="image/jpeg" class="file" name="petas[]" type="file" style="display: none;">
                                                </label>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger clear-file"><i class="fa fa-trash"></i></button>
                                            </span>
                                            <input class="form-control overviews-files" name="overviews[petas][]" type="text" placeholder="(Optional) Keterangan file" value="">
                                            <span class="input-group-btn add-remove-button">
                                                <button type="button" class="btn btn-primary add-file">+</button>
                                            </span>

                                        </div>
                                        <span class="span-file"></span>
                                    </div>
                                    <hr>

                                    {{-- Foto/Gambar --}}
                                    <div class="form-group">
                                            <label>Foto/Gambar</label>
                                            <div><p>Bisa dalam bentuk Infografis, Poster, Leaflet, Flyer atau Publikasi lainnya. Format yang diterima adalah format gambar. Per file <strong>maksimal 3MB.</strong></p></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group btn-file">
                                            <span class="input-group-btn">
                                                <label class="btn btn-primary">
                                                    <i class="fa fa-upload"></i>
                                                    <span class="label-file">Browse </span>
                                                    <input id="file_" accept="image/jpeg" class="file" name="gambars[]" type="file" style="display: none;">
                                                </label>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger clear-file"><i class="fa fa-trash"></i></button>
                                            </span>
                                            <input class="form-control overviews-files" name="overviews[gambars][]" type="text" placeholder="(Optional) Keterangan file" value="">
                                            <span class="input-group-btn add-remove-button">
                                                <button type="button" class="btn btn-primary add-file">+</button>
                                            </span>

                                        </div>
                                        <span class="span-file"></span>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">

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
                                            <textarea name="deskripsi" class="summernote">{{ old('deskripsi') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel hred">
                    <div class="panel-body">
                        <div class="tab-pane active">
                            <div class="row m-b-lg">
                                <div class="col-lg-4 text-center m-b-lg">
                                    <i class="pe-7s-ribbon fa-5x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Simpan dan Publikasi</strong>
                                    </p>
                                    <p>
                                        Simpan dan atau publikasi press release yang dibuat.
                                    </p>
                                </div>

                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Apakah Press Release akan dipublikasikan langsung?</label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="checkbox" style="font-weight: normal"><input name="is_published" value="1" type="radio" class="i-checks is-published"
                                                            {{ (old('is_published') == '1' OR empty(old('is_published'))) ? 'checked' : ''}}> Ya, segera publikasikan </label>
                                                    <label class="checkbox" style="font-weight: normal"><input name="is_published" value="0" type="radio" class="i-checks is-published"
                                                            {{ (old('is_published') == '0') ? 'checked' : ''}}> Tidak, simpan sebagai draft </label>

                                                    <span class="help-block m-b-none">Pastikan informasi press release telah mengikuti penggunaan bahasa Indonesia yang baik dan benar.</span>

                                                    @if( $errors->has('is_published'))
                                                    <label class="error" for="is_published">{{ ucfirst($errors->first('is_published')) }}</label>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group col-sm-12">
                                            <hr>
                                            <a href="{{ url()->previous() }}" class="btn btn-outline btn-danger">Cancel</a>
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/moment/locale/id.js') }}"></script>
<script src="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function() {
    $('#datepicker').datetimepicker({
        minDate: '2015-05-01',
        maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
        sideBySide: true,
        locale: 'id',
        format: 'YYYY-MM-DD HH:mm',
    });

    $('input.file').on('change', function(e) {
        var input = $(this),
            label = input.val()
                        .replace(/\\/g, '/')
                        .replace(/.*\//, '');

        input.siblings('.label-file').html('Uploaded');
        input.parents('.input-group').siblings('.span-file').html(label);
    });

    function resetValueAfterClear(element) {
        element.find('input').val('');
        element.find('.label-file').html('Browse');
        element.find('.span-file').html('');
    };

    $('.clear-file').on('click', function(e) {
        var element = $(this).closest('.form-group');
        resetValueAfterClear(element);
    });

    $('.add-file').on('click', function() {
        var element = $(this).closest('.form-group'),
            $clone = element.clone(true),
            $removePlus  = $clone.find('.add-remove-button').remove(),
            $remove = '<span class="input-group-btn"><button type="button" class="btn btn-danger remove-file">-</button></span>',
            $addRemove = $clone.find('.overviews-files').after($remove);

        resetValueAfterClear($clone);

        element.after($clone);
    });

    $('form').on('click','.remove-file',function(){
        $(this).closest('.form-group').remove();
    });

    $('.summernote').summernote({
        height: '1000',
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

    $("input[id='gunung_api']").on('ifChecked', function(event) {
        $('.gunung-api').show();
    });

    $("input[id='lainnya']").on('ifChecked', function(event) {
        $('.lainnya').show();
    });

    $("input[id='gunung_api']").on('ifUnchecked', function(event) {
        $('.gunung-api').hide();
    });

    $("input[id='lainnya']").on('ifUnchecked', function(event) {
        $('.lainnya').hide();
    });
});
</script>
@endsection