@extends('layouts.default')

@section('title')
    VONA | Subscription
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
                        {{ $subs->links() }}
                        <div class="table-responsive">
                            <table id="table-jabatan" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        @role('Super Admin')
                                        <th>Status</th>
                                        <th>Action</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subs as $key => $sub)
                                    <tr>
                                        <td>{{ $subs->firstItem()+$key }}</td>
                                        <td>{{ $sub->name ?? 'Guest' }}</td>
                                        <td>{{ $sub->email }}</td>
                                        @role('Super Admin')
                                        <td>{!! $sub->status ? '<span class="label label-magma">Subscribe</span>' : '<span class="label label-danger">Unsubscribe</span>'!!}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">Edit</a>
                                            <form id="deleteForm" style="display:inline" method="POST" action="#" accept-charset="UTF-8">
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
                        {{ $subs->links() }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection