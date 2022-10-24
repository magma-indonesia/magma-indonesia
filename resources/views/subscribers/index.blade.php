@extends('layouts.default')

@section('title')
VONA | Subscription
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
                    <li>
                        <a href="{{ route('chambers.vona.index') }}">VONA</a>
                    </li>
                    <li>
                        <a href="{{ route('chambers.subscribers.index') }}">Subscription</a>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Daftar VONA Subscribers
            </h2>
            <small>Daftar VONA Subscribers yang pernah dibuat dan dikirim kepada stakeholder terkait.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Email Subscription
                </div>
                <div class="panel-body">
                    <div class="row text-center">
                        <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                        <a href="{{ route('chambers.subscribers.create')}}" class="btn btn-outline btn-block btn-magma" type="button">Tambah Subscriber</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hpanel">
                <div class="panel-body">

                    <div class="table-responsive">
                        <table id="table-subs" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Group</th>
                                    @role('Super Admin')
                                    <th>Status</th>
                                    <th>Action</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subs as $key => $sub)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $sub->email }}</td>
                                    <td>{{ $sub->real ? 'Real, ' : '' }} {{ $sub->exercise ? 'Exercise, ' : '' }} {{ $sub->pvmbg ? 'PVMBG, ' : '' }}</td>
                                    @role('Super Admin')
                                    <td>{!! $sub->status ? '<span class="label label-magma">Subscribed</span>' : '<span class="label label-danger">Unsubscribed</span>'!!}</td>
                                    <td>
                                        <a href="{{ route('chambers.subscribers.edit', ['id' => $sub ]) }}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">Edit</a>
                                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.subscribers.destroy',['id' => $sub->id ]) }}" accept-charset="UTF-8">
                                            @method('DELETE')
                                            @csrf
                                            <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                        </form>
                                    </td>
                                    @endrole
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
@endsection

@section('add-vendor-script')
<!-- DataTables -->
<script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- DataTables buttons scripts -->
<script src="{{ asset('vendor/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    // Initialize table
    $('#table-subs').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[30, 60, 100, -1], [30, 60, 100, "All"]],
        buttons: [
            { extend: 'copy', className: 'btn-sm'},
            { extend: 'csv', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
            { extend: 'pdf', title: 'Daftar Users', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} },
            { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ]} }
        ]
    });
});
</script>
@endsection