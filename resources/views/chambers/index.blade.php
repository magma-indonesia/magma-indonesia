@extends('layouts.default')

@section('title')
    {{ config('app.name') }}
@endsection

@section('content-body')   
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Selamat Datang di MAGMA Chamber
                </h2>

                <p>
                    Pusat kontrol data
                    <strong>MAGMA</strong> meliputi kebencanaan Gunung Api, Gerakan Tanah, Gempa Bumi dan Tsunami.
                </p>
                @include('includes.alert')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="closebox">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        Informasi dan Statistik
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="small">
                                    <i class="fa fa-bolt"></i> Jumlah Data MAGMA
                                </div>
                                <div>
                                    <h1 class="font-extra-bold m-t-xl m-b-xs">
                                        226,802
                                    </h1>
                                    <small>Data Gunung Api</small>
                                </div>
                                <div class="small m-t-xl">
                                    <i class="fa fa-clock-o"></i> Data dari Mei 2015
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center small">
                                    <i class="fa fa-laptop"></i> Data User Aktif
                                </div>
                                <div class="flot-chart" style="height: 160px">
                                    <div class="flot-chart-content" id="flot-line-chart"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="small">
                                    <i class="fa fa-clock-o"></i> Active duration
                                </div>
                                <div>
                                    <h1 class="font-extra-bold m-t-xl m-b-xs">
                                        10 Months
                                    </h1>
                                    <small>And four weeks</small>
                                </div>
                                <div class="small m-t-xl">
                                    <i class="fa fa-clock-o"></i> Last active in 12.10.2015
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Last update: 21.05.2015
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-body text-center h-200">
                        <i class="pe-7s-graph1 fa-4x"></i>

                        <h1 class="m-xs">$1 206,90</h1>

                        <h3 class="font-extra-bold no-margins text-magma">
                            All Income
                        </h3>
                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit vestibulum.</small>
                    </div>
                    <div class="panel-footer">
                        This is standard panel footer
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Users Activity</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-share fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h3 class="m-b-xs">210</h3>
                            <span class="font-bold no-margins">
                                Social users
                            </span>

                            <div class="progress m-t-xs full progress-small">
                                <div style="width: 55%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="55" role="progressbar" class=" progress-bar progress-bar-magma">
                                    <span class="sr-only">35% Complete (success)</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stats-label">Pages / Visit</small>
                                    <h4>7.80</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stats-label">% New Visits</small>
                                    <h4>76.43%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        This is standard panel footer
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Page Views</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-monitor fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h1 class="text-magma">860k+</h1>
                            <span class="font-bold no-margins">
                                Social users
                            </span>
                            <br/>
                            <small>
                                Lorem Ipsum is simply dummy text of the printing and
                                <strong>typesetting industry
                                </strong>. Lorem Ipsum has been.
                            </small>
                        </div>
                    </div>
                    <div class="panel-footer">
                        This is standard panel footer
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Today income</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-cash fa-4x"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Today</small>
                                    <h4>$230,00 </h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Last week</small>
                                    <h4>$7 980,60
                                        <i class="fa fa-level-up text-magma"></i>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        This is standard panel footer
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="closebox">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        Last active
                    </div>
                    <div class="panel-body list">
                        <div class="stats-title pull-left">
                            <h4>Activity</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-science fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <span class="font-bold no-margins">
                                Social users
                            </span>
                            <br/>
                            <small>
                                Lorem Ipsum is simply dummy text of the printing simply all dummy text. Lorem Ipsum is simply dummy text of the printing
                                and typesetting industry. Lorem Ipsum has been.
                            </small>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma">300,102</h3>

                                <div class="font-bold">98%
                                    <i class="fa fa-level-up text-magma"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold text-magma">280,200</h3>

                                <div class="font-bold">98%
                                    <i class="fa fa-level-up text-magma"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-md">
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold ">120,108</h3>

                                <div class="font-bold">38%
                                    <i class="fa fa-level-down"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h3 class="no-margins font-extra-bold ">450,600</h3>

                                <div class="font-bold">28%
                                    <i class="fa fa-level-down"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer">
                        This is standard panel footer
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="closebox">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        Recently active projects
                    </div>
                    <div class="panel-body list">
                        <div class="table-responsive project-list">
                            <table class="table table-striped">
                                <thead>
                                    <tr>

                                        <th colspan="2">Project</th>
                                        <th>Completed</th>
                                        <th>Task</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="i-checks" checked>
                                        </td>
                                        <td>Contract with Zender Company
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Created 14.08.2015</small>
                                        </td>
                                        <td>
                                            <span class="pie">1/5</span>
                                        </td>
                                        <td>
                                            <strong>20%</strong>
                                        </td>
                                        <td>Jul 14, 2013</td>
                                        <td>
                                            <a href="">
                                                <i class="fa fa-check text-magma"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="i-checks">
                                        </td>
                                        <td>There are many variations of passages
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Created 21.07.2015</small>
                                        </td>
                                        <td>
                                            <span class="pie">1/4</span>
                                        </td>
                                        <td>
                                            <strong>40%</strong>
                                        </td>
                                        <td>Jul 16, 2013</td>
                                        <td>
                                            <a href="">
                                                <i class="fa fa-check text-navy"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="i-checks" checked>
                                        </td>
                                        <td>Contrary to popular belief
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Created 12.06.2015</small>
                                        </td>
                                        <td>
                                            <span class="pie">0.52/1.561</span>
                                        </td>
                                        <td>
                                            <strong>75%</strong>
                                        </td>
                                        <td>Jul 18, 2013</td>
                                        <td>
                                            <a href="">
                                                <i class="fa fa-check text-navy"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="i-checks">
                                        </td>
                                        <td>Gamma project
                                            <br/>
                                            <small>
                                                <i class="fa fa-clock-o"></i> Created 06.03.2015</small>
                                        </td>
                                        <td>
                                            <span class="pie">226/360</span>
                                        </td>
                                        <td>
                                            <strong>16%</strong>
                                        </td>
                                        <td>Jul 22, 2013</td>
                                        <td>
                                            <a href="">
                                                <i class="fa fa-check text-navy"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="closebox">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        Activity
                    </div>
                    <div class="panel-body list">

                        <div class="pull-right">
                            <a href="#" class="btn btn-xs btn-default">Today</a>
                            <a href="#" class="btn btn-xs btn-default">Month</a>
                        </div>
                        <div class="panel-title">Last Activity</div>
                        <small class="fo">This is simple example</small>
                        <div class="list-item-container">
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">2,773</h3>
                                <small>Tota Messages Sent</small>
                                <div class="pull-right font-bold">98%
                                    <i class="fa fa-level-up text-magma"></i>
                                </div>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-color3">4,422</h3>
                                <small>Last activity</small>
                                <div class="pull-right font-bold">13%
                                    <i class="fa fa-level-down text-color3"></i>
                                </div>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-color3">9,180</h3>
                                <small>Monthly income</small>
                                <div class="pull-right font-bold">22%
                                    <i class="fa fa-bolt text-color3"></i>
                                </div>
                            </div>
                            <div class="list-item">
                                <h3 class="no-margins font-extra-bold text-magma">1,450</h3>
                                <small>Tota Messages Sent</small>
                                <div class="pull-right font-bold">44%
                                    <i class="fa fa-level-up text-magma"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-vendor-script')

    <script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('vendor/jquery-flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('vendor/jquery-flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('vendor/flot.curvedlines/curvedLines.js') }}"></script>
    <script src="{{ asset('vendor/jquery.flot.spline/index.js') }}"></script>
    <script src="{{ asset('vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
    
@endsection

@section('add-script')
    <script src="{{ asset('scripts/charts.js') }}"></script>
    <script>

        $(function () {

            /**
             * Flot charts data and options
             */
            var data1 = [[0, 55], [1, 48], [2, 40], [3, 36], [4, 40], [5, 60], [6, 50], [7, 51]];
            var data2 = [[0, 56], [1, 49], [2, 41], [3, 38], [4, 46], [5, 67], [6, 57], [7, 59]];

            var chartUsersOptions = {
                series: {
                    splines: {
                        show: true,
                        tension: 0.4,
                        lineWidth: 1,
                        fill: 0.4
                    },
                },
                grid: {
                    tickColor: "#f0f0f0",
                    borderWidth: 1,
                    borderColor: 'f0f0f0',
                    color: '#6a6c6f'
                },
                colors: ["#007fff", "#efefef"],
            };

            $.plot($("#flot-line-chart"), [data1, data2], chartUsersOptions);

            /**
             * Flot charts 2 data and options
             */
            var chartIncomeData = [
                {
                    label: "line",
                    data: [[1, 10], [2, 26], [3, 16], [4, 36], [5, 32], [6, 51]]
                }
            ];

            var chartIncomeOptions = {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 0,
                        fill: true,
                        fillColor: "#64cc34"

                    }
                },
                colors: ["#62cb31"],
                grid: {
                    show: false
                },
                legend: {
                    show: false
                }
            };

            $.plot($("#flot-income-chart"), chartIncomeData, chartIncomeOptions);



        });

    </script>
@endsection