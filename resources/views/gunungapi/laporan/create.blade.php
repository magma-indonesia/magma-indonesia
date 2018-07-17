@extends('layouts.default')

@section('title')
    Create VAR
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
                            <a href="{{ route('chambers.laporan.create') }}">Buat VAR</a>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Buat Laporan Gunung Api baru
                </h2>
                <small>Buat laporan gunung api terbaru.</small>
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
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                            <a class="closebox"><i class="fa fa-times"></i></a>
                        </div>
                        Example fo wizard form
                    </div>
                    <div class="panel-body">
    
                        <form name="simpleForm" novalidate id="simpleForm" action="#" method="post">
    
                            <div class="text-center m-b-md" id="wizardControl">
    
                                <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - Personal data</a>
                                <a class="btn btn-default" href="#step2" data-toggle="tab">Step 2 - Payment data</a>
                                <a class="btn btn-default" href="#step3" data-toggle="tab">Step 3 - Approval</a>
    
                            </div>
    
                            <div class="tab-content">
                            <div id="step1" class="p-m tab-pane active">
    
                                <div class="row">
                                    <div class="col-lg-3 text-center">
                                        <i class="pe-7s-user fa-5x text-muted"></i>
                                        <p class="small m-t-md">
                                            <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.
                                            <br/><br/>Lorem Ipsum has been the industry's dummy text of the printing and typesetting
                                        </p>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Username</label>
                                                <input type="" value="" id="" class="form-control" name="username" placeholder="username">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Password</label>
                                                <input type="password" value="" id="" class="form-control" name="" placeholder="******" name="password">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Company</label>
                                                <input type="text" value="" id="" class="form-control" name="" placeholder="Company Name" name="company">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Email Address</label>
                                                <input type="" value="" id="" class="form-control" name="" placeholder="user@email.com" name="email">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Country</label>
                                                <input type="text" value="" id="" class="form-control" name="" name="country" placeholder="UK">
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="text-right m-t-xs">
                                    <a class="btn btn-default prev" href="#">Previous</a>
                                    <a class="btn btn-default next" href="#">Next</a>
                                </div>
    
                            </div>
    
                            <div id="step2" class="p-m tab-pane">
    
                                <div class="row">
                                    <div class="col-lg-3 text-center">
                                        <i class="pe-7s-credit fa-5x text-muted"></i>
                                        <p class="small m-t-md">
                                            <strong>It is a long</strong> established fact that a reader will be distracted by the readable
                                            <br/><br/>Many desktop publishing packages and web page editors now use
                                        </p>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Name on Card</label>
                                                <input type="" value="" id="" class="form-control" name="card_name">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Card Number</label>
                                                <input type="text" value="" id="" class="form-control" name="" name="card_number">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Billing Address</label>
                                                <input type="text" value="" id="" class="form-control" name="" name="billing_address">
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <div class="row">
                                                    <div class="col-xs-4 form-group">
                                                        <label>CVC</label>
                                                        <input class="form-control" placeholder="ex. 381"  type="text" name="cvc">
                                                    </div>
                                                    <div class="col-xs-4 form-group">
                                                        <label>Expiration</label>
                                                        <input class="form-control" placeholder="MM" type="text" name="expire_month">
                                                    </div>
                                                    <div class="col-xs-4 form-group">
                                                        <label></label>
                                                        <input class="form-control" placeholder="YYYY" type="text" name="expire_year">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right m-t-xs">
                                    <a class="btn btn-default prev" href="#">Previous</a>
                                    <a class="btn btn-default next" href="#">Next</a>
                                </div>
    
                            </div>
                            <div id="step3" class="tab-pane">
                                <div class="row text-center m-t-lg m-b-lg">
                                    <div class="col-lg-12">
                                        <i class="pe-7s-check fa-5x text-muted"></i>
                                        <p class="small m-t-md">
                                            <strong>There are many</strong> variations of passages of Lorem Ipsum available, but the majority have suffered
                                        </p>
                                    </div>
                                    <div class="checkbox col-lg-12">
                                        <input type="checkbox" class="i-checks approveCheck" name="approve">
                                        Approve this form
                                    </div>
                                </div>
                                <div class="text-right m-t-xs">
                                    <a class="btn btn-default prev" href="#">Previous</a>
                                    <a class="btn btn-default next" href="#">Next</a>
                                    <a class="btn btn-success submitWizard" href="#">Submit</a>
                                </div>
                            </div>
                            </div>
                        </form>
    
                        <div class="m-t-md">
    
                            <p>
                                This is an example of a wizard form which can be easy adjusted. Since each step is a tab, and each clik to next tab is a function you can easily add validation or any other functionality.
                            </p>
    
                        </div>
    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection