@extends('layouts.default')

@section('title')
    Administrasi | Jabatan
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('nav-add-jabatan')
    <li class="{{ active('chambers.administratif.jabatan.create') }}">
        <a href="{{ route('chambers.administratif.jabatan.create') }}">Tambah Jabatan</a>
    </li>
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
                            <span>Administratif </span>
                        </li>
                        <li class="active">
                            <a href="{{ route('chambers.administratif.jabatan.index') }}">Jabatan</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Form Jabatan
                </h2>
                <small>Daftar Jabatan yang terdaftar di Pusat Vulkanologi dan Mitigasi Bencana Geologi.Digunakan untuk menambah kelas jabatan karyawan.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content content-boxed animate-panel"> 
        <div class="row">
            <div class="col-lg-offset-1 col-lg-10">
                <div class="hpanel">
                    <div class="panel-heading">
                        Tambah Jabatan Baru
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="post" action="{{ route('chambers.administratif.jabatan.store')}}">
                            @csrf
                            <label>Nama Jabatan</label>
                            @if (count($errors)==0)
                            <div class="form-group">
                                <div class="input-group">
                                    <input name="name[]" type="text" placeholder="Maksimal 100 Karakter" class="form-control" required>
                                    <span class="input-group-btn"> 
                                        <button type="button" class="btn btn-primary add-jabatan" style="width:40px;"> + </button>
                                    </span>
                                </div>
                            </div>
                            @else
                            @for($i = 0; $i < count(old('name')); $i++)
                            <div class="form-group">
                                <div class="input-group">
                                    <input name="name[]" type="text" placeholder="Maksimal 100 Karakter" class="form-control" value="{{ old('name.'.$i) }}" required>
                                    <span class="input-group-btn">
                                    @if($i>0)
                                        <button type="button" class="btn btn-danger remove-permission" style="width:40px;"> - </button>
                                    @else
                                        <button type="button" class="btn btn-primary add-permission" style="width:40px;"> + </button>                        
                                    @endif
                                    </span>
                                </div>
                                @if($errors->has('name.*'.$i))
                                <label id="name-error" class="error" for="name[]">{{ ucfirst($errors->first('name.'.$i)) }}</label>
                                @endif
                            </div>
                            @endfor
                            @endif
                            <div class="form-group">
                                <label>Tambahkan dalam Roles</label>
                                <div class="checkbox">
                                    <label class="checkbox-inline"> 
                                    <input name="roles" class="i-checks" type="radio" value="1" id="roles" {{ old('roles') == '1' || empty(old('status')) ? 'checked' : '' }}> Ya </label> 
                                    <label class="checkbox-inline">
                                    <input name="roles" class="i-checks" type="radio" value="0" id="roles" {{ old('roles') == '0' ? 'checked' : '' }}> Tidak </label>  
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>Submit</strong></button>                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(count($jabatans) > 0)
        <div class="row">
            <div class="col-lg-offset-1 col-lg-10">
                <div class="hpanel">
                    <div class="panel-heading">
                        Jabatan yang terdaftar
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="table-jabatan" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jabatans as $key => $jabatan)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $jabatan->nama }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables -->
    <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
<script>
    $(document).ready(function () {

        var t = $('#table-jabatan').DataTable({
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: [0]
            }],
            order: [[ 1, 'asc' ]],
            dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>tp",
                "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
        });

        t.on('order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        $('.add-jabatan').on('click', function () {

            var $ele = $(this).closest('.form-group').clone(true),
                $removePlus  = $ele.find('.input-group-btn').remove(),
                $remove = '<span class="input-group-btn"><button type="button" class="btn btn-danger remove-jabatan" style="width:40px;"> - </button></span>',
                $addRemove = $ele.find('input').after($remove);
            
            $(this).closest('.form-group').after($ele);

        });

        $('form').on('click','.remove-jabatan',function(){
            $(this).closest('.form-group').remove();
        });

        $("#form").validate({
            errorPlacement: function(error, element) {
                console.log(element);
                if (element.attr('name') == 'name[]' ){
                    $(element).closest('.input-group').after(error);
                }
            },
            ignore: [],
            rules: {
                'name[]': {
                    required: true,
                    maxlength: 100
                }
            },
            messages: {
                'name[]': {
                    required: 'Nama Jabatan tidak boleh kosong',
                    minlength: 'Maksimal 100 karakter'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

    });
</script>
@endsection