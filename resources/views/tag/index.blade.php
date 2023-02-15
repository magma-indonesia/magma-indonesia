@extends('layouts.default')

@section('title')
Tags untuk Kategori atau Label
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/fooTable/css/footable.bootstrap.min.css') }}" />
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
@endsection

@section('content-header')
<div class="normalheader content-boxed">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="pe-7s-ribbon fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Tag/Label</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li class="active"><a href="{{ route('chambers.tag.index') }}">Tag</a></li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Tag digunakan untuk memberikan label pada press release, laporan, atau pengguna. Dapat juga digunakan untuk mengelompokkan data dalam satu kelompok yang sama. Tag atau label bersifat unik.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan performa bisa terjadi sewaktu-waktu
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed no-top-padding">

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <a class="text-muted" href="#add-tag">Add Tag</a>
                </div>

                @if (session('message'))
                <div class="alert alert-success">
                    <i class="fa fa-check"></i> {{ session('message') }}
                </div>
                @endif

                <div class="panel-body">

                    @if( $errors->has('name'))
                    <label class="error" for="name">{{ ucfirst($errors->first('name'))
                        }}</label>
                    @endif

                    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.tag.store') }}">
                        @csrf

                        <div class="input-group">
                            <input name="name" class="form-control" type="text"
                                value="{{ old('name') }}" required>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Tambahkan Tag</button>
                            </span>
                        </div>

                        <span class="help-block m-b-none">Tambahkan tag/label baru jika tidak ada label yang dicari.</span>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @if($tags->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-body">
                    <table id="table-tags" class="table table-striped" data-sorting="true" data-expand-first="true" data-page-size="10" data-paging="true" data-paging-limit="10" data-filtering="true" data-filter-placeholder="Cari..." data-filter-position="left">
                        <thead>
                            <tr>
                                <th>Nama Tag/Label</th>
                                <th data-sortable="false" data-breakpoints="xs sm md" data-title="Jumlah Pengggunaan Label">Jumlah Pengggunaan Label</th>
                                <th data-sortable="false" data-breakpoints="xs sm md" data-title="Action" width="40%">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($tags as $tag)
                            <tr>
                                <td>{{ $tag->name }}</td>
                                <td>
                                    <a href="{{ route('chambers.tag.show', $tag) }}">{{ $tag->press_releases_count }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('chambers.tag.edit', $tag) }}" class="btn btn-sm btn-warning btn-outline m-b-xs" type="button" title="Edit">Edit</a>

                                    @role('Super Admin')
                                    <a class="btn btn-sm btn-danger btn-outline m-b-xs form-submit" data-id="{{ $tag->id }}" type="button" title="Delete" data-value="delete"><i class="fa fa-trash"></i> Delete</a>
                                    @endrole
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@role('Super Admin')
<form id="form-destroy" style="display:none;" method="POST" data-action="{{ route('chambers.tag.index') }}">
    @csrf
    @method('DELETE')
</form>
@endrole
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/fooTable/dist/footable.min.js') }}"></script>
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function() {
    $('#table-tags').footable();

    function sweet_alert($value, $url, $data)
    {
        var title = 'Anda Yakin?',
            text = 'Data yang telah dihapus tidak bisa dikembalikan',
            confirm_button = 'Yes, hapus!';

        if ($value == '0') {
            title = 'Unpublish Informasi?';
            text = 'Informasi yang ditarik tidak akan tampil di MAGMA';
            confirm_button = 'Yes, Unpublish!';
        }

        if ($value == '1') {
            title = 'Publish Informasi?';
            text = 'Informasi yang di-publish akan tampil di MAGMA';
            confirm_button = 'Yes, Publish!';
        }

        swal({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: confirm_button,
            cancelButtonText: 'Gak jadi deh!',
            closeOnConfirm: false,
            closeOnCancel: true },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: $url,
                    data: $data,
                    type: 'POST',
                    success: function(data){
                        console.log(data);
                        if (data.success){
                            swal('Berhasil!', data.message, 'success');
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }
                    },
                    error: function(data){
                        var $errors = {
                            'status': data.status,
                            'exception': data.responseJSON.exception,
                            'file': data.responseJSON.file,
                            'line': data.responseJSON.line
                        };
                        console.log($errors);
                        swal('Gagal!', data.responseJSON.exception, 'error');
                    }
                });
            }
        });
    }

    function get_url($value)
    {
        return $value == 'delete' ? $('#form-destroy').data('action') : $('#form-update').data('action');
    }

    function get_form($value)
    {
        return $value == 'delete' ? $('#form-destroy') : $('#form-update');
    }

    $('body').on('click','.form-submit',function (e) {
        e.preventDefault();

        var $id = $(this).data('id'),
            $value = $(this).data('value'),
            $url = get_url($value)+'/'+$id,
            $type = $('#form-type').val($value),
            $data = get_form($value).serialize();

        sweet_alert($value, $url, $data);

        return false;
    });

});
</script>
@endsection