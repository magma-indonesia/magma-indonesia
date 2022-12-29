@extends('layouts.default')

@section('title')
Add Tag
@endsection

@section('content-header')
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li>
                        <a href="{{ route('chambers.index') }}">Chambers</a>
                    </li>
                    <li class="active">
                        <span>Add Tag</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Form untuk menabahkan Tag Press Release
            </h2>
            <small>Tag digunakan juga untuk pengkategorian berita</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel content-boxed">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Edit {{ $tag->name }}
                </div>

                <div class="panel-body">

                   @if ($errors->any())
                    <div class="form-group col-sm-12">
                        <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                        </div>
                    </div>
                    @endif

                    <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.tag.update', $tag) }}">
                        @csrf
                        @method('PUT')
                        <div class="tab-content">
                            <div class="p-m tab-pane active">

                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            Rubah Tag "{{ $tag->name }}"
                                        </p>
                                    </div>

                                    <div class="col-lg-8">

                                        {{-- Email --}}
                                        <div class="form-group col-sm-12">
                                            <label>Nama Tag</label>
                                            <input name="name" class="form-control" type="text" value="{{ old('name') ?: $tag->name }}" required>

                                            @if( $errors->has('name'))
                                            <label class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                                            @endif
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="hr-line-dashed"></div>

                                                    <button class="btn btn-primary" type="submit">Update Tag</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection