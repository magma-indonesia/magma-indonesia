@extends('layouts.default')

@section('title')
    Draft Laporan Gunung Api
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
                        <li>
                            <a href="{{ route('chambers.laporan.index') }}">Gunung Api</a>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.draft.index') }}">Draft Laporan</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru.</small>
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
                    Draft Laporan Gunung Api
                </div>
                <div class="panel-body">
                    <div class="text-center m-b-md" id="wizardControl">
                        <a class="btn btn-primary m-b" href="{{ route('chambers.laporan.create.var') }}">Buat Lapoan Gunung Api (VAR)</a>   
                    </div>
                    <hr>
                    <div class="tab-content">
                        <div id="step1" class="p-m tab-pane active">
                            <div class="row">
                                <div class="col-lg-3 text-center">
                                    <i class="pe-7s-note fa-4x text-muted"></i>
                                    <p class="m-t-md">
                                        <strong>Draft Laporan Gunung Api</strong>, menampilkan beberap draft laporan gunung api yang pernah dibuat namun belum sempat terkirim.
                                    </p>
                                </div>
                                <div class="col-lg-9">
                                    {{ $drafts->links() }}
                                    @if ($errors->any())
                                    <div class="row m-b-md">
                                        <div class="col-lg-12">
                                            <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                <p>{!! $error !!}</p>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table-draft" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Gunung Api</th>
                                                            <th>Tanggal Laporan</th>
                                                            <th>Periode</th>
                                                            <th>Pelapor</th>
                                                            <th>Last Update</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($drafts as $key => $draft)
                                                        <tr>
                                                            <td>{{ $drafts->firstItem()+$key }}</td>
                                                            <td>{{ $draft->gunungapi->name }}</td>
                                                            <td>{{ $draft->var['var_data_date'] }}</td>
                                                            <td>{{ $draft->var['var_perwkt'].', Jam, '.$draft->var['periode'] }}</td>
                                                            <td>{{ $draft->user->name }}</td>
                                                            <td>{{ $draft->updated_at->format('Y-m-d H:i:s') }}</td>
                                                            <td>
                                                                <a href="{{ route('chambers.draft.show',['noticenumber' => $draft->noticenumber])}}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
                                                                @if(auth()->user()->nip == $draft->nip_pelapor || auth()->user()->hasRole('Super Admin'))
                                                                <a href="{{ route('chambers.laporan.preview.magma.var',['noticenumber' => $draft->noticenumber])}}" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">Edit</a>
                                                                <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.draft.destroy',['noticenumber' => $draft->noticenumber]) }}" accept-charset="UTF-8">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button value="Delete" class="m-t-xs m-b-xs btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                                                </form>
                                                                @endif
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
<script>
    $(document).ready(function () {
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
    })
</script>
@endsection