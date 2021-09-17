@extends('layouts.default')

@section('title')
Login Statistik
@endsection

@section('add-vendor-css')
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endrole
<link rel="stylesheet"
    href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('content-header')
<div class="content content-boxed normalheader">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                Data Login User
            </h2>
            <small class="font-light"> Informasi keterangan login user MAGMA Indonesia.</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">

@if ($logins->isNotEmpty())
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-heading">
                Daftar Login
            </div>

            <div class="panel-body">
                <div class="text-center">
                    {{ $logins->links() }}
                </div>

                <div class="table-responsive m-t">
                    <table id="table-login" class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIP</th>
                                <th>Tanggal</th>
                                <th>Hit</th>
                                {{-- <th width="20%">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logins as $key => $login)
                            <tr>
                                <td>{{ $logins->firstItem()+$key }}</td>
                                <td>{{ $login->user->name }}</td>
                                <td>{{ $login->date->format('Y-m-d') }}</td>
                                <td>{{ $login->hit }}</td>
                                {{-- <td>
                                    @if (auth()->user()->nip === $eventCatalog->nip || auth()->user()->hasRole('Super Admin'))
                                    <a href="{{ route('chambers.event-catalog.edit', $eventCatalog) }}" class="btn btn-sm btn-warning btn-outline" style="margin-right: 3px;">Edit</a>

                                    <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.event-catalog.destroy', $eventCatalog) }}" accept-charset="UTF-8">
                                        @method('DELETE')
                                        @csrf
                                        <button value="Delete" class="btn btn-sm btn-danger btn-outline delete" type="submit">Delete</button>
                                    </form>
                                    @else
                                    -
                                    @endif
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    {{ $logins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

</div>
@endsection

@section('add-vendor-script')
<!-- DataTables buttons scripts -->
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection