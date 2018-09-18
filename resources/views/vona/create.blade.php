@extends('layouts.default')

@section('title')
    VONA | Create
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
                        <li class="active">
                            <a href="{{ route('chambers.vona.create') }}">Buat VONA</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Buat VONA Baru
                </h2>
                <small>Buat VONA baru. Default template telah disesuaikan dengan format ICAO (International Civil Aviation Organization).</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Form VONA
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="POST" action="{{ route('chambers.vona.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input name="_type" value="base" type="hidden">
                            @role('Super Admin|Admin MGA')
                            <div class="form-group">
                                <label>Pembuat Laporan</label>
                                <select id="nip" class="form-control" name="nip">
                                @foreach($users as $user)
                                <option value="{{ $user->nip}}" {{ $user->nip == auth()->user()->nip ? 'selected' : ''}}>{{ $user->name }}</option>      
                                @endforeach
                                </select>
                                @if( $errors->has('nip'))
                                <label class="error" for="nip">{{ ucfirst($errors->first('nip')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Tipe Laporan</label>
                                <select id="tipe" class="form-control" name="tipe">
                                    <option value="real" {{ strtolower(old('tipe')) == 'real' || empty(old('tipe')) ? 'selected' : ''}}>Real</option>
                                    <option value="exercise" {{ strtolower(old('tipe')) == 'exercise' ? 'selected' : ''}}>Exercise</option>
                                </select>
                                @if( $errors->has('tipe'))
                                <label class="error" for="tipe">{{ ucfirst($errors->first('tipe')) }}</label>
                                @endif
                            </div>
                            @endrole
                            <div class="form-group">
                                <label>Gunung Api</label>
                                <select id="code" class="form-control" name="code">
                                    @foreach($gadds as $gadd)
                                    <option value="{{ $gadd->code }}" {{ old('code') == $gadd->code ? 'selected' : ''}}>{{ $gadd->name }}</option>
                                    @endforeach
                                </select>
                                @if( $errors->has('code'))
                                <label class="error" for="code">{{ ucfirst($errors->first('code')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Current Color Code*</label>
                                <select id="cucode" class="form-control" name="cucode">
                                    <option value="green" {{ old('cucode') == 'green' ? 'selected' : ''}}>Green</option>
                                    <option value="yellow" {{ old('cucode') == 'yellow' ? 'selected' : ''}}>Yellow</option>
                                    <option value="orange" {{ old('cucode') == 'orange' ? 'selected' : ''}}>Orange</option>
                                    <option value="red" {{ old('cucode') == 'red' ? 'selected' : ''}}>Red</option>
                                </select>
                                @if( $errors->has('cucode'))
                                <label class="error" for="cucode">{{ ucfirst($errors->first('cucode')) }}</label>
                                @endif
                                <span class="text-bold help-block m-b-none">* <span style="color:#9CCC65;"><b>Green</b></span>, tidak ada erupsi/abu vulkanik.</span>
                                <span class="text-bold help-block m-b-none">* <span style="color:#FFEB3B;"><b>Yellow</b></span>, abu vulkanik tidak teramati tetapi gunungapi <b>MULAI</b> menunjukkan peningkatan aktivitas.</span>
                                <span class="text-bold help-block m-b-none">* <span style="color:#FFC107;"><b>Orange</b></span>, telah terjadi erupsi, abu vulkanik teramati dengan tinggi <b>KURANG DARI</b> 6000 mdpl.</span>
                                <span class="text-bold help-block m-b-none">* <span style="color:#F44336;"><b>Red</b></span>, telah terjadi erupsi, abu vulkanik teramati dengan tinggi <b>LEBIH DARI</b> 6000 mdpl.</span>
                            </div>
                            <div class="form-group">
                                <label>Volcanic Activity Summary*</label>
                                <textarea id="vas" class="form-control" rows="3" name="vas">{{ old('vas') }}</textarea>
                                <span class="text-bold help-block m-b-none">* Gambaran umum aktivitas gunungapi. <b>Contoh :</b> Eruption with volcanic ash cloud at 1253 UTC (2053 local). Eruption and ash emission is continuing.</span>
                                @if( $errors->has('vas'))
                                <label class="error" for="vas">{{ ucfirst($errors->first('vas')) }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Volcanic Cloud Height*</label>
                                <div class="input-group">
                                    <input id="vch" value="{{ old('vch') }}" class="form-control" name="vch">
                                    <span class="input-group-addon">meter, dari atas puncak.</span>
                                </div>
                                @if( $errors->has('vch'))
                                <label class="error" for="vch">{{ ucfirst($errors->first('vch')) }}</label>
                                @endif
                                <span class="text-bold help-block m-b-none">*Tinggi letusan abu vulkanik dari atas puncak.</span>
                            </div>
                            <div class="form-group">
                                <label>Other Volcanic Cloud Information*</label>
                                <textarea id="ovci" class="form-control" name="ovci">{{ old('ovci') }}</textarea>
                                @if( $errors->has('ovci'))
                                <label class="error" for="ovci">{{ ucfirst($errors->first('ovci')) }}</label>
                                @endif
                                <span class="text-bold help-block m-b-none">*Keterangan abu vulkanik meliputi arah, ketebalan, warna dll. Contoh : Ash-cloud moving to southeast.</span>
                            </div>
                            <div class="form-group">
                                <label>Remarks*</label>
                                <textarea class="form-control" name="remarks">{{ old('ovci') }}</textarea>
                                @if( $errors->has('remarks'))
                                <label class="error" for="remarks">{{ ucfirst($errors->first('remarks')) }}</label>
                                @endif
                                <span class="text-bold help-block m-b-none">*Keterangan abu vulkanik meliputi arah, ketebalan, warna dll. Contoh : Ash-cloud moving to southeast.</span>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <button class="btn btn-sm btn-primary submit" type="submit">Save</button>     
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

        $('#form').validate({
            rules:{
                nip: {
                    required: true,
                },
                tipe: {
                    required: true,
                },
                code: {
                    required: true,
                },
                cucode: {
                    required: true,
                },
                vas: {
                    required: true,
                    minlength: 50
                },
                vch: {
                    required: true,
                    maxlength: 5,
                    digits: true,
                },
                ovci: {
                    required: true,
                    minlength: 20
                }
            },
            messages: {
                nip: {
                    required: 'Harap Pilih Nama Anda',
                },
                tipe: {
                    required: 'Harap Pilih Tipe Laporan',                    
                },
                code: {
                    required: 'Harap Pilih Gunung Api',                    
                },
                cucode: {
                    required: 'Harap Pilih Current Color Code',                    
                },
                vas: {
                    required: 'Volcanic Activity Summary tidak boleh kosong',
                    minlength: 'Minimal 50 karakter'
                },
                vch: {
                    required: 'Tinggi Kolom Abu belum diisi',
                    maxlength: 'Maximum 5 digit',
                    digits: 'Paramater harus berupa angka'
                },
                ovci: {
                    required: 'Other Volcanic Cloud Information tidak boleh kosong',
                    minlength: 'Minimal 20 karakter'
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        
    });
</script>
@endsection