@extends('layouts.default')

@section('title')
    PVMBG League - 2018/2019
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
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
                        <li class="active">
                            <a href="{{ route('chambers.fun.fpl.index') }}">FPL Table</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Data Liga FPL
                </h2>
                <small>Data liga FPL PVMBG</small>
            </div>
        </div>
    </div>
@endsection

@section('content-body')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        PVMBG League
                    </div>
                    <div class="panel-body">
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <a href="#" class="btn btn-outline btn-block btn-magma" type="button">Bingung mau isi apa</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-body">
                        {!! $chart->container() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-fpl" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Rank</th>
                                        <th>Teams</th>
                                        <th>Manager</th>
                                        <th>Total Points</th>
                                        <th>Points Race</th>
                                        <th>Team Value</th>
                                        <th>GW Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fpls as $key => $fpl)
                                    <tr>
                                        <td>
                                            @if($fpl['movement'] == 'up')
                                            <span class="fa fa-angle-double-up fa-2x text-success"></span>
                                            @elseif($fpl['movement'] == 'down')
                                            <span class="fa fa-angle-double-down fa-2x text-danger"></span>
                                            @endif
                                        </td>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $fpl['entry_name'] }}</td>
                                        <td>{{ $fpl['player_name'] }}</td>
                                        <td>{{ $fpl['total'] }}</td>
                                        <td>{{ $fpl['total']-$top }}</td>
                                        <td>{{ $value[$key]['value'] + $value[$key]['bank'] }}</td>
                                        <td>{{ $fpl['event_total'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')
    <!-- DataTables -->
    <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- DataTables buttons scripts -->
    <script src="{{ asset('vendor/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <!-- Echarts scripts -->    
    <script src="{{ asset('vendor/echarts/echarts-en.min.js') }}" charset="utf-8"></script>
    {!! $chart->script() !!}
@endsection

@section('add-script')
    <script>
        $(document).ready(function () {
            // Initialize table
            $('#table-fpl').dataTable({
                columnDefs: [
                    { 
                        "targets": 0,
                        "orderable": false,
                        "searchable": false
                    }
                ],
                order: [
                    [
                        1, 'asc'
                    ]
                ],
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[40, 50, 100, -1], [40, 50, 100, "All"]],
                buttons: [
                    { extend: 'copy', className: 'btn-sm'},
                    { extend: 'csv', title: 'PVMBG FPL League', className: 'btn-sm', exportOptions: { columns: [ 1, 2, 3, 4, 5, 6,7 ]} },
                    { extend: 'pdf', title: 'PVMBG FPL League', className: 'btn-sm', exportOptions: { columns: [ 1, 2, 3, 4, 5, 6,7 ]} },
                    { extend: 'print', className: 'btn-sm', exportOptions: { columns: [ 1, 2, 3, 4, 5, 6,7 ]} }
                ]

            });
        });
    </script>
@endsection