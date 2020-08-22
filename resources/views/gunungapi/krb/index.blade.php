@extends('layouts.default')

@section('title')
    Daftar Peta KRB Gunung Api
@endsection

@section('add-vendor-css')
@role('Super Admin')
<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endrole
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
@endsection

@section('content-header')
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                    <li>
                        <span>MAGMA v1</span>
                    </li>
                    <li>
                        <span>Gunung Api</span>
                    </li>
                    <li class="active">
                        <span>Peta KRB</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Daftar Peta KRB Gunung Api
            </h2>
            <small>Meliputi data seluruh Peta KRB Gunung Api Terkini</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">
    @hasanyrole('Super Admin|GIS Admin')
    <div class="row">
        <div class="col-lg-12">
            @if ($home_krbs->isEmpty())
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Belum ada Service ArcGis yang dibuat. Tambahkan sekarang pada menu di bawah ini</a>
            </div>

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Service ArcGis yang pernah dibuat
                </div>

                <div class="panel-body">
                    <form action="{{ route('chambers.home-krb.store') }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Tambahkan Link service ArcGis untuk Peta KRB Gunung Api</strong>, service yang digunakan adalah yang dibuat terkini.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

                                            @if ($errors->any())
                                            <div class="form-group col-sm-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12">
                                                <label>URL Service ArcGis</label>
                                                <p>Contoh: https://services5.arcgis.com/h3r17ndRvhy4NFDq/arcgis/rest/services/KRB_Gunung_Api/FeatureServer/0</p>
                                                <input name="url" class="form-control" type="text" value="{{ old('url') }}">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Tanggal Expired</label>
                                                <input name="expired_at" id="date" class="form-control" type="text" value="{{ now()->format('Y-m-d') }}">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Tambahkan URL</button>
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
            @else

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Service ArcGis yang pernah dibuat
                </div>

                <div class="panel-body float-e-margins">
                    <form action="{{ route('chambers.home-krb.store') }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Tambahkan Link service ArcGis untuk Peta KRB Gunung Api</strong>, service yang digunakan adalah yang dibuat terkini.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="row p-md">

                                            @if ($errors->any())
                                            <div class="form-group col-sm-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group col-sm-12">
                                                <label>URL Service ArcGis</label>
                                                <p>Contoh: https://services5.arcgis.com/h3r17ndRvhy4NFDq/arcgis/rest/services/KRB_Gunung_Api/FeatureServer/0</p>
                                                <input name="url" class="form-control" type="text" value="{{ old('url') }}">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Tanggal Expired</label>
                                                <input name="expired_at" id="date" class="form-control" type="text" value="{{ now()->format('Y-m-d') }}">
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="m-t-xs">
                                                    <button class="btn btn-primary" type="submit">Tambahkan URL</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-krb" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>URL</th>
                                    <th>Dibuat</th>
                                    <th>Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($home_krbs as $key => $home_krb)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $home_krb->url }}</td>
                                    <td>{{ $home_krb->created_at }}</td>
                                    <td>{{ $home_krb->expired_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endhasanyrole

    <div class="row">
        <div class="col-lg-12">
            {{-- Peta KRB --}}
            @if ($krbs->isEmpty())
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Belum ada Peta KRB yang diupload. <a href="{{ route('chambers.krb-gunungapi.create') }}"><b>Upload peta baru? ?</b></a>
            </div>
            @else

            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Peta KRB Gunung Api
                </div>

                <div class="panel-body float-e-margins">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.krb-gunungapi.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Upload Peta KRB</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-krb" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Preview</th>
                                    <th>Size</th>
                                    <th>Published</th>
                                    <th width="30%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($krbs as $key => $krb)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $krb->gunungapi->name }}</td>
                                    <td><a href="{{ $krb->medium_url }}" data-lightbox="file-set-{{ $key }}"data-title="{{ $krb->gunungapi->name.'_'.($key+1) }}"><img class="img-fluid" src="{{ $krb->thumbnail }}" alt="" /></a></td>
                                    <td>{{ $krb->size_mb }}</td>
                                    <td>{{ $krb->published ? 'Ya' : 'Tidak' }}</td>
                                    <td>
                                        <a href="{{ $krb->url }}" class="btn btn-sm btn-info btn-outline m-b-sm" type="button" download="Original">Original</a>
                                        <a href="{{ $krb->large_url }}" class="btn btn-sm btn-info btn-outline m-b-sm" type="button" download="Large">Large</a>
                                        <a href="{{ $krb->medium_url }}" class="btn btn-sm btn-info btn-outline m-b-sm" type="button" download="Medium">Medium</a>
                                        <a class="btn btn-sm btn-success btn-outline m-b-sm form-submit" data-id="{{ $krb->id }}" type="button" title="Aktivasi" data-value="1">Publish</a>
                                        <a class="btn btn-sm btn-danger btn-outline m-b-sm form-submit" data-id="{{ $krb->id }}" type="button" title="Deaktivasi" data-value="0">Unpublish</a>
                                        @role('Super Admin')
                                        <form id="deleteForm" style="display:inline" method="POST" action="{{ route('chambers.krb-gunungapi.destroy', $krb) }}" accept-charset="UTF-8">
                                            @method('DELETE')
                                            @csrf
                                            <button value="Delete" class="btn btn-sm btn-danger btn-outline delete m-b-sm" type="submit">Delete</button>
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
            @endif
        </div>
    </div>
</div>

<form id="form-update" style="display:none;" method="POST" data-action="{{ route('chambers.krb-gunungapi.index') }}">
    @csrf
    @method('PUT')
    <input id="form-type" type="hidden" name="published" value="0">
</form>
@endsection

@section('add-vendor-script')
@role('Super Admin')
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endrole
<script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {
    function sweet_alert($value, $url, $data)
    {
        var title = 'Anda Yakin?',
            text = 'Data yang telah dihapus tidak bisa dikembalikan',
            confirm_button = 'Yes, hapus!';

        if ($value == '0') {
            title = 'Unpublish Informasi?';
            text = 'Informasi yang ditarik tidak akan tampil di MAGMA';
            confirm_button = 'Yes, Unpublish!';
        }

        if ($value == '1') {
            title = 'Publish Informasi?';
            text = 'Informasi yang di-publish akan tampil di MAGMA';
            confirm_button = 'Yes, Publish!';
        }

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

    function get_url($value)
    {
        return $value == 'delete' ? $('#form-destroy').data('action') : $('#form-update').data('action');
    }

    function get_form($value)
    {
        return $value == 'delete' ? $('#form-destroy') : $('#form-update');
    }

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

    $('body').on('click','.form-submit',function (e) {
        e.preventDefault();

        var $id = $(this).data('id'),
            $value = $(this).data('value'),
            $url = get_url($value)+'/'+$id,
            $type = $('#form-type').val($value),
            $data = get_form($value).serialize();

        sweet_alert($value, $url, $data);

        return false;
    });

    $.fn.datepicker.dates['id'] = {
        days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        daysMin: ['Mi', 'Se', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
        months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        today: 'Hari ini',
        clear: 'Bersihkan',
        format: 'yyyy-mm-dd',
        titleFormat: 'MM yyyy',
        weekStart: 1
    };

    $('#date').datepicker({
        startDate: '2015-05-01',
        endDate: '{{ now()->addYears(2)->format('Y-m-d') }}',
        language: 'id',
        todayHighlight: true,
        todayBtn: 'linked',
        enableOnReadonly: true,
        minViewMode: 0,
        maxViewMode: 2,
        readOnly: true,
    });
});
</script>
@endsection
