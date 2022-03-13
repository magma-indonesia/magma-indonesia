@extends('layouts.default')

@section('title')
Database Migrations
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/fooTable/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
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
                    <li class="active">
                        <a href="{{ route('chambers.migration.index') }}">Migrations</a>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Database Migration
            </h2>
            <small>Daftar migrasi database.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-migration" class="table table-striped" data-sorting="true" data-expand-first="true" data-page-size="10" data-paging="true" data-paging-limit="10" data-filtering="true" data-filter-placeholder="Cari..." data-filter-position="left">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Migration</th>
                                    <th>Batch</th>
                                    <th data-sortable="false" data-title="Action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($migrations as $key => $migration)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $migration->migration }}</td>
                                    <td>{{ $migration->batch }}</td>
                                    <td>
                                    <a class="btn btn-sm btn-danger btn-outline m-b-xs form-submit" data-id="{{ $migration->id }}" type="button" title="Delete" data-value="delete"><i class="fa fa-trash"></i> Delete</a>
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

<form id="form-destroy" style="display:none;" method="POST" data-action="{{ route('chambers.migration.index') }}">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/fooTable/dist/footable.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function() {
    $('#table-migration').footable();

    function sweet_alert($url, $data)
    {
        var title = 'Anda Yakin?',
            text = 'Data yang telah dihapus tidak bisa dikembalikan',
            confirm_button = 'Yes, hapus!';

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

    $('body').on('click','.form-submit',function (e) {
        e.preventDefault();

        var $id = $(this).data('id'),
            $url = $('#form-destroy').data('action')+'/'+$id,
            $data = $('#form-destroy').serialize();

        sweet_alert($url, $data);

        return false;
    });
});
</script>
@endsection