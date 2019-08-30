@extends('layouts.default')

@section('title')
    Hasil Evaluasi {{ $gadd->ga_nama_gapi }}
@endsection

@section('add-vendor-css')
    @role('Super Admin')
    <link rel="stylesheet" href="{{ asset('vendor/json-viewer/jquery.json-viewer.css') }}" />
    @endrole
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
                            <span>Evaluasi </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Hasil Evaluasi Gunung Api {{ $gadd->ga_nama_gapi }}
                </h2>
                <small>Menampilkan data visual dan kegempaan pada periode tertentu.</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content">
        <div class="row">
            <div class="col-lg-4">

                @role('Super Admin')
                @component('components.json-var')
                    @slot('title')
                        For Developer
                    @endslot
                @endcomponent
                @endrole

                <div class="hpanel hgreen">
                    <div class="panel-body">
                        <h4>Resume Pengamatan Visual</h4>
                        <div class="text-muted font-bold m-b-xs">Periode {{ $data['periode_report']['start'] }} hingga {{ $data['periode_report']['end'] }} - {{ $data['periode_report']['count'] }} hari</div>
                        <hr>
                        <p class="pd-r">{!! $data['summary']['visual'] !!}</p>

                        <button type="button" class="btn copy m-t" data-toggle="tooltip" data-placement="top" title="Copied!" data-clipboard-text="{!! $data['summary']['visual'] !!}">Copy</button>
                    </div>
                </div>

                <div class="hpanel hgreen">
                    <div class="panel-body">
                        <h4>Resume Kegempaan</h4>
                        <div class="text-muted font-bold m-b-xs">Periode {{ $data['periode_report']['start'] }} hingga {{ $data['periode_report']['end'] }} - {{ $data['periode_report']['count'] }} hari</div>
                        <hr>
                        @if ($data['summary']['gempa'])

                        <button class="btn" type="button" data-toggle="collapse" data-target="#collapse-gempa" aria-expanded="true" aria-controls="collapse">
                        Sembunyikan
                        </button>

                        <div class="collapse in" id="collapse-gempa">
                            <ul class="list-group m-t-lg">
                                @foreach ($data['summary']['gempa'] as $gempa)
                                <li class="list-group-item">{{ $gempa }}</li>  
                                @endforeach
                            </ul>

                            <div class="m-t">
                                <button type="button" class="btn copy" data-toggle="tooltip" data-placement="top" title="Copied!" aria-label data-clipboard-text="{{ implode('',$data['summary']['gempa']) }}">Copy</button>
                            </div>
                        </div>

                        @else
                        <div class="alert alert-warning"><p>Tidak ada kegempaan/nihil</p></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if ($data['summary']['gempa'])
                <div class="hpanel">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1">Detail Visual</a></li>
                        <li><a data-toggle="tab" href="#tab-2">Detail Kegempaan</a></li>
                        <li><a data-toggle="tab" href="#tab-3">Tabel Kegempaan</a></li>
                        <li><a data-toggle="tab" href="#tab-4">Grafik Kegempaan</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <ul class="list-group m-t-lg">
                                @foreach ($data['details'] as $details)
                                    <li class="list-group-item"><b>{{ \Carbon\Carbon::parse($details['date'])->formatLocalized('%A, %d %B %Y')}}</b>, {!! $details['visual'] ?: 'Tidak ada data' !!}</li>
                                @endforeach
                                </ul>
                            </div>
                        </div>

                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <ul class="list-group m-t-lg">
                                @foreach ($data['details'] as $details)
                                    @if (!empty($details['gempa']))
                                    <li class="list-group-item"><b>{{ \Carbon\Carbon::parse($details['date'])->formatLocalized('%A, %d %B %Y')}}</b>, terekam {{ implode('',$details['gempa']) }}</li>  
                                    @else
                                    <li class="list-group-item"><b>{{ \Carbon\Carbon::parse($details['date'])->formatLocalized('%A, %d %B %Y')}}</b>, kegempaan nihil.</li>  
                                    @endif

                                @endforeach
                                </ul>
                            </div>
                        </div>

                        <div id="tab-3" class="tab-pane">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis Gempa</th>
                                                <th>Jumlah</th>
                                                <th>A-min</th>
                                                <th>A-max</th>
                                                <th>SP-min</th>
                                                <th>SP-max</th>
                                                <th>D-min</th>
                                                <th>D-max</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['summary']['raw'] as $key => $gempa)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $gempa['nama'] }}</td>
                                                <td>{{ $gempa['jumlah'] }}</td>
                                                <td>{{ $gempa['amin'] ?: '-'}}</td>
                                                <td>{{ $gempa['amax'] }}</td>
                                                <td>{{ $gempa['spmin'] ?: '-' }}</td>
                                                <td>{{ $gempa['spmax'] ?: '-' }}</td>
                                                <td>{{ $gempa['dmin'] ?: '-' }}</td>
                                                <td>{{ $gempa['dmax'] ?: '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-footer border-right border-left border-bottom m-b-md">
                                Keterangan : A = Amplitudo, D = Durasi (Lama Gempa). Min = Nilai Minimum. Max = Nilai Maksimum. Amplitudo dalam Milimeter (mm), Durasi dalam satuan Detik 
                            </div>
                        </div>

                        <div id="tab-4" class="tab-pane">
                            <div class="panel-body">
                                <div class="text-left">
                                    <button id="export-png" href="#" type="button" class="btn btn-magma btn-outline">Simpan Semua Grafik</button>
                                    <button id="export-pdf" href="#" type="button" class="btn btn-magma btn-outline">Simpan Semua Dalam PDF</button>
                                </div>
                                <hr>
                                <div class="row p-md">
                                    <div id="gempa" style="min-width: 310px; height: 480px; margin: 0 auto"></div>
                                    @foreach ($data['highcharts']['var']['series'] as $key => $gempa)
                                    <hr>
                                    <div id="gempa-{{ $key }}" style="min-width: 310px; height: 480px; margin: 0 auto"></div>
                                    @endforeach
                                </div>
                                @if (!empty($data['highcharts']['tinggi_asap']['series']))
                                <hr>
                                <div class="row p-md">
                                    <div id="visual-0" style="min-width: 310px; height: 480px; margin: 0 auto"></div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
@role('Super Admin')
<script src="{{ asset('vendor/json-viewer/jquery.json-viewer.js') }}"></script>
@endrole
@endsection

@section('add-script')
<script>
    $(document).ready(function () {

        var clipboard = new ClipboardJS('.copy');

        clipboard.on('success', function(e) {
            console.info('Action:', e.action);
            console.info('Text:', e.text);
            console.info('Trigger:', e.trigger);

            e.clearSelection();
        });

        $('button[data-toggle="tooltip"]').tooltip({
            animated: 'fade',
            trigger: 'click'
        });

        @role('Super Admin')
        $('#json-renderer').jsonViewer(@json($data), {collapsed: true});
        @endrole

        var data = @json($data);

        /**
        * Create a global getSVG method that takes an array of charts as an
        * argument
        */
        Highcharts.getSVG = function (charts) {
            var svgArr = [],
                top = 0,
                width = 0;

            Highcharts.each(charts, function (chart) {
                var svg = chart.getSVG(),
                    // Get width/height of SVG for export
                    svgWidth = +svg.match(
                        /^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/
                    )[1],
                    svgHeight = +svg.match(
                        /^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/
                    )[1];

                svg = svg.replace(
                    '<svg',
                    '<g transform="translate(0,' + top + ')" '
                );
                svg = svg.replace('</svg>', '</g>');

                top += svgHeight;
                width = Math.max(width, svgWidth);

                svgArr.push(svg);
            });

            return '<svg height="' + top + '" width="' + width +
                '" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
                svgArr.join('') + '</svg>';
        };

        /**
        * Create a global exportCharts method that takes an array of charts as an
        * argument, and exporting options as the second argument
        */
        Highcharts.exportCharts = function (charts, options) {

            // Merge the options
            options = Highcharts.merge(Highcharts.getOptions().exporting, options);

            // Post to export server
            Highcharts.post(options.url, {
                filename: options.filename || 'chart',
                type: options.type,
                width: options.width,
                svg: Highcharts.getSVG(charts)
            });
        };

        var gempa = Highcharts.chart('gempa', {
            chart: {
                zoomType: 'x',
                type: 'column',
                animation: false,
                renderTo: 'gempa',
                events: {
                    load: function() {
                        this.renderer.image('{{ asset('logo-esdm-magma.png') }}', 80, 2, 80, 38)
                            .add();
                    }
                }
            },
            credits: {
                enabled: true,
                text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
            },
            title: {
                text: 'Grafik Kegempaan {{ $gadd->ga_nama_gapi }}'
            },
            subtitle: {
                text: 'Periode: '+data.periode
            },
            xAxis: {
                categories: data.highcharts.var.categories,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Gempa'
                },
                allowDecimals: false
            },
            tooltip: {
                enabled: true,
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name} - {point.y}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    groupPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: data.highcharts.var.series,
            exporting: {
                enabled: true,
                scale: 1,
                sourceHeight: 720,
                sourceWidth: 1280
            }
        });

        @foreach ($data['highcharts']['var']['series'] as $key=>$gempa)
        var gempa{{$key}} = Highcharts.chart('gempa-{{ $key }}', {
            chart: {
                zoomType: 'x',
                type: 'column',
                animation: false,
                borderColor: '#f1f3f6',
                borderWidth: 1
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: true,
                text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
            },
            title: {
                text: 'Gempa {{ $gempa['name'] }}'
            },
            subtitle: {
                text: 'Periode: '+data.periode
            },
            xAxis: {
                categories: data.highcharts.var.categories,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Gempa'
                },
                allowDecimals: false
            },
            tooltip: {
                enabled: true,
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name} - {point.y}'
            },
            plotOptions: {
                column: {
                    groupPadding: 0.2,
                }
            },
            series: [@json($gempa)],
            exporting: {
                enabled: true,
                scale: 1,
                sourceHeight: 1080,
                sourceWidth: 1920
            }
        });
        @endforeach

        @if (!empty($data['highcharts']['tinggi_asap']['series']))
        var asap = Highcharts.chart('visual-0', {
            chart: {
                type: 'column',
                renderTo: 'visual-0',
                events: {
                    load: function() {
                        this.renderer.image('{{ asset('logo-esdm-magma.png') }}', 80, 2, 80, 38)
                            .add();
                    }
                }
            },
            credits: {
                enabled: true,
                text: 'Highcharts | MAGMA Indonesia - PVMBG, Badan Geologi, Kementerian ESDM'
            },
            title: {
                text: 'Grafik Tinggi Asap {{ $gadd->ga_nama_gapi }}'
            },
            xAxis: {
                categories: data.highcharts.tinggi_asap.categories,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Tinggi Asap (meter dari puncak)'
                },
                allowDecimals: false
            },
            tooltip: {
                enabled: true,
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{point.y} meter'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: data.highcharts.tinggi_asap.series,
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            exporting: {
                enabled: true,
                scale: 1,
                sourceHeight: 360,
                sourceWidth: 800
            }
        });
        @endif

        $('#export-png').click(function () {
            Highcharts.exportCharts([{{ $data['export_chart'] }}]);
        });

        $('#export-pdf').click(function () {
            Highcharts.exportCharts([{{ $data['export_chart'] }}], {
                type: 'application/pdf'
            });
        });

    });
</script>
@endsection