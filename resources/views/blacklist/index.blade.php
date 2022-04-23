@extends('layouts.default')

@section('title')
Blacklist
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
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
                        <a href="{{ route('chambers.blacklist.index') }}">Blacklist</a>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Blacklist
            </h2>
            <small>Daftar Blacklist IP.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">

    @if ($diffs->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar yang belum masuk blacklist
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IP Address</th>
                                    <th>Hit</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diffs as $key => $diff)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $diff->ip_address }}</td>
                                    <td>{{ $diff->hit }}</td>
                                    <td>{{ $diff->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $diff->updated_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-magma btn-outline m-b-xs add-submit"
                                            data-ip="{{ $diff->ip_address }}" type="button" title="Add"
                                            data-value="add"><i class="fa fa-plus"></i> Add</a>
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
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Blacklist
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-blacklist" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IP Address</th>
                                    <th>Hit</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blacklists->where('hit','>',0) as $key => $blacklist)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $blacklist->ip_address }}</td>
                                    <td>{{ $blacklist->hit }}</td>
                                    <td>{{ optional($blacklist->created_at)->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $blacklist->updated_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-danger btn-outline m-b-xs form-submit" data-id="{{ $blacklist->id }}" type="button" title="Delete" data-value="delete"><i class="fa fa-trash"></i> Delete</a>
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

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Access
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-access" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IP Address</th>
                                    <th>Hit</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th>Hit/hour</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accesses as $key => $access)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $access->ip_address }}</td>
                                    <td>{{ $access->hit }}</td>
                                    <td>{{ $access->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $access->updated_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ round($access->hit/$access->created_at->diffInHours($access->updated_at)) }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-magma btn-outline m-b-xs add-submit"
                                            data-ip="{{ $access->ip_address }}" type="button" title="Add"
                                            data-value="add"><i class="fa fa-plus"></i> Add</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </dvi>

<form id="form-destroy" style="display:none;" method="POST" data-action="{{ route('chambers.blacklist.index') }}">
    @csrf
    @method('DELETE')
</form>

<form id="form-add" style="display:none;" method="POST" data-action="{{ route('chambers.blacklist.store') }}">
    @csrf
    <input id="ip" name="ip" value="">
</form>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function() {

    $('#table-blacklist').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [
            { extend: 'copy', className: 'btn-sm'},
            { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3 ]} },
            { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3 ]} },
            { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3 ]} }
        ]

    });

    $('#table-access').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [
            { extend: 'copy', className: 'btn-sm'},
            { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3 ]} },
            { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3 ]} },
            { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3 ]} }
        ]

    });

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

    function sweet_alert_add($url, $data)
    {
        var title = 'Anda Yakin?',
            text = 'IP address akan ditambahkan ke dalam daftar blacklist',
            confirm_button = 'Iya, yakin!';

        swal({
            title: title,
            text: text,
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#007fff',
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

    $('body').on('click','.add-submit',function (e) {
        e.preventDefault();

        var $id = $(this).data('ip'),
            $ip = $('#ip').val($id),
            $url = $('#form-add').data('action'),
            $data = $('#form-add').serialize();

        sweet_alert_add($url, $data);

        return false;
    });
});
</script>
@endsection