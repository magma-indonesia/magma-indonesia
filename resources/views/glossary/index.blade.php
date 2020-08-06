@extends('layouts.default')

@section('title')
Glossary
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
                <strong>Glossary</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                    <li class="active">
                        <span>Glossary</span>
                    </li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Gunakan menu ini untuk menjelaskan suatu istilah yang biasa digunakan dalam mitigasi bencana geologi.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan performa bisa terjadi sewaktu-waktu
            </div>
            @if (session('message'))
            <div class="alert alert-success">
                <i class="fa fa-check"></i> {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed no-top-padding">
    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Glossary</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-note2 fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $glossaries->count() }}</h1>
                        <p>
                            Menu ini digunakan untuk melihat edukasi informasi apa yang pernah dibuat. 
                        </p>
                        <a href="{{ route('chambers.glossary.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Jumlah Informasi</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="pe-7s-info fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $edukasis_count }}</h1>
                        <p>
                            Menu ini digunakan untuk melihat edukasi informasi apa yang pernah dibuat. 
                        </p>
                        <a href="{{ route('chambers.edukasi.index') }}" class="btn btn-outline btn-info"> Daftar</a>
                        <a href="{{ route('chambers.edukasi.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-body">
                    <table id="table-edukasi" class="table table-striped" data-sorting="true" data-expand-first="true" data-page-size="10" data-paging="true" data-paging-limit="10" data-filtering="true" data-filter-placeholder="Cari..." data-filter-position="left">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th data-breakpoints="xs sm md" data-title="Deskripsi">Deskripsi</th>
                                <th data-sortable="false" data-breakpoints="xs sm md" data-title="Jumlah File(s)">Jumlah File(s)</th>
                                <th data-title="Published">Published</th>
                                <th data-sortable="false" data-breakpoints="xs sm md" data-title="Action" width="40%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($glossaries as $glossary)
                            <tr>
                                <td>{{ $glossary->judul }}</td>
                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($glossary->deskripsi), 100) }}</td>
                                <td>{{ $glossary->glossary_files_count }}</td>
                                <td>{{ $glossary->is_published ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a href="{{ route('chambers.glossary.show', $glossary->slug) }}" class="btn btn-sm btn-info btn-outline m-b-xs" type="button" title="View">View</a>
                                    <a href="{{ route('chambers.glossary.edit', $glossary) }}" class="btn btn-sm btn-warning btn-outline m-b-xs" type="button" title="Edit">Edit</a>
                                    <a class="btn btn-sm btn-success btn-outline m-b-xs m-l form-submit" data-id="{{ $glossary->id }}" type="button" title="Aktivasi" data-value="1">Publish</a>
                                    <a class="btn btn-sm btn-danger btn-outline m-b-xs m-r form-submit" data-id="{{ $glossary->id }}" type="button" title="Deaktivasi" data-value="0">Unpublish</a>
                                    @role('Super Admin')
                                    <a class="btn btn-sm btn-danger btn-outline m-b-xs form-submit" data-id="{{ $glossary->id }}" type="button" title="Delete" data-value="delete"><i class="fa fa-trash"></i> Delete</a>
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
</div>

<form id="form-update" style="display:none;" method="POST" data-action="{{ route('chambers.glossary.index') }}">
    @csrf
    @method('PUT')
    <input id="form-type" type="hidden" name="is_published" value="0">
</form>

@role('Super Admin')
<form id="form-destroy" style="display:none;" method="POST" data-action="{{ route('chambers.glossary.index') }}">
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
    $('#table-edukasi').footable();

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