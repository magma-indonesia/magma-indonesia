@extends('layouts.default')

@section('title')
    Gunung Api | Laporan Letusan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" />
@endsection

@section('content-header')
<div class="normalheader content-boxed">
    <div class="row">
        <div class="col-lg-12 m-t-md">
            <h1 class="hidden-xs">
                <i class="icon icon-volcano-warning fa-2x text-danger"></i>
            </h1>
            <h1 class="m-b-md">
                <strong>Informasi Letusan (VEN)</strong>
            </h1>

            <div id="hbreadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('chambers.index') }}">MAGMA</a></li>
                    <li><a href="{{ route('chambers.index') }}">Gunung Api</a></li>
                    <li class="active"><a href="{{ route('chambers.letusan.index') }}">Informasi Letusan</a></li>
                </ol>
            </div>

            <p class="m-b-lg tx-16">
                Gunakan menu ini untuk membuat Informasi Laporan Letusan Gunung Api (Volcano Eruption Notice). Laporan yang dibuat di menu ini akan juga akan dipublikasikan di MAGMA v1 dan langsung dibuatkan <b>Draft VONA</b>. VONA yang dibuat tidak langsung dikirim, akan tetapi masih dalam bentuk Draft.
            </p>
            <div class="alert alert-danger">
                <i class="fa fa-gears"></i> Halaman ini masih dalam tahap pengembangan. Error, bug, maupun penurunan
                performa bisa terjadi sewaktu-waktu
            </div>
            @if (session('message'))
            <div class="alert alert-success">
                <i class="fa fa-check"></i> {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed no-top-padding">

    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body h-200">
                    <div class="stats-title pull-left">
                        <h4>Jumlah VEN</h4>
                    </div>

                    <div class="stats-icon pull-right">
                        <i class="icon icon-volcano-warning fa-4x text-danger"></i>
                    </div>

                    <div class="m-t-xl">
                        <h1>{{ $vens->count() }}</h1>
                        <p>
                            Jumlah VEN yang telah dibuat di MAGMA v2. Untuk v1 bisa dilihat di <a href="{{ route('chambers.v1.gunungapi.ven.index') }}">Informasi Letusan v1</a>
                        </p>
                        <h1>
                            <a href="{{ route('chambers.letusan.create') }}" class="btn btn-outline btn-danger"><i class="fa fa-plus"></i> Buat VEN</a>
                            <a href="#" class="btn btn-outline btn-danger"><i class="fa fa-download"></i> Download</a>
                        </h1>
                    </div>
                </div>

            </div>
        </div>

        @if ($vens->isNotEmpty())
        <div class="col-lg-6 col-xs-12">
            <div class="hpanel hred">
                <div class="panel-body">
                    <div class="stats-title pull-left">
                        <h4>Letusan Terkini</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="icon icon-volcano-warning fa-4x text-danger"></i>
                    </div>
                    <div class="m-t-xl">
                        <h1>{{ $vens->first()->gunungapi->name }}</h1>
                        <p>
                            Letusan terjadi pada <b>{{ $vens->first()->datetime_utc->formatLocalized('%A, %d %B %Y, %T WIB')}}</b>
                        </p>
                        <h1>
                            <a href="{{ route('chambers.letusan.show' ,['id' => $vens->first()->id ]) }}" class="btn btn-outline btn-info" type="button" target="_blank"><i class="fa fa-eye"></i> Lihat Informasi</a>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    @if ($vens->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">

                @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    <i class="fa fa-bolt"></i> {!! session('flash_message') !!}
                </div>
                @endif

                <div class="panel-body">
                    {{ $vens->links() }}
                    <div class="table-responsive">
                        <table id="table-ven" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gunung Api</th>
                                    <th>Waktu Kejadian (UTC)</th>
                                    <th>Visual</th>
                                    <th>Jenis letusan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vens as $key => $ven)
                                <tr>
                                    <td>{{ $vens->firstItem()+$key }}</td>
                                    <td>{{ $ven->gunungapi->name }}</td>
                                    <td>{{ $ven->datetime_utc }}</td>
                                    <td>{{ $ven->visibility ? 'Teramati' : 'Tidak teramati' }}</td>
                                    <td>{{ $ven->jenis == 'lts' ? 'Letusan' : 'Awan Panas Guguran' }}</td>
                                    <td>
                                        <a href="{{ route('chambers.letusan.show', $ven) }}"
                                        class="btn btn-sm btn-magma btn-outline" style="margin-right: 3px;">View</a>
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