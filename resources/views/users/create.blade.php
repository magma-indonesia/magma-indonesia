@extends('layouts.default')

@section('title')
Tambah Pegawai
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
                        <span>Pegawai</span>
                    </li>
                    <li class="active">
                        <span>Create </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Tambah Pegawai
            </h2>
            <small>Menu ini untuk digunakan untuk menambahkan pengguna MAGMA Indonesia</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="hpanel">
                <div class="panel-heading">
                    Input Data Dasar Pengguna
                </div>
                <div class="panel-body">
                    <p>Masukkan semua data-data yang dibutuhkan untuk menambahkan pengguna ke dalam MAGMA.</p>

                    <form role="form" id="form" method="POST" action="{{ route('chambers.users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input name="_type" value="base" type="hidden">
                        <div class="form-group">
                            <label>Nama</label>
                            <input name="name" type="text" placeholder="Masukkan Nama" class="form-control" value="{{ old('name') }}" required>
                            @if( $errors->has('name'))
                            <label id="name-error" class="error" for="name">{{ ucfirst($errors->first('name')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>NIP/KTP</label>
                            <input name="nip" type="text" placeholder="Masukkan NIP/KTP" class="form-control" value="{{ old('nip') }}"required>
                            @if( $errors->has('nip'))
                            <label id="nip-error" class="error" for="nip">{{ ucfirst($errors->first('nip')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input id="password" name="password" type="password" placeholder="Masukkan Password" class="form-control" required>
                            @if( $errors->has('password'))
                            <label id="password-error" class="error" for="password">{{ ucfirst($errors->first('password')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input name="password_confirmation" type="password" placeholder="Konfirmasi Password" class="form-control" required>
                            @if( $errors->has('password_confirmation'))
                            <label id="password_confirmation-error" class="error" for="password_confirmation">{{ ucfirst($errors->first('password_confirmation')) }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status User</label>
                            <div>
                                <label class="checkbox-inline">
                                <input name="status" class="i-checks" type="radio" value="1" id="status" checked> Aktif </label>
                                <label class="checkbox-inline">
                                <input name="status" class="i-checks" type="radio" value="0" id="status"> Tidak Aktif </label>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div>
                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>Submit</strong></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
<script>
	$(document).ready(function(){

        $("#form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 4
                },
                nip: {
                    required: true,
                    digits: true,
                    minlength: 16,
                    maxlength: 18
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                name: {
                    required: 'Harap Masukkan Nama Anda',
                    minlength: 'Minimal 4 karakter'
                },
                nip: {
                    required: 'Harap Masukkan NIP Anda',
                    digits: 'NIP hanya menerima dalam bentuk karakter angka saja',
                    minlength: 'Panjang karakter NIP adalah 18 karakter',
                    maxlength: 'Panjang karakter NIP adalah 18 karakter'
                },
                password: {
                    required: 'Password belum diisi',
                    minlength: 'Minimal 4 karakter'
                },
                password_confirmation: {
                    required: 'Konfirmasi Password tidak boleh kosong',
                    equalTo: 'Password tidak sama'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

    });

</script>
@endsection