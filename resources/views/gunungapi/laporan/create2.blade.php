@extends('layouts.default')

@section('title')
    VAR - Step 2
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
                            <a href="{{ route('chambers.laporan.create.2') }}">Buat VAR - Step 2</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                   Form laporan MAGMA-VAR (Volcanic Activity Report)
                </h2>
                <small>Buat laporan gunung api terbaru Step 2 - Input data visual.</small>
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
                        Form MAGMA-VAR
                    </div>
                    <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ route('chambers.laporan.store.2')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center m-b-md" id="wizardControl">
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 1 - <span class="hidden-xs">Data Laporan</span></a>
                                <a class="btn btn-primary" href="#">Step 2 - Data Visual</a>
                                <a class="btn btn-default hidden-xs" href="#" disabled>Step 3 - Data Kegempaan</a>
                            </div>
                            <hr>
                            <div class="tab-content">
                                <div id="step2" class="p-m tab-pane active">
        
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <i class="pe-7s-camera fa-4x text-muted"></i>
                                            <p class="m-t-md">
                                                <strong>Buat Laporan MAGMA-VAR</strong>, form ini digunakan untuk input data laporan gunung api, meliputi laporan visual dan instrumental.
                                                <br/><br/>Semua laporan yang dibuat akan dipublikasikan secara realtime melalui aplikasi <strong>MAGMA Indonesia</strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-9">
                                            
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
                                                <div class="form-group col-lg-12">
                                                    <label>Nama Pembuat</label>
                                                    <input type="text" value="{{ auth()->user()->name }}" id="name" class="form-control" name="name" placeholder="Nama Pembuat Laporan" disabled>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label {{$errors->has('visibility') ? 'class=text-danger' : ''}}>Visual Gunung Api</label>
                                                    <div class="checkbox">
                                                        <label><input name="visibility[]" value="Jelas" type="checkbox" class="i-checks" {{ (is_array(old('visibility')) AND in_array('Jelas',old('visibility'))) || in_array('Jelas',$visual['visibility']) || empty(old('visibility')) ? 'checked' : ''}}> Terlihat Jelas </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="visibility[]" value="Kabut 0-I" type="checkbox" class="i-checks" {{ (is_array(old('visibility')) AND in_array('Kabut 0-I',old('visibility'))) || in_array('Kabut 0-I',$visual['visibility']) ? 'checked' : ''}}> Tertutup Kabut 0-I </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="visibility[]" value="Kabut 0-II" type="checkbox" class="i-checks" {{ (is_array(old('visibility')) AND in_array('Kabut 0-II',old('visibility'))) || in_array('Kabut 0-II',$visual['visibility'])? 'checked' : ''}}> Tertutup Kabut 0-II </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input name="visibility[]" value="Kabut 0-III" type="checkbox" class="i-checks" {{ (is_array(old('visibility')) AND in_array('Kabut 0-III',old('visibility'))) || in_array('Kabut 0-III',$visual['visibility'])? 'checked' : ''}}> Tertutup Kabut 0-III </label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label {{$errors->has('visual_asap') ? 'class=text-danger' : ''}}>Visual Asap Kawah</label>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="visual_asap" value="Nihil" type="radio" class="i-checks asap" {{ (old('visual_asap') == 'Nihil') || ($visual['visual_asap'] == 'Nihil') || empty(old('visual_asap')) ? 'checked' : ''}}> Nihil </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="visual_asap" value="Teramati" type="radio" class="i-checks asap" {{ old('visual_asap') == 'Teramati' || ($visual['visual_asap'] == 'Teramati') ? 'checked' : ''}}> Teramati </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="visual_asap" value="Tidak Teramati" type="radio" class="i-checks asap" {{ old('visual_asap') == 'Tidak Teramati' || ($visual['visual_asap'] == 'Tidak Teramati') ? 'checked' : ''}}> Tidak Teramati </label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label {{$errors->has('hasfoto') ? 'class=text-danger' : ''}}>Foto Visual</label>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="hasfoto" value="1" type="radio" class="i-checks hasfoto" {{ (old('hasfoto') == '1') || ($visual['hasfoto'] == '1') || empty(old('hasfoto')) ? 'checked' : ''}}> Ada </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label class="checkbox-inline"><input name="hasfoto" value="0" type="radio" class="i-checks hasfoto" {{ old('hasfoto') == '0' || ($visual['hasfoto'] == '0') ? 'checked' : ''}}> Tidak Ada </label>
                                                    </div>
                                                </div>

                                                {{-- Extended Foto Visual --}}
                                                <div class="foto-visual" style="{{ old('hasfoto') == '0' || $visual['hasfoto'] == '0' ? 'Display:none' : ''}}">
                                                    <div class="form-group col-lg-12">
                                                        <label {{$errors->has('foto') || $errors->has('foto') ? 'class=text-danger' : ''}}>Upload Foto Visual</label>
                                                        <div class="form-group">
                                                            <img class="img-responsive border-top border-bottom border-right border-left p-xs image-file" src="#" style="display:none;">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="w-xs btn btn-outline btn-default btn-file">
                                                                <i class="fa fa-upload"></i>
                                                                <span class="label-file">Browse </span> 
                                                                <input accept="image/jpeg" class="file" type="file" name="foto" style="display: none;">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-lg-12">
                                                        <label>Foto Visual Tambahan (opsional)</label>
                                                        <div class="form-group">
                                                            <label class="w-xs btn btn-outline btn-default btn-file">
                                                                <i class="fa fa-plus"></i>
                                                                <span id="label-file-1" class="label-file-lainnya">Browse </span> 
                                                                <input id="file-1" accept="image/jpeg" class="file-lainnya" type="file" name="foto_lainnya[]" style="display: none;">
                                                            </label>
                                                            <label class="w-xs btn btn-outline btn-default btn-file">
                                                                <i class="fa fa-plus"></i>
                                                                <span id="label-file-2" class="label-file-lainnya">Browse </span> 
                                                                <input id="file-2" accept="image/jpeg" class="file-lainnya" type="file" name="foto_lainnya[]" style="display: none;">
                                                            </label>
                                                            <label class="w-xs btn btn-outline btn-default btn-file">
                                                                <i class="fa fa-plus"></i>
                                                                <span id="label-file-3" class="label-file-lainnya">Browse </span> 
                                                                <input id="file-3" accept="image/jpeg" class="file-lainnya" type="file" name="foto_lainnya[]" style="display: none;">
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" class="w-xs btn btn-danger clear-file"><i class="fa fa-trash"></i> Hapus Foto Tambahan</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Extended Form Visual Asap --}}
                                                <div class="visual-asap" style="{{ old('visual_asap') != 'Teramati' ? 'Display:none;' : '' }}">
                                                    <div class="form-group col-lg-12">
                                                        <label {{$errors->has('tasap_max') || $errors->has('tasap_min')? 'class=text-danger' : ''}}>Tinggi Asap Dari Atas Puncak (meter)</label>
                                                        <div class="form-group row">
                                                            <div class="col-lg-6 col-xs-12">
                                                                <div class="input-group">
                                                                    <input placeholder="Minimum" name="tasap_min" class="form-control" type="text" value="{{ empty(old('tasap_min')) ? '' : old('tasap_min') }}">
                                                                    <span class="input-group-addon" style="min-width: 75px;"> - </span>
                                                                    <input placeholder="Maximum" name="tasap_max" class="form-control" type="text" value="{{ empty(old('tasap_max')) ? '' : old('tasap_max') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-lg-12">
                                                        <label {{$errors->has('wasap') ? 'class=text-danger' : ''}}>Warna Asap</label>
                                                        <div class="row">
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="checkbox">
                                                                    <label><input name="wasap[]" value="Putih" type="checkbox" class="i-checks wasap" {{ (is_array(old('wasap')) AND in_array('Putih',old('wasap'))) ? 'checked' : ''}}> Putih </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input name="wasap[]" value="Kelabu" type="checkbox" class="i-checks wasap" {{ (is_array(old('wasap')) AND in_array('Kelabu',old('wasap'))) ? 'checked' : ''}}> Kelabu </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="checkbox">
                                                                    <label><input name="wasap[]" value="Coklat" type="checkbox" class="i-checks wasap" {{ (is_array(old('wasap')) AND in_array('Coklat',old('wasap'))) ? 'checked' : ''}}> Coklat </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input name="wasap[]" value="Hitam" type="checkbox" class="i-checks wasap" {{ (is_array(old('wasap')) AND in_array('Hitam',old('wasap'))) ? 'checked' : ''}}> Hitam </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form group col-lg-12">
                                                        <label {{$errors->has('intasap') ? 'class=text-danger' : ''}}>Intensitas Asap</label>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6">
                                                                <label class="checkbox-inline"><input name="intasap[]" value="Tipis" type="checkbox" class="i-checks intensitas" {{ (is_array(old('intasap')) AND in_array('Tipis',old('intasap'))) ? 'checked' : ''}}> Tipis </label>
                                                                <label class="checkbox-inline"><input name="intasap[]" value="Sedang" type="checkbox" class="i-checks intensitas" {{ (is_array(old('intasap')) AND in_array('Sedang',old('intasap'))) ? 'checked' : ''}}> Sedang </label>
                                                                <label class="checkbox-inline"><input name="intasap[]" value="Tebal" type="checkbox" class="i-checks intensitas" {{ (is_array(old('intasap')) AND in_array('Tebal',old('intasap'))) ? 'checked' : ''}}> Tebal </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form group col-lg-12">
                                                        <label {{$errors->has('tekasap') ? 'class=text-danger' : ''}}>Tekanan Asap</label>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6">
                                                                <label class="checkbox-inline"><input name="tekasap[]" value="Lemah" type="checkbox" class="i-checks intensitas" {{ (is_array(old('tekasap')) AND in_array('Tipis',old('tekasap'))) ? 'checked' : ''}}> Tipis </label>
                                                                <label class="checkbox-inline"><input name="tekasap[]" value="Sedang" type="checkbox" class="i-checks intensitas" {{ (is_array(old('tekasap')) AND in_array('Sedang',old('tekasap'))) ? 'checked' : ''}}> Sedang </label>
                                                                <label class="checkbox-inline"><input name="tekasap[]" value="Kuat" type="checkbox" class="i-checks intensitas" {{ (is_array(old('tekasap')) AND in_array('Tebal',old('tekasap'))) ? 'checked' : ''}}> Tebal </label>
                                                            </div>
                                                        </div>
                                                    </div>         
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label {{$errors->has('visual_kawah') ? 'class=text-danger' : ''}}>Keterangan Visual Lainnya</label>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <textarea placeholder="Kosongi jika tidak ada" name="visual_kawah" class="form-control" rows="3">{{ old('visual_kawah')}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-left m-t-xs">
                                                <a href="{{ route('chambers.laporan.create.1') }}" type="button" class="btn btn-default">Step 1 - Data Laporan</a>
                                                <button type="submit" class="submit btn btn-primary">Step 2 - Submit</button>
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

@section('add-script')
    <script>
        $(document).ready(function () {

            var height = window.screen.height;
            console.log(height > 480);

            height = height > 480 ? height/4 : 360;

            $('.asap').on('ifChecked', function(e) {
                $(this).val() == 'Teramati' ? $('.visual-asap').show() : $('.visual-asap').hide();
            });

            $('.hasfoto').on('ifChecked', function(e) {
                $(this).val() == '1' ? $('.foto-visual').show() : $('.foto-visual').hide();
            });

            $('input.file').on('change', function(e) {
                var input = $(this),
                    label = input.val()
                                .replace(/\\/g, '/')
                                .replace(/.*\//, ''),
                    reader = new FileReader();

                $('.label-file').html(label);
                reader.onload = function (e) {
                    $('.image-file')
                        .show()
                        .attr('src',e.target.result)
                        .css('max-height', height+'px');
                }

                reader.readAsDataURL(this.files[0]);
            });

            $('input.file-lainnya').on('change', function(e) {
                var $id = $(this).attr('id'),
                    input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                
                $('#label-'+$id).html(label);
            });

            $('.clear-file').on('click', function(e) {
                $('.file-lainnya').val(null);
                $('.label-file-lainnya').html('Browse');
            })

        });
    </script>
@endsection