@extends('layouts.default')

@section('title')
    MAGMA | CRS
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/select2-3.5.2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap/select2-bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li class="active">
                            <span>CRS </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Data CRS - Community Reporting System
                </h2>
                <small>Menampilkan hasil laporan yang pernah dikirim oleh masyarakat</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-heading">
                        Jumlah CRS
                    </div>
                    <div class="panel-body list">
                        <div class="panel-title">Statistik Laporan</div>
                        <small class="fo">Berdasarkan Status</small>
                        <div class="list-item-container">
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-baru">{{ $jumlahBaru }}</h3>
                                <small> Baru </small>
                                <div class="pull-right font-bold">{{ $jumlahBaru>0 ? round($jumlahBaru/$total*100, 2 ) : 0 }}% <i class="fa fa-level-up text-magma"></i></div>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-draft">{{ $jumlahDraft }}</h3>
                                <small> Draft</small>
                                <div class="pull-right font-bold">{{ $jumlahBaru>0 ? round($jumlahDraft/$total*100, 2) : 0}}% <i class="fa fa-level-down text-color3"></i></div>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-terbit">{{ $jumlahTerbit }}</h3>
                                <small> Terbit</small>
                                <div class="pull-right font-bold">{{ $jumlahBaru>0 ? round($jumlahTerbit/$total*100, 2) : 0 }}% <i class="fa fa-bolt text-color3"></i></div>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">{{ $total }}</h3>
                                <small>Total Semuanya</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hpanel">
                    <div class="panel-heading">
                        Jumlah CRS per Bidang
                    </div>
                    <div class="panel-body list">
                        <div class="panel-title">Statistik Laporan</div>
                        <small class="fo">Dikelompokkan Per Bencana</small>
                        <div class="list-item-container">
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">
                                    {{ $jumlahMga }}
                                    <span class="pull-right">{{ $jumlahMga ? round($jumlahMga/$total*100, 2) : 0 }}%  </span>
                                </h3>
                                <h6>Gunung Api</h6>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">
                                    {{ $jumlahMgt }}
                                    <span class="pull-right">{{ $jumlahMga ? round($jumlahMgt/$total*100, 2) : 0 }}%  </span>
                                </h3>
                                <h6>Gerakan Tanah</h6>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">
                                    {{ $jumlahMgb }}
                                    <span class="pull-right">{{ $jumlahMgb ? round($jumlahMgb/$total*100, 2) : 0 }}%  </span>
                                </h3>
                                <h6>Gempa Bumi dan Tsunami</h6>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">
                                    {{ $jumlahEtc }}
                                    <span class="pull-right">{{ $jumlahMgb ? round($jumlahEtc/$total*100, 2) : 0 }}%  </span>
                                </h3>
                                <h6>Semburan Lumpur, Gas dan Air</h6>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">            
                <div class="hpanel">
                    <div class="panel-heading">
                        Per Provinsi - 8 Tertinggi
                    </div>                        
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Provinsi</th>
                                        <th>Jumlah Laporan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($topProv as $item)
                                <tr>
                                    <td>
                                        <span class="font-bold">{{ title_case($item->name) }}</span>
                                    </td>
                                    <td>{{ $item->total }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Filter Laporan
                    </div>
                    <div class="panel-body">
                        <form role="form" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" class="form-control m-b" name="status"> 
                                            <option value="all" {{ empty(old('status')) ? 'selected' : ''}}>Semua Status</option>
                                            <option value="BARU" {{ old('status') == 'BARU' ? 'selected' : ''}}>Baru</option>
                                            <option value="DRAFT" {{ old('status') == 'DRAFT' ? 'selected' : ''}}>Draft</option>
                                            <option value="TERBIT" {{ old('status') == 'TERBIT' ? 'selected' : ''}}>Terbit</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipe">Tipe Bencana</label>
                                        <select id="tipe" class="form-control m-b" name="tipe">
                                            <option value="all" {{ empty(old('tipe')) ? 'selected' : ''}}>Semua Bencana</option>
                                            <option value="1" {{ old('tipe') == 1 ? 'selected' : ''}}>Gerakan Tanah</option>
                                            <option value="2" {{ old('tipe') == 2 ? 'selected' : ''}}>Gempa Bumi</option>
                                            <option value="3" {{ old('tipe') == 3 ? 'selected' : ''}}>Tsunami</option>
                                            <option value="4" {{ old('tipe') == 4 ? 'selected' : ''}}>Gunung Api</option>
                                            <option value="5" {{ old('tipe') == 5 ? 'selected' : ''}}>Semburan Lumpur, Gas dan Air</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="valid">Validasi</label>
                                        <select id="valid" class="form-control m-b" name="valid"> 
                                            <option value="all" {{ empty(old('valid')) ? 'selected' : ''}}>Semua Validasi</option>
                                            <option value="not" {{ old('valid') == 'not' ? 'selected' : ''}}>Belum Divalidasi</option>
                                            <option value="valid" {{ old('valid') == 'valid' ? 'selected' : ''}}>Valid</option>
                                            <option value="invalid" {{ old('valid') == 'invalid' ? 'selected' : ''}}>Invalid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provinsi">Provinsi</label>
                                        <select id="provinsi" class="form-control m-b" name="provinsi">
                                            <option value="all" selected="">Semua Provinsi</option>
                                            @foreach($provinsi as $item)                   
                                            <option value="{{ $item->id }}" {{ old('provinsi') == $item->name ? 'selected' : ''}}>{{ $item->name }}</option>      
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kota">Kota/Kabupaten</label>
                                        <select id="kota" class="form-control m-b" name="kota">
                                            <option value="all" selected="">Semua Kota</option>
                                            @foreach($provinsi[0]->cities as $item)                   
                                            <option value="{{ $item->id }}" {{ old('kota') == $item->name ? 'selected' : ''}}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Waktu Kejadian</label>
                                        <div class="input-group input-daterange">
                                            <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(90)->format('Y-m-d') : old('start')}}" name="start">
                                            <div class="input-group-addon"> - </div>
                                            <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div>
                                <button class="btn btn-sm btn-primary m-t-n-xs"><strong>Fiter Laporan</strong></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Data Laporan CRS yang diterima
                    </div>
                    <div class="panel-body list">
                        <div class="row">
                            <div class="col-md-6">
                                {{ $crs->appends(Request::except('crs_page'))->links() }}
                            </div>
                            <div class="col-md-6">
                                <div class="pagination pull-right">
                                    <a href="{{ route('chambers.export',['type' => 'crs',Request::getQueryString()]) }}" type="button" class="btn btn-magma btn-sm m-b-t">Save to Excel</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive project-list">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Nama Pelapor</th>
                                        <th>No. HP</th>
                                        <th>Tipe Bencana</th>
                                        <th>Validasi</th>
                                        <th>Validator</th>
                                        <th>Waktu Kejadian</th>
                                        <th>Lokasi</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Sumber</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($crs as $item)
                                    <tr title="{{ $item->name }}">
                                        <td class="{{ $item->status == 'BARU' ? 4 : ($item->status == 'DRAFT' ? 2 : 1) }}">{{ title_case($item->status) }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ title_case($item->type) }}</td>
                                        <td><span class="label {{ optional($item->validator)->valid ? $item->validator->valid == 'Valid' ? 'label-magma' : 'label-warning': 'label-danger'}}">{{ optional($item->validator)->valid ? $item->validator->valid : 'Belum divalidasi'}}</span></td>
                                        <td>{{ optional($item->validator)->user ? $item->validator->user->name : '-'}}</td>  
                                        <td>{{ $item->waktu_kejadian }}</td>
                                        <td>{{ Indonesia::findProvince($item->province_id)->name .', '. Indonesia::findCity($item->city_id)->name .', '. Indonesia::findDistrict($item->district_id)->name }}</td>
                                        <td>{{ $item->latitude }}</td>
                                        <td>{{ $item->longitude }}</td>
                                        <td>{{ $item->sumber }}</td>
                                        <td>{{ $item->tsc->formatLocalized('%A, %d %B %Y').', '. $item->ksc }}</td>                                      
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $crs->appends(Request::except('crs_page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <script src="{{ asset('vendor/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/select2-3.5.2/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('add-script')
    <script>
        $(document).ready(function(){
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

            jQuery.validator.addMethod('isValid', function (value, element) {
                var startDate = $('#start').val();
                var endDate = $('#end').val();
                return Date.parse(startDate) <= Date.parse(endDate);
            }, '* Tanggal akhir harus setelah tanggal awal');

            $('.input-daterange').datepicker({
                startDate: '2015-05-01',
                endDate: '{{ now()->format('Y-m-d') }}',
                language: 'id',
                todayHighlight: true,
                todayBtn: 'linked',
                enableOnReadonly: false
            });

            $('.input-daterange input').each(function() {
                $(this).datepicker().on('changeDate', function(e){
                    var startDate = $('#start').val(),
                        endDate = $('#end').val(),
                        isValid = Date.parse(startDate) <= Date.parse(endDate);
                    console.log(isValid);
                });
            });
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('#provinsi').on('change',function(e){
                var $id = $('#provinsi').val();
                var $city = $('#kota'),
                    $kota = '<option value="*" selected="">Semua Kota</option>';
                    $city.empty().append('<option value="*" selected="">Loading ...</option>');

                console.log($id);
                
                $.ajax({
                    url: '{{ route('chambers.crs.getcities' )}}',
                    data: {id:$id},
                    type: 'POST',
                    success: function(data){
                        $city.empty();
                        $.each(data, function(index,value){
                            var $element = '<option value="'+value.id+'" >'+value.name+'</option>';
                            $kota += $element;
                        });
                        $city.append($kota);
                    }
                });
                
            });
        });
    </script>
@endsection