@extends('layouts.default')

@section('title')
    List User
@endsection

@section('sub-title')
    MAGMA - Users
@endsection

@section('breadcrumb')
<ul class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('chamber') }}">Home</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">Users</a>
    </li>
</ul>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-4 col-xl-2">
        <div class="element-wrapper">
            <h6 class="element-header">
                Persebaran Users
            </h6>
            <div class="element-box">
                <div class="el-chart-w">
                    <canvas height="120" id="donutChart" width="120"></canvas>
                    <div class="inside-donut-chart-label">
                        <strong>142</strong>
                        <span>Total Orders</span>
                    </div>
                </div>
                <div class="el-legend">
                    <div class="legend-value-w">
                        <div class="legend-pin" style="background-color: #6896f9;"></div>
                        <div class="legend-value">
                            Processed
                        </div>
                    </div>
                    <div class="legend-value-w">
                        <div class="legend-pin" style="background-color: #85c751;"></div>
                        <div class="legend-value">
                            Cancelled
                        </div>
                    </div>
                    <div class="legend-value-w">
                        <div class="legend-pin" style="background-color: #d97b70;"></div>
                        <div class="legend-value">
                            Pending
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8 col-xl-7">
        <div class="element-wrapper">
            <h6 class="element-header">
                List Users
            </h6>
            <div class="element-box">
                <div class="table-responsive">
                    <table class="table table-lightborder">
                        <thead>
                            <tr>
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Nip
                                </th>
                                <th>
                                    Phone
                                </th>
                                <th>
                                    Email
                                </th>
                                <th class="text-center">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="nowrap">{{ $user->name }}</td>
                                <td>{{ $user->nip }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>                             
                                <td class="text-center">
                                    <div class="status-pill {{ $user->status ? 'green' : 'red' }}" data-title="Tidak Aktif" data-toggle="User Aktif"></div>
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
@endsection