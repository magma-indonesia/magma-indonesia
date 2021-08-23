@extends('layouts.default')

@section('title')
Event Catalog
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
                Daftar Event Catalog Kejadian Gempa
            </h2>
            <small class="font-light"> Caalog event gempa ini dapat digunakan untuk melakukan relokasi gempa</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">

<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-body float-e-margins">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                        <a href="{{ route('chambers.event-catalog.create') }}" class="btn btn-magma btn-outline btn-block" type="button">Tambah Event Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@if ($eventCatalogs->isNotEmpty())
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-heading">
                Daftar Katalog Gempa
            </div>

            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
            @endforeach
            @endif

            @if(Session::has('flash_event'))
            <div class="alert alert-success">
                <i class="fa fa-bolt"></i> {!! session('flash_event') !!}
            </div>
            @endif

            <div class="panel-body float-e-margins">
                <form action="{{ route('chambers.event-catalog.index') }}" method="get">
                    <div class="row">
                        <div class="col-lg-4 text-center">
                            <i class="pe-7s-note fa-4x text-muted"></i>
                            <p class="m-t-md">
                                Filter data event catalog gempa yang tercatat di stasiun seismik gunung api.
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
                                    <label>Gunung Api</label>
                                    <select id="code" class="form-control" name="code">
                                        @foreach($gadds as $gadd)
                                        <option value="{{ $gadd->code }}"
                                            {{ old('code') == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label>Jenis Gempa</label>
                                    <div class="row">
                                        @foreach ($types->chunk(10) as $type)
                                        <div class="col-lg-6">
                                            @foreach ($type as $item)
                                            <div class="checkbox">
                                            <label><input name="types[]" value="{{ $item->code }}" type="checkbox" class="i-checks type" checked> {{ $item->name}}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label>Pilih Semua Gempa</label>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="i-checks all" checked> Check All</label>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label>Range Tanggal</label>
                                    <div class="input-group input-daterange">
                                        <input id="start_date" type="text" class="form-control" value="{{ now()->subDays(14)->format('Y-m-d') }}" name="start_date">
                                        <div class="input-group-addon"> - </div>
                                        <input id="end_date" type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" name="end_date">
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="m-t-xs">
                                        <button class="btn btn-primary" type="submit">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="panel-body">
                <div class="text-center">
                    {{ $eventCatalogs->links() }}
                </div>
                <div class="table-responsive m-t">
                    <table id="table-kesimpulan" class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gunung Api</th>
                                <th>Stasiun</th>
                                <th>Gempa</th>
                                <th>P-Arrival (UTC)</th>
                                <th>S-Arrival (UTC)</th>
                                <th>Durasi</th>
                                <th>Amplitude</th>
                                <th>Pelapor</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventCatalogs as $key => $eventCatalog)
                            <tr>
                                <td>{{ $eventCatalogs->firstItem()+$key }}</td>
                                <td>{{ $eventCatalog->gunungapi->name }}</td>
                                <td>{{ $eventCatalog->seismometer->scnl }}</td>
                                <td>{{ $eventCatalog->type->name }}</td>
                                <td>{{ $eventCatalog->p_datetime_utc }}</td>
                                <td>{{ $eventCatalog->s_datetime_utc ?? '-'}}</td>
                                <td>{{ $eventCatalog->duration }}</td>
                                <td>{{ $eventCatalog->maximum_amplitude }}</td>
                                <td>{{ $eventCatalog->user->name }}</td>
                                <td>
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
@endif
</div>
@endsection

@section('add-vendor-script')
<!-- DataTables buttons scripts -->
<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {

    $('.input-daterange').datepicker({
        startDate: '2015-05-01',
        endDate: '{{ now()->format('Y-m-d') }}',
        language: 'id',
        todayHighlight: true,
        todayBtn: 'linked',
        enableOnReadonly: false,
        format: 'yyyy-mm-dd',
    });

    $('.input-daterange input').each(function() {
        $(this).datepicker().on('changeDate', function(e){
            var startDate = $('#start_date').val(),
                endDate = $('#end_date').val(),
                isValid = Date.parse(startDate) <= Date.parse(endDate);
            console.log(isValid);
        });
    });

    var $checkAll = $('input.all'),
        $checkboxes = $('input.type');

    $checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            $checkboxes.iCheck('check');
        } else {
            $checkboxes.iCheck('uncheck');
        }
    });

    $checkboxes.on('ifChanged', function(event){
        if($checkboxes.filter(':checked').length == $checkboxes.length) {
            $checkAll.prop('checked', 'checked');
        } else {
            $checkAll.removeProp('checked');
        }
        $checkAll.iCheck('update');
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