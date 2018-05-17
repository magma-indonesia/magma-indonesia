@extends('layouts.default')

@section('title')
    VONA | Volcano Observatory Notice for Aviation
@endsection

@section('add-vendor-css')
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
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        VONA
                    </div>
                    <div class="panel-body float-e-margins m-b">
                        <div class="row text-center">
                            <div class="col-md-4 col-lg-2 col-sm-6 col-xs-12">
                                <a href="{{ route('chambers.vona.create') }}" class="btn btn-outline btn-block btn-success" type="button">Buat VONA Baru</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ $vonas->links() }}
                        <div class="table-responsive">
                            <table id="table-jabatan" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Volcano</th>
                                        <th>Issued (UTC)</th>
                                        <th>Current Aviation Colour Code</th>
                                        <th>Volcano Cloud Height (ASL)</th>
                                        <th>Sender</th>
                                        @role('Super Admin')
                                        <th>Action</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vonas as $key => $vona)
                                    <tr>
                                        <td>{{ $vonas->firstItem()+$key }}</td>
                                        <td>{{ $vona->gunungapi->name }}</td>
                                        <td>{{ $vona->issued }}</td>
                                        <td>{{ title_case($vona->cu_code) }}</td>
                                        <td>{{ $vona->vch_asl.' meter' }}</td>
                                        <td>{{ $vona->user->name }}</td>
                                        @role('Super Admin')
                                        <td>
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.vona.destroy',['uuid'=>$vona->uuid]) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <a href="{{ route('chambers.vona.edit',['uuid'=>$vona->uuid]) }}" class="btn btn-sm btn-success btn-outline" style="margin-right: 3px;">Edit</a>                                            
                                                <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
                                        </td>
                                        @endrole                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $vonas->links() }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            $('body').on('submit','#deleteForm',function (e) {
                e.preventDefault();                

                var $url = $(this).attr('action'),
                    $data = $(this).serialize();

                // var $tableuser = $('#table-users').DataTable();
                // var $row = $tableuser.row($(this).parents('tr'));

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
                                    location.reload();
                                    // $row.remove().draw();
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
                                l.stop();
                                $label.html('Error. Coba lagi?');
                            }
                        });
                    }
                });

                return false;
            });
        });
    </script>
@endsection