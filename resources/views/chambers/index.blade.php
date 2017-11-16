@extends('layouts.default')

@section('title')
    {{ config('app.name') }}
@endsection

@section('sub-title')
    MAGMA Chamber
@endsection

@section('breadcrumb')
<ul class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('chamber') }}">Home</a>
    </li>
</ul>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="element-wrapper">
            <h6 class="element-header">
                Import MAGMA v1
            </h6>
            <div class="element-box">
                <div class="element-info">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <div class="element-info-with-icon">
                                <div class="element-info-icon">
                                    <div class="os-icon os-icon-wallet-loaded"></div>
                                </div>
                                <div class="element-info-text">
                                    <h5 class="element-inner-header">
                                        Import User
                                    </h5>
                                    <div class="element-inner-desc">
                                        Normalisasi database Users dari MAGMA v1 ke v2 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="row">
                            <div class="col-sm-6 b-r b-b">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        
                                    </div>
                                    <div class="label">
                                        Products Sold
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 b-b">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        47.5K
                                    </div>
                                    <div class="label">
                                        Followers
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        $95
                                    </div>
                                    <div class="label">
                                        Daily Earnings
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        12
                                    </div>
                                    <div class="label">
                                        Products
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="padded b-l b-r">
                            <div class="element-info-with-icon smaller">
                                <div class="element-info-icon">
                                    <div class="os-icon os-icon-bar-chart-stats-up"></div>
                                </div>
                                <div class="element-info-text">
                                    <h5 class="element-inner-header">
                                        Monthly Revenue
                                    </h5>
                                    <div class="element-inner-desc">
                                        Calculated every month
                                    </div>
                                </div>
                            </div>
                            <div class="os-progress-bar blue">
                                <div class="bar-labels">
                                    <div class="bar-label-left">
                                        <span>Accessories</span>
                                        <span class="positive">+10</span>
                                    </div>
                                    <div class="bar-label-right">
                                        <span class="info">72/100</span>
                                    </div>
                                </div>
                                <div class="bar-level-1" style="width: 100%">
                                    <div class="bar-level-2" style="width: 60%">
                                        <div class="bar-level-3" style="width: 20%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="os-progress-bar blue">
                                <div class="bar-labels">
                                    <div class="bar-label-left">
                                        <span>Shoe Sales</span>
                                        <span class="negative">-5</span>
                                    </div>
                                    <div class="bar-label-right">
                                        <span class="info">62/100</span>
                                    </div>
                                </div>
                                <div class="bar-level-1" style="width: 100%">
                                    <div class="bar-level-2" style="width: 40%">
                                        <div class="bar-level-3" style="width: 10%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="os-progress-bar blue">
                                <div class="bar-labels">
                                    <div class="bar-label-left">
                                        <span>New Customers</span>
                                        <span class="positive">+12</span>
                                    </div>
                                    <div class="bar-label-right">
                                        <span class="info">78/100</span>
                                    </div>
                                </div>
                                <div class="bar-level-1" style="width: 100%">
                                    <div class="bar-level-2" style="width: 80%">
                                        <div class="bar-level-3" style="width: 50%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden-lg-down col-xl-4">
                        <div class="padded">
                            <div class="el-tablo bigger">
                                <div class="value">
                                    245
                                </div>
                                <div class="trending trending-up">
                                    <span>12%</span>
                                    <i class="os-icon os-icon-arrow-up2"></i>
                                </div>
                                <div class="label">
                                    Products Sold
                                </div>
                            </div>
                            <div class="el-chart-w">
                                <canvas height="100" id="liteLineChart" width="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="element-wrapper">
            <h6 class="element-header">
                Chamber
            </h6>
            <div class="element-box">
                <div class="element-info">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <div class="element-info-with-icon">
                                <div class="element-info-icon">
                                    <div class="os-icon os-icon-wallet-loaded"></div>
                                </div>
                                <div class="element-info-text">
                                    <h5 class="element-inner-header">
                                        Sales Statistics
                                    </h5>
                                    <div class="element-inner-desc">
                                        Discharge best employed your phase each the of shine. Be met even.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="element-search">
                                <input placeholder="Type to search for products..." type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="row">
                            <div class="col-sm-6 b-r b-b">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        3814
                                    </div>
                                    <div class="label">
                                        Products Sold
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 b-b">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        47.5K
                                    </div>
                                    <div class="label">
                                        Followers
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        $95
                                    </div>
                                    <div class="label">
                                        Daily Earnings
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="el-tablo centered padded">
                                    <div class="value">
                                        12
                                    </div>
                                    <div class="label">
                                        Products
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="padded b-l b-r">
                            <div class="element-info-with-icon smaller">
                                <div class="element-info-icon">
                                    <div class="os-icon os-icon-bar-chart-stats-up"></div>
                                </div>
                                <div class="element-info-text">
                                    <h5 class="element-inner-header">
                                        Monthly Revenue
                                    </h5>
                                    <div class="element-inner-desc">
                                        Calculated every month
                                    </div>
                                </div>
                            </div>
                            <div class="os-progress-bar blue">
                                <div class="bar-labels">
                                    <div class="bar-label-left">
                                        <span>Accessories</span>
                                        <span class="positive">+10</span>
                                    </div>
                                    <div class="bar-label-right">
                                        <span class="info">72/100</span>
                                    </div>
                                </div>
                                <div class="bar-level-1" style="width: 100%">
                                    <div class="bar-level-2" style="width: 60%">
                                        <div class="bar-level-3" style="width: 20%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="os-progress-bar blue">
                                <div class="bar-labels">
                                    <div class="bar-label-left">
                                        <span>Shoe Sales</span>
                                        <span class="negative">-5</span>
                                    </div>
                                    <div class="bar-label-right">
                                        <span class="info">62/100</span>
                                    </div>
                                </div>
                                <div class="bar-level-1" style="width: 100%">
                                    <div class="bar-level-2" style="width: 40%">
                                        <div class="bar-level-3" style="width: 10%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="os-progress-bar blue">
                                <div class="bar-labels">
                                    <div class="bar-label-left">
                                        <span>New Customers</span>
                                        <span class="positive">+12</span>
                                    </div>
                                    <div class="bar-label-right">
                                        <span class="info">78/100</span>
                                    </div>
                                </div>
                                <div class="bar-level-1" style="width: 100%">
                                    <div class="bar-level-2" style="width: 80%">
                                        <div class="bar-level-3" style="width: 50%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden-lg-down col-xl-4">
                        <div class="padded">
                            <div class="el-tablo bigger">
                                <div class="value">
                                    245
                                </div>
                                <div class="trending trending-up">
                                    <span>12%</span>
                                    <i class="os-icon os-icon-arrow-up2"></i>
                                </div>
                                <div class="label">
                                    Products Sold
                                </div>
                            </div>
                            <div class="el-chart-w">
                                <canvas height="100" id="liteLineChart" width="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 col-xl-2">
        <div class="element-wrapper">
            <h6 class="element-header">
                Top Selling Today
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
                New Orders
            </h6>
            <div class="element-box">
                <div class="table-responsive">
                    <table class="table table-lightborder">
                        <thead>
                            <tr>
                                <th>
                                    Customer Name
                                </th>
                                <th>
                                    Products Ordered
                                </th>
                                <th class="text-center">
                                    Status
                                </th>
                                <th class="text-right">
                                    Order Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="nowrap">
                                    John Mayers
                                </td>
                                <td>
                                    <div class="cell-image-list">
                                        <div class="cell-img" style="background-image: url(img/portfolio9.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio2.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio12.jpg)"></div>
                                        <div class="cell-img-more">
                                            + 5 more
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-pill green" data-title="Complete" data-toggle="tooltip"></div>
                                </td>
                                <td class="text-right">
                                    $354
                                </td>
                            </tr>
                            <tr>
                                <td class="nowrap">
                                    Kelly Brans
                                </td>
                                <td>
                                    <div class="cell-image-list">
                                        <div class="cell-img" style="background-image: url(img/portfolio14.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio8.jpg)"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-pill red" data-title="Cancelled" data-toggle="tooltip"></div>
                                </td>
                                <td class="text-right">
                                    $94
                                </td>
                            </tr>
                            <tr>
                                <td class="nowrap">
                                    Tim Howard
                                </td>
                                <td>
                                    <div class="cell-image-list">
                                        <div class="cell-img" style="background-image: url(img/portfolio16.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio14.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio5.jpg)"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-pill green" data-title="Complete" data-toggle="tooltip"></div>
                                </td>
                                <td class="text-right">
                                    $156
                                </td>
                            </tr>
                            <tr>
                                <td class="nowrap">
                                    Joe Trulli
                                </td>
                                <td>
                                    <div class="cell-image-list">
                                        <div class="cell-img" style="background-image: url(img/portfolio1.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio5.jpg)"></div>
                                        <div class="cell-img" style="background-image: url(img/portfolio6.jpg)"></div>
                                        <div class="cell-img-more">
                                            + 2 more
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-pill yellow" data-title="Pending" data-toggle="tooltip"></div>
                                </td>
                                <td class="text-right">
                                    $1,120
                                </td>
                            </tr>
                            <tr>
                                <td class="nowrap">
                                    Jerry Lingard
                                </td>
                                <td>
                                    <div class="cell-image-list">
                                        <div class="cell-img" style="background-image: url(img/portfolio9.jpg)"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-pill green" data-title="Complete" data-toggle="tooltip"></div>
                                </td>
                                <td class="text-right">
                                    $856
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-5 col-xl-3">
        <div class="element-wrapper">
            <h6 class="element-header">
                Support Agents
            </h6>
            <div class="element-box-tp">
                <div class="profile-tile">
                    <div class="profile-tile-box">
                        <div class="pt-avatar-w">
                            <img alt="" src="img/avatar1.jpg">
                        </div>
                        <div class="pt-user-name">
                            Mark Parson
                        </div>
                    </div>
                    <div class="profile-tile-meta">
                        <ul>
                            <li>
                                Last Login:
                                <strong>Online Now</strong>
                            </li>
                            <li>
                                Tickets:
                                <strong>12</strong>
                            </li>
                            <li>
                                Response Time:
                                <strong>2 hours</strong>
                            </li>
                        </ul>
                        <div class="pt-btn">
                            <a class="btn btn-success btn-sm" href="#">Send Message</a>
                        </div>
                    </div>
                </div>
                <div class="profile-tile">
                    <div class="profile-tile-box">
                        <div class="pt-avatar-w">
                            <img alt="" src="img/avatar1.jpg">
                        </div>
                        <div class="pt-user-name">
                            Mark Parson
                        </div>
                    </div>
                    <div class="profile-tile-meta">
                        <ul>
                            <li>
                                Last Login:
                                <strong>Online Now</strong>
                            </li>
                            <li>
                                Tickets:
                                <strong>12</strong>
                            </li>
                            <li>
                                Response Time:
                                <strong>2 hours</strong>
                            </li>
                        </ul>
                        <div class="pt-btn">
                            <a class="btn btn-secondary btn-sm" href="#">Send Message</a>
                        </div>
                    </div>
                </div>
                <div class="profile-tile hidden-md">
                    <div class="profile-tile-box">
                        <div class="pt-avatar-w">
                            <img alt="" src="img/avatar1.jpg">
                        </div>
                        <div class="pt-user-name">
                            Mark Parson
                        </div>
                    </div>
                    <div class="profile-tile-meta">
                        <ul>
                            <li>
                                Last Login:
                                <strong>Online Now</strong>
                            </li>
                            <li>
                                Tickets:
                                <strong>12</strong>
                            </li>
                            <li>
                                Response Time:
                                <strong>2 hours</strong>
                            </li>
                        </ul>
                        <div class="pt-btn">
                            <a class="btn btn-success btn-sm" href="#">Send Message</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-7 col-xl-12">
        <div class="element-wrapper">
            <h6 class="element-header">
                Unique Visitors Graph
            </h6>
            <div class="element-box">
                <div class="os-tabs-w">
                    <div class="os-tabs-controls">
                        <ul class="nav nav-tabs smaller">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab_overview">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_sales">Sales</a>
                            </li>
                        </ul>
                        <ul class="nav nav-pills smaller hidden-xl-down">
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#">7 Days</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#">14 Days</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#">Last Month</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_overview">
                            <div class="el-tablo">
                                <div class="label">
                                    Unique Visitors
                                </div>
                                <div class="value">
                                    12,537
                                </div>
                            </div>
                            <div class="el-chart-w">
                                <canvas height="150px" id="lineChart" width="600px"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_sales"></div>
                        <div class="tab-pane" id="tab_conversion"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="element-wrapper">
            <h6 class="element-header">
                Recent Orders
            </h6>
            <div class="element-box-tp">
                <div class="controls-above-table">
                    <div class="row">
                        <div class="col-sm-6">
                            <a class="btn btn-sm btn-secondary" href="#">Download CSV</a>
                            <a class="btn btn-sm btn-secondary" href="#">Archive</a>
                            <a class="btn btn-sm btn-danger" href="#">Delete</a>
                        </div>
                        <div class="col-sm-6">
                            <form class="form-inline justify-content-sm-end">
                                <input class="form-control form-control-sm rounded bright" placeholder="Search" type="text">
                                <select class="form-control form-control-sm rounded bright">
                                    <option selected="selected" value="">
                                        Select Status
                                    </option>
                                    <option value="Pending">
                                        Pending
                                    </option>
                                    <option value="Active">
                                        Active
                                    </option>
                                    <option value="Cancelled">
                                        Cancelled
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-lg table-v2 table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <input class="form-control" type="checkbox">
                                </th>
                                <th>
                                    Customer Name
                                </th>
                                <th>
                                    Country
                                </th>
                                <th>
                                    Order Total
                                </th>
                                <th>
                                    Referral
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input class="form-control" type="checkbox">
                                </td>
                                <td>
                                    John Mayers
                                </td>
                                <td>
                                    <img alt="" src="img/flags-icons/us.png" width="25px">
                                </td>
                                <td class="text-right">
                                    $245
                                </td>
                                <td>
                                    Organic
                                </td>
                                <td class="text-center">
                                    <div class="status-pill green" data-title="Complete" data-toggle="tooltip"></div>
                                </td>
                                <td class="row-actions">
                                    <a href="#">
                                        <i class="os-icon os-icon-pencil-2"></i>
                                    </a>
                                    <a href="#">
                                        <i class="os-icon os-icon-link-3"></i>
                                    </a>
                                    <a class="danger" href="#">
                                        <i class="os-icon os-icon-database-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <input class="form-control" type="checkbox">
                                </td>
                                <td>
                                    Mike Astone
                                </td>
                                <td>
                                    <img alt="" src="img/flags-icons/fr.png" width="25px">
                                </td>
                                <td class="text-right">
                                    $154
                                </td>
                                <td>
                                    Organic
                                </td>
                                <td class="text-center">
                                    <div class="status-pill red" data-title="Cancelled" data-toggle="tooltip"></div>
                                </td>
                                <td class="row-actions">
                                    <a href="#">
                                        <i class="os-icon os-icon-pencil-2"></i>
                                    </a>
                                    <a href="#">
                                        <i class="os-icon os-icon-link-3"></i>
                                    </a>
                                    <a class="danger" href="#">
                                        <i class="os-icon os-icon-database-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <input class="form-control" type="checkbox">
                                </td>
                                <td>
                                    Kira Knight
                                </td>
                                <td>
                                    <img alt="" src="img/flags-icons/us.png" width="25px">
                                </td>
                                <td class="text-right">
                                    $23
                                </td>
                                <td>
                                    Adwords
                                </td>
                                <td class="text-center">
                                    <div class="status-pill green" data-title="Complete" data-toggle="tooltip"></div>
                                </td>
                                <td class="row-actions">
                                    <a href="#">
                                        <i class="os-icon os-icon-pencil-2"></i>
                                    </a>
                                    <a href="#">
                                        <i class="os-icon os-icon-link-3"></i>
                                    </a>
                                    <a class="danger" href="#">
                                        <i class="os-icon os-icon-database-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <input class="form-control" type="checkbox">
                                </td>
                                <td>
                                    Jessica Bloom
                                </td>
                                <td>
                                    <img alt="" src="img/flags-icons/ca.png" width="25px">
                                </td>
                                <td class="text-right">
                                    $112
                                </td>
                                <td>
                                    Organic
                                </td>
                                <td class="text-center">
                                    <div class="status-pill green" data-title="Complete" data-toggle="tooltip"></div>
                                </td>
                                <td class="row-actions">
                                    <a href="#">
                                        <i class="os-icon os-icon-pencil-2"></i>
                                    </a>
                                    <a href="#">
                                        <i class="os-icon os-icon-link-3"></i>
                                    </a>
                                    <a class="danger" href="#">
                                        <i class="os-icon os-icon-database-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <input class="form-control" type="checkbox">
                                </td>
                                <td>
                                    Gary Lineker
                                </td>
                                <td>
                                    <img alt="" src="img/flags-icons/ca.png" width="25px">
                                </td>
                                <td class="text-right">
                                    $64
                                </td>
                                <td>
                                    Organic
                                </td>
                                <td class="text-center">
                                    <div class="status-pill yellow" data-title="Pending" data-toggle="tooltip"></div>
                                </td>
                                <td class="row-actions">
                                    <a href="#">
                                        <i class="os-icon os-icon-pencil-2"></i>
                                    </a>
                                    <a href="#">
                                        <i class="os-icon os-icon-link-3"></i>
                                    </a>
                                    <a class="danger" href="#">
                                        <i class="os-icon os-icon-database-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="controls-below-table">
                    <div class="table-records-info">
                        Showing records 1 - 5
                    </div>
                    <div class="table-records-pages">
                        <ul>
                            <li>
                                <a href="#">Previous</a>
                            </li>
                            <li>
                                <a class="current" href="#">1</a>
                            </li>
                            <li>
                                <a href="#">2</a>
                            </li>
                            <li>
                                <a href="#">3</a>
                            </li>
                            <li>
                                <a href="#">4</a>
                            </li>
                            <li>
                                <a href="#">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection