@extends('layouts.default')

@section('title')
    Import MAGMAv1
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/ladda/dist/ladda-themeless.min.css') }}" />
@endsection

@section('content-body')   
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Selamat Datang di Import Data MAGMA
                </h2>

                <p>
                    Pusat kontrol untuk melakukan 
                    <strong>Import data MAGMA v1 ke MAGMA v2</strong> 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Users
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class=" pe-7s-add-user fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-users">{{ $users }}</h1>

                        <h3 class="font-extra-bold no-margins text-success">
                            Jumlah User
                        </h3>
                        <small>Jumlah aktif dan terdaftar</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="users" action="{{ route('import.users') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Users</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Dasar Gunung Api
                    </div>
                    <div class="panel-body text-center h-200">
                        <i class=" pe-7s-server fa-4x"></i>
                        <br>
                        <h1 class="m-xs jumlah-gadds">{{ $gadds }}</h1>

                        <h3 class="font-extra-bold no-margins text-success">
                            Jumlah Gunung Api
                        </h3>
                        <small>Data dasar Gunung Api yang terdaftar</small>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="gadds" action="{{ route('import.gadds') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Dasar</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Var
                    </div>
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Data Utama VAR</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-share fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h3 class="m-b-xs jumlah-vars">{{ number_format($vars,0,',','.') }}</h3>
                            <span class="font-bold no-margins">
                                Jumlah data VAR
                            </span>

                            <div class="progress m-t-xs full progress-small">
                                <div style="width: {{ $vars/$varsv1*100 }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ $vars/$varsv1*100 }}" role="progressbar" class=" progress-bar progress-bar-success">
                                    <span class="sr-only">{{ $vars/$varsv1*100 }}% Complete</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stats-label">Jumlah VAR Magma v1</small>
                                    <h4>{{ number_format($varsv1,0,',','.') }}</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stats-label">% Data</small>
                                    <h4 class="persentase-vars">{{ round($vars/$varsv1*100, 2) }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="vars" action="{{ route('import.vars') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Vars</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                        <div class="panel-heading">
                            Data VAR Harian
                        </div>
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Var Daily</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-monitor fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-success jumlah-dailies">{{ $vardailies }}</h1>
                            <span class="font-bold no-margins">
                                Volcanic Activiy Report
                            </span>
                            <br/>
                            <small>
                                Panel ini memberikan informasi tentang
                                <strong>update VAR terkini dari MAGMA v1
                                </strong>. Digunakan untuk melakukan updating manual.
                            </small>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="dailies" action="{{ route('import.dailies') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Harian</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Var
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Visual</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class=" pe-7s-camera fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data VAR Visual
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk meng-import data visual dari MAGMA-VAR, sekaligus menormalisasi data ke versi yang lebih baru.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-success jumlah-visuals">{{ number_format($visuals,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-success"></i> Jumlah data terkini</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="visuals" action="{{ route('import.visuals') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Visual</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Var
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Klimatologi</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-cloud fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data Klimatologi
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk import data pengamatan klimatologi dari MAGMA-VAR, sekaligus normalisasi data ke versi yang lebih baru.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-success jumlah-klimatologis">{{ number_format($klimatologis,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-success"></i> Jumlah data terkini</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="klimatologis" action="{{ route('import.klimatologi') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Klimatologi</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        Data Gempa Gunung Api
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Data Gempa Gunung Api</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-download fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Import Data Gempa Gunung Api
                            </span>
                            <br/>
                            <small>
                                Panel ini digunakan untuk import data Gempa Gunung Api dari MAGMA-VAR, sekaligus normalisasi data ke versi yang lebih baru.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-success jumlah-gempa">{{ number_format($gempagunungapi,0,',','.') }}</h3>
                                <div class="font-bold"><i class="fa fa-level-up text-success"></i> Jumlah gempa seluruh gunung api Indonesia yang tersimpan</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <form role="form" id="form-import" method="POST"
                        data-import="gempa" action="{{ route('import.gempa') }}">
                            {{ csrf_field() }}
                            <button type="submit" id="form-submit" class="ladda-button btn btn-success btn-sm " data-style="expand-right">
                                <span class="ladda-label">Import Data Gempa Gunung Api</span>
                                <span class="ladda-spinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>            
        </div>
    </div>
@endsection

@section('add-vendor-script')

    <script src="{{ asset('vendor/ladda/dist/spin.min.js') }}"></script>
    <script src="{{ asset('vendor/ladda/dist/ladda.min.js') }}"></script>
    <script src="{{ asset('vendor/ladda/dist/ladda.jquery.min.js') }}"></script>
    
@endsection

@section('add-script')
    <script>

        $(document).ready(function () {

            function commafy(num) {
                var $num = num.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1.');
                return $num;
            }

            $('button#form-submit').on('click',function(e) {
                e.preventDefault();

                var l = Ladda.create(this),
                    $button = $(this),
                    $label = $(this).children('.ladda-label');

                l.start();

                var $url = $(this).parent().attr('action'),
                    $data = $(this).parent().serialize(),
                    $import = $(this).parent().attr('data-import');

                $.ajax({
                    url: $url,
                    data: $data,
                    type: 'POST',
                    success: function(data){
                        if (data.success==1){
                            console.log(data);
                            setTimeout(function(){
                                l.stop();
                                $jumlah = data.count;
                                $button.attr('disabled', 'disabled');
                                $label.html(data.message);
                                $('.jumlah-'+$import).html(commafy($jumlah));
                                if ($import=='vars') {
                                    $persentase = $jumlah/{{ $varsv1 }}*100;
                                    $('.persentase-'+$import).html($persentase.toFixed(2)+'%');
                                }
                            },1000)
                        } else {
                            l.stop();
                            $button.removeAttr('disabled');
                        }
                    },
                    error: function(xhr,code,error){
                        console.log(xhr.status);
                        l.stop();
                        $label.html('Error. Coba lagi?');
                    }
                });
                
            });

        });

    </script>
@endsection