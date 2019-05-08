@extends('layouts.default')

@section('title')
    Stakeholder
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
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
                            <a href="{{ route('chambers.stakeholder.index') }}">Stakeholder</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Stakeholder 
                </h2>
                <small>Daftar Stakeholder untuk akses MAGMA.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent

                <div class="hpanel">
                    <div class="panel-heading">
                        Stakeholder
                    </div>
                    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <i class="fa fa-bolt"></i> Aplikasi {!! session('flash_message') !!}
                    </div>
                    @endif
                    <div class="panel-body">
                        <div class="text-left">
                            <a href="{{ route('chambers.stakeholder.create') }}" type="button" class="btn btn-magma btn-outline">Tambah Stakeholder</a>
                        </div>
                        <hr>
                        <div class="text-center">
                        {{ $stakeholders->links() }}
                        </div>
                        <div class="table-responsive">
                            <table class="table" cellspacing="1" cellpadding="1">
                                <thead>
                                <tr>
                                    <th>Nama Aplikasi</th>
                                    <th>Organisasi</th>
                                    <th>Nama Pemohon</th>
                                    <th>No. HP</th>
                                    <th>Email</th>
                                    <th>Tipe API</th>
                                    <th>App ID</th>
                                    <th>Secret Key</th>
                                    <th>Status</th>
                                    <th>Tanggal Expired</th>
                                    <th style="min-width: 180px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stakeholders as $stakeholder)
                                    <tr>
                                        <td>{{ $stakeholder->app_name }}</td>
                                        <td>{{ $stakeholder->organisasi }}</td>
                                        <td>{{ $stakeholder->kontak_nama }}</td>
                                        <td>{{ $stakeholder->kontak_phone ?: 'Tidak ada' }}</td>
                                        <td>{{ $stakeholder->kontak_email ?: 'Tidak ada' }}</td>
                                        <td>{{ ucfirst($stakeholder->api_type) }}</td>
                                        <td>{{ $stakeholder->uuid }}</td>
                                        <td>{{ $stakeholder->secret_key }}</td>
                                        <td>{{ $stakeholder->status ? 'Aktif' : 'Tidak Aktif' }}</td>
                                        <td>{{ $stakeholder->expired_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('chambers.stakeholder.edit',['id'=>$stakeholder->id]) }}" class="m-t-xs m-b-xs btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>
                                            <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.stakeholder.destroy',['id'=>$stakeholder->id]) }}" accept-charset="UTF-8">
                                                @method('DELETE')
                                                @csrf
                                                <button value="Delete" class="m-t-xs m-b-xs btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                            </form>
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
@endsection

@section('add-vendor-script')
    <script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            $('#json-renderer').jsonViewer(@json($stakeholders), {collapsed: true});
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
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    setTimeout(function(){
                                        location.reload();
                                    },2000);
                                }
                            }
                        });
                    }
                });

                return false;
            });
        });
    </script>
@endsection