@extends('layouts.default')

@section('title')
VONA | Volcano Observatory Notice for Aviation
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
                        <a href="{{ route('chambers.vona.index') }}">VONA</a>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Daftar VONA
            </h2>
            <small>Daftar VONA yang pernah dibuat dan dikirim kepada stakeholder terkait.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content">

    @if ($vonas->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    VONA
                </div>
                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.vona.create') }}" class="btn btn-outline btn-block btn-magma" type="button">Buat VONA Baru</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar VONA Terkirim - ({{ $vonas->count() }})
                </div>
                {{ $vonas->links() }}
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-vona" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Volcano</th>
                                    <th>Issued (UTC)</th>
                                    <th>Noticenumber</th>
                                    <th>Current/Previous Color</th>
                                    <th>Ash Cloud Height</th>
                                    <th>Status</th>
                                    <th>Sender</th>
                                    <th data-orderable="false" style="min-width: 180px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vonas as $key => $vona)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $vona->type == 'REAL' ? 'Real' : 'Exercise' }}</td>
                                    <td><a href="{{ route('chambers.vona.show',['uuid' => $vona->uuid])}}" target="_blank">{{ $vona->gunungapi->name }}</a></td>
                                    <td>{{ $vona->issued }}</td>
                                    <td>{{ $vona->noticenumber }}</td>
                                    <td>{{ $vona->current_code }}/{{ strtolower($vona->previous_code) }}</td>
                                    <td>{{ $vona->ash_height > 0 ? $vona->ash_height.' meter' : 'Tidak teramati' }}</td>
                                    <td>{{ $vona->is_sent ? 'Published' : 'Draft' }}</td>
                                    <td>{{ $vona->user->name }}</td>
                                    <td>
                                        <a href="{{ route('chambers.vona.show',['uuid'=>$vona->uuid]) }}" class="m-t-xs m-b-xs btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                        @role('Super Admin')
                                        <a href="{{ route('chambers.vona.edit',['uuid'=>$vona->uuid]) }}" class="m-t-xs m-b-xs btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.vona.destroy',['uuid'=>$vona->uuid]) }}" accept-charset="UTF-8">
                                            @method('DELETE')
                                            @csrf
                                            <button value="Delete" class="m-t-xs m-b-xs btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                        </form>
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
    @else
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> VONA belum pernah dibuat. <a href="{{ route('chambers.vona.create') }}"><b>Buat baru?</b></a>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('add-vendor-script')
<!-- DataTables -->
<script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- DataTables buttons scripts -->
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
    $('#table-vona').dataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[30, 60, 100, -1], [30, 60, 100, "All"]],
        buttons: [
            { extend: 'csv', title: 'Vonas', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5,6 ]} },
            { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6 ]} }
        ]
    });

    $('.click-here').click(function() {
        window.open($(this).data('href'),'_blank');
    });

    $('body').on('submit','#deleteForm',function (e) {
        e.preventDefault();

        var $url = $(this).attr('action'),
            $data = $(this).serialize();

        swal({
            title: "Anda yakin?",
            text: "Data yang telah dihapus tidak bisa dikembalikan",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, hapus!",
            cancelButtonText: "Gak jadi deh!",
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
                            swal("Berhasil!", data.message, "success");
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
                        swal("Gagal!", data.responseJSON.exception, "error");
                    }
                });
            }
        });

        return false;
    });
});
</script>
@endsection