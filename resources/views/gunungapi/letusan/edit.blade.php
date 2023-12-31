@extends('layouts.default')

@section('title')
    Edit Informasi Letusan Gunung Api
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
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
                        <li>
                            <a href="{{ route('chambers.datadasar.index') }}">Gunung Api</a>
                        </li>
                        <li>
                            <a href="{{ route('chambers.letusan.index') }}">Letusan</a>
                        </li>
                        <li class="active">
                            <span>Edit Laporan</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Edit Laporan Letusan Gunung Api
                </h2>
                <small>Edit</small>
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
                        Volcano Eruption Notice
                    </div>
                    <div class="panel-body float-e-margins">
                        <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.letusan.index') }}" class="btn btn-outline btn-block btn-magma" type="button">Daftar Informasi Letusan</a>
                        </div>
                        <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">
                            <a href="{{ route('chambers.letusan.create') }}" class="btn btn-outline btn-block btn-magma" type="button">Buat Informasi Letusan</a>
                        </div>
                    </div>
                </div>
                <div class="hpanel">
                    <div class="panel-heading">
                        Edit Informasi Letusan
                    </div>
                    <div class="panel-body">     
                        <form id="form" class="form-horizontal" method="POST" action="{{ route('chambers.letusan.update',['uuid' => $ven->uuid ]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            {{-- Nama Gunung Api --}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Gunung Api</label>
                                <div class="col-sm-6">
                                    <select id="code" class="form-control" name="code">
                                        @foreach($gadds as $gadd)
                                        <option value="{{ $gadd->code }}" {{ (($ven->code_id == $gadd->code) OR (old('code') == $gadd->code)) ? 'selected' : ''}}>{{ $gadd->name }}</option>
                                        @endforeach
                                    </select>
                                    @if( $errors->has('code'))
                                    <label class="error" for="code">{{ ucfirst($errors->first('code')) }}</label>
                                    @endif
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Gunung Api</label>
                                <div class="col-sm-6">
                                    <select id="status" class="form-control" name="status">
                                        <option value="1" {{ (($ven->status == 'Level I (Normal)') OR (old('status') == '1')) ? 'selected' : ''}}>Level I (Normal)</option>
                                        <option value="2" {{ (($ven->status == 'Level II (Waspada)') OR (old('status') == '2')) ? 'selected' : ''}}>Level II (Waspada)</option>
                                        <option value="3" {{ (($ven->status == 'Level III (Siaga)') OR (old('status') == '3')) ? 'selected' : ''}}>Level III (Siaga)</option>
                                        <option value="4" {{ (($ven->status == 'Level IV (Awas)') OR (old('status') == '4')) ? 'selected' : ''}}>Level IV (Awas)</option>
                                    </select>
                                    @if( $errors->has('status'))
                                    <label class="error" for="status">{{ ucfirst($errors->first('status')) }}</label>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Visual Kolom Abu --}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Visual Kolom Abu</label>
                                <div class="col-sm-6">
                                    <select id="visibility" class="form-control" name="visibility">
                                        <option value="1" {{ (($ven->visibility == '1') AND (old('visibility') == '1')) ? 'selected' : ''}}>Teramati</option>
                                        <option value="0" {{ (($ven->visibility == '0') AND (old('visibility') == '1')) ? 'selected' : ''}}>Tidak Teramati</option>
                                    </select>
                                    @if( $errors->has('visibility'))
                                    <label class="error" for="visibility">{{ ucfirst($errors->first('visibility')) }}</label>
                                    @endif
                                </div>
                            </div>

                            {{-- Waktu Letusan --}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Waktu Letusan</label>
                                <div class="col-sm-6">
                                    <input name="date" id="datepicker" class="form-control" type="text" value="{{ empty(old('date')) ? $ven->date.' '.$ven->time : old('date') }}">
                                    @if( $errors->has('date'))
                                    <label class="error" for="date">{{ ucfirst($errors->first('date')) }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="teramati" style="display: {{ old('visibility') == '0' ? 'none' :'block'}};">
                                {{-- Tinggi Letusan --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tinggi Letusan</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input placeholder="Antara 100 - 20.000 meter" name="height" class="form-control" type="text" value="{{ empty(old('height')) ? $ven->height : old('height') }}">
                                            <span class="input-group-addon h-bg-red">meter, dari atas puncak</span>
                                        </div>
                                        @if( $errors->has('height'))
                                        <label class="error" for="height">{{ ucfirst($errors->first('height')) }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                {{-- Warna Abu --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Warna Abu</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label><input name="wasap[]" value="Putih" type="checkbox" class="i-checks wasap" {{ ((!empty($ven->wasap) AND in_array('Putih',$ven->wasap)) OR (is_array(old('wasap')) AND in_array('Putih',old('wasap')))) ? 'checked' : ''}}> Putih </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="wasap[]" value="Kelabu" type="checkbox" class="i-checks wasap" {{ ((!empty($ven->wasap) AND  in_array('Kelabu',$ven->wasap)) OR (is_array(old('wasap')) AND in_array('Kelabu',old('wasap')))) ? 'checked' : ''}}> Kelabu </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label><input name="wasap[]" value="Coklat" type="checkbox" class="i-checks wasap" {{ ((!empty($ven->wasap) AND in_array('Coklat',$ven->wasap)) OR (is_array(old('wasap')) AND in_array('Coklat',old('wasap')))) ? 'checked' : ''}}> Coklat </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="wasap[]" value="Hitam" type="checkbox" class="i-checks wasap" {{ ((!empty($ven->wasap) AND in_array('Hitam',$ven->wasap)) OR (is_array(old('wasap')) AND in_array('Hitam',old('wasap')))) ? 'checked' : ''}}> Hitam </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if($errors->has('wasap'))
                                            <label class="error" for="wasap">{{  ucfirst($errors->first('wasap')) }}</label>
                                        @endif
                                        @if($errors->has('wasap.*'))
                                        @foreach($errors->get('wasap.*') as $error)
                                            <label class="error" for="wasap">{{  $error[0] }}</label>
                                            @break
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                {{-- Intensitas --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Intensitas</label>
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline"><input name="intensitas[]" value="Tipis" type="checkbox" class="i-checks intensitas" {{ ((!empty($ven->intensitas) AND in_array('Tipis',$ven->intensitas)) OR (is_array(old('intensitas')) AND in_array('Tipis',old('intensitas')))) ? 'checked' : ''}}> Tipis </label>
                                        <label class="checkbox-inline"><input name="intensitas[]" value="Sedang" type="checkbox" class="i-checks intensitas" {{ ((!empty($ven->intensitas) AND in_array('Sedang',$ven->intensitas)) OR (is_array(old('intensitas')) AND in_array('Sedang',old('intensitas')))) ? 'checked' : ''}}> Sedang </label>
                                        <label class="checkbox-inline"><input name="intensitas[]" value="Tebal" type="checkbox" class="i-checks intensitas" {{ ((!empty($ven->intensitas) AND in_array('Tebal',$ven->intensitas)) OR (is_array(old('intensitas')) AND in_array('Tebal',old('intensitas')))) ? 'checked' : ''}}> Tebal </label>
                                        @if( $errors->has('intensitas'))
                                        <label class="error" for="intensitas">{{ ucfirst($errors->first('intensitas')) }}</label>
                                        @endif
                                        @if($errors->has('intensitas.*'))
                                        @foreach($errors->get('intensitas.*') as $error)
                                            <label class="error" for="intensitas">{{  $error[0] }}</label>
                                            @break
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                {{-- Arah Abu --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Abu Mengarah ke</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Utara" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Utara',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Utara',old('arah')))) ? 'checked' : ''}}> Utara </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Timur Laut" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Timur Laut',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Timur Laut',old('arah')))) ? 'checked' : ''}}> Timur Laut </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Timur" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Timur',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Timur',old('arah')))) ? 'checked' : ''}}> Timur </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Tenggara" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Tenggara',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Tenggara',old('arah')))) ? 'checked' : ''}}> Tenggara </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Selatan" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Selatan',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Selatan',old('arah')))) ? 'checked' : ''}}> Selatan </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Barat Daya" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Barat Daya',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Barat Daya',old('arah')))) ? 'checked' : ''}}> Barat Daya </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Barat" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Barat',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Barat',old('arah')))) ? 'checked' : ''}}> Barat </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input name="arah[]" value="Barat Laut" type="checkbox" class="i-checks arah" {{ ((!empty($ven->arah_asap) AND in_array('Barat Laut',$ven->arah_asap)) OR (is_array(old('arah')) AND in_array('Barat Laut',old('arah')))) ? 'checked' : ''}}> Barat Laut </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if( $errors->has('arah'))
                                        <label class="error" for="arah">{{ ucfirst($errors->first('arah')) }}</label>
                                        @endif
                                        @if($errors->has('arah.*'))
                                        @foreach($errors->get('arah.*') as $error)
                                            <label class="error" for="arah">{{  $error[0] }}</label>
                                            @break
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                {{-- Rekaman Seismik --}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Rekaman Seismik</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="min-width: 100px;">Amplitudo</span>
                                            <input name="amplitudo" class="form-control" type="text" value="{{ empty(old('amplitudo')) ? $ven->amplitudo : old('amplitudo') }}">
                                            <span class="input-group-addon" style="min-width: 75px;">mm</span>
                                        </div>
                                        @if( $errors->has('amplitudo'))
                                        <label class="error" for="amplitudo">{{ ucfirst($errors->first('amplitudo')) }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6 col-sm-offset-2">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="min-width: 100px;">Durasi</span>
                                            <input name="durasi" class="form-control" type="text" value="{{ empty(old('durasi')) ? $ven->durasi : old('durasi') }}">
                                            <span class="input-group-addon" style="min-width: 75px;">detik</span>
                                        </div>
                                        @if( $errors->has('durasi'))
                                        <label class="error" for="durasi">{{ ucfirst($errors->first('durasi')) }}</label>
                                        @endif
                                    </div>
                                </div>
                            </div>                            
                            {{-- Rekomendasi --}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Rekomendasi</label>
                                <div class="col-sm-6">
                                    <textarea placeholder="Kosongi jika tidak ada" name="rekomendasi"class="form-control" rows="5">{{ empty(old('rekomendasi')) ? $ven->rekomendasi : old('rekomendasi') }}</textarea>
                                </div>
                            </div>
                            
                            {{-- Keterangan Lainnya --}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Keterangan Lainnya (opsional)</label>
                                <div class="col-sm-6">
                                    <textarea placeholder="Kosongi jika tidak ada" name="lainnya"class="form-control" rows="3">{{ empty(old('lainnya')) ? $ven->lainnya : old('lainnya') }}</textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            {{-- Submit --}}
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">Submit Laporan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/moment/locale/id.js') }}"></script>
    <script src="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            $('#datepicker').datetimepicker({
                minDate: '2015-05-01',
                maxDate: '{{ now()->addDay(1)->format('Y-m-d')}}',
                sideBySide: true,
                locale: 'id',
                format: 'YYYY-MM-DD HH:mm',
            });

            $('#visibility').on('change',function(){
                var $val = $('#visibility').val();
                if($val == '0')
                {
                    $('.teramati').hide();
                } else {
                    $('.teramati').show();
                }
            });
        });
    </script>
@endsection