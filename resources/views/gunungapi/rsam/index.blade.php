@extends('layouts.default')

@section('title')
    Gunung Api | RSAM
@endsection

@section('add-vendor-css')
    <link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/stocktools/gui.css">
    <link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/annotations/popup.css">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}" />
@endsection


@section('content-header')
    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="{{ route('chambers.index') }}">Chamber</a></li>
                        <li>
                            <span>Gunung Api</span>
                        </li>
                        <li>
                            <span>RSAM</span>
                        </li>
                        <li class="active">
                            <span>Result </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Grafik RSAM Gunung Api
                </h2>
                <small>Periode Tanggal, data per 10 menit.</small>
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
                    Pilih Paramater
                </div>
                <div class="panel-body">
                    <form role="form" id="form" method="POST" action="{{ URL::signedRoute('chambers.json.rsam') }}">
                        @csrf
                        <div class="tab-content">
                            <div id="step1" class="p-m tab-pane active">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <i class="pe-7s-note fa-4x text-muted"></i>
                                        <p class="m-t-md">
                                            <strong>Masukkan parameter yang dibutuhkan</strong>, gunakan form menu ini untuk membuat grafik data RSAM.
                                        </p>
                                    </div>

                                    <div class="col-lg-8">
                                        @if ($errors->any())
                                        <div class="row m-b-md">
                                            <div class="col-lg-12">
                                                <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row p-md">
                                            <div class="form-group">
                                                <label class="control-label">Channel</label>
                                                <select id="channel" class="form-control m-b" name="channel">
                                                    <option value="ABNG_SHZ_VG_00">ABNG_SHZ_VG_00</option>
                                                    <option value="AGG_EHZ_VG_00">AGG_EHZ_VG_00</option>
                                                    <option value="AKB_EHZ_VG_00">AKB_EHZ_VG_00</option>
                                                    <option value="AMBL_EHE_VG_00">AMBL_EHE_VG_00</option>
                                                    <option value="AMBL_EHN_VG_00">AMBL_EHN_VG_00</option>
                                                    <option value="AMBL_EHZ_VG_00">AMBL_EHZ_VG_00</option>
                                                    <option value="ARK_EHZ_VG_00">ARK_EHZ_VG_00</option>
                                                    <option value="ASOP_EHZ_VG_00">ASOP_EHZ_VG_00</option>
                                                    <option value="AWU_EHZ_VG_00">AWU_EHZ_VG_00</option>
                                                    <option value="AWU1_EHZ_VG_00">AWU1_EHZ_VG_00</option>
                                                    <option value="BANG_EHZ_VG_00">BANG_EHZ_VG_00</option>
                                                    <option value="BARI_BHE_VG_00">BARI_BHE_VG_00</option>
                                                    <option value="BARI_BHN_VG_00">BARI_BHN_VG_00</option>
                                                    <option value="BARI_BHZ_VG_00">BARI_BHZ_VG_00</option>
                                                    <option value="BATU_BHZ_VG_00">BATU_BHZ_VG_00</option>
                                                    <option value="BBGZ_EHZ_VG_00">BBGZ_EHZ_VG_00</option>
                                                    <option value="BBL_EHZ_VG_00">BBL_EHZ_VG_00</option>
                                                    <option value="BDL_SHE_VG_00">BDL_SHE_VG_00</option>
                                                    <option value="BDL_SHN_VG_00">BDL_SHN_VG_00</option>
                                                    <option value="BDL_SHZ_VG_00">BDL_SHZ_VG_00</option>
                                                    <option value="BGM_EHZ_VG_00">BGM_EHZ_VG_00</option>
                                                    <option value="BLDA_EHZ_VG_00">BLDA_EHZ_VG_00</option>
                                                    <option value="BOL_EHZ_VG_00">BOL_EHZ_VG_00</option>
                                                    <option value="BRMO_EHZ_VG_00">BRMO_EHZ_VG_00</option>
                                                    <option value="BTP_EHZ_VG_00">BTP_EHZ_VG_00</option>
                                                    <option value="BTPL_EHZ_VG_00">BTPL_EHZ_VG_00</option>
                                                    <option value="BTR_EHZ_VG_00">BTR_EHZ_VG_00</option>
                                                    <option value="CEGI_BHZ_VG_00">CEGI_BHZ_VG_00</option>
                                                    <option value="CH2_EHZ_VG_00">CH2_EHZ_VG_00</option>
                                                    <option value="CH3_EHZ_VG_00">CH3_EHZ_VG_00</option>
                                                    <option value="CH4_EHZ_VG_00">CH4_EHZ_VG_00</option>
                                                    <option value="CH5_EHZ_VG_00">CH5_EHZ_VG_00</option>
                                                    <option value="CH6_EHZ_VG_00">CH6_EHZ_VG_00</option>
                                                    <option value="CLKX_EHN_VG_00">CLKX_EHN_VG_00</option>
                                                    <option value="CLKY_EHE_VG_00">CLKY_EHE_VG_00</option>
                                                    <option value="CLKZ_EHZ_VG_00">CLKZ_EHZ_VG_00</option>
                                                    <option value="CLM_BHE_VG_00">CLM_BHE_VG_00</option>
                                                    <option value="CLM_BHN_VG_00">CLM_BHN_VG_00</option>
                                                    <option value="CLM_BHZ_VG_00">CLM_BHZ_VG_00</option>
                                                    <option value="CMEZ_EHZ_VG_00">CMEZ_EHZ_VG_00</option>
                                                    <option value="CTK_SHE_VG_00">CTK_SHE_VG_00</option>
                                                    <option value="CTK_SHN_VG_00">CTK_SHN_VG_00</option>
                                                    <option value="CTK_SHZ_VG_00">CTK_SHZ_VG_00</option>
                                                    <option value="CTRZ_EHZ_VG_00">CTRZ_EHZ_VG_00</option>
                                                    <option value="CTS_EHZ_VG_00">CTS_EHZ_VG_00</option>
                                                    <option value="DELS_EHZ_VG_00">DELS_EHZ_VG_00</option>
                                                    <option value="DKA_EHZ_VG_00">DKA_EHZ_VG_00</option>
                                                    <option value="DKB_EHZ_VG_00">DKB_EHZ_VG_00</option>
                                                    <option value="DKN_EHZ_VG_00">DKN_EHZ_VG_00</option>
                                                    <option value="DNU_EHZ_VG_00">DNU_EHZ_VG_00</option>
                                                    <option value="DUKU_BHZ_VG_00">DUKU_BHZ_VG_00</option>
                                                    <option value="EBU_EHZ_VG_00">EBU_EHZ_VG_00</option>
                                                    <option value="EMP_EHZ_VG_00">EMP_EHZ_VG_00</option>
                                                    <option value="FORA_EHZ_VG_00">FORA_EHZ_VG_00</option>
                                                    <option value="GBO_EHZ_VG_00">GBO_EHZ_VG_00</option>
                                                    <option value="GBR_SHZ_VG_00">GBR_SHZ_VG_00</option>
                                                    <option value="GDEZ_EHZ_VG_00">GDEZ_EHZ_VG_00</option>
                                                    <option value="GGSL_BDF_VG_00">GGSL_BDF_VG_00</option>
                                                    <option value="GGSL_BHE_VG_00">GGSL_BHE_VG_00</option>
                                                    <option value="GGSL_BHN_VG_00">GGSL_BHN_VG_00</option>
                                                    <option value="GGSL_BHZ_VG_00">GGSL_BHZ_VG_00</option>
                                                    <option value="GMK_EHZ_VG_00">GMK_EHZ_VG_00</option>
                                                    <option value="GOAZ_EHZ_VG_00">GOAZ_EHZ_VG_00</option>
                                                    <option value="GSB_EHZ_VG_00">GSB_EHZ_VG_00</option>
                                                    <option value="GTOH_EHZ_VG_00">GTOH_EHZ_VG_00</option>
                                                    <option value="GUCI_EHZ_VG_00">GUCI_EHZ_VG_00</option>
                                                    <option value="HBGA_EHZ_VG_00">HBGA_EHZ_VG_00</option>
                                                    <option value="HODO_EHZ_VG_00">HODO_EHZ_VG_00</option>
                                                    <option value="IBU_EHZ_VG_00">IBU_EHZ_VG_00</option>
                                                    <option value="IJEN_EHZ_VG_00">IJEN_EHZ_VG_00</option>
                                                    <option value="INFR_EHZ_VG_00">INFR_EHZ_VG_00</option>
                                                    <option value="IYA2_EHZ_VG_00">IYA2_EHZ_VG_00</option>
                                                    <option value="IYA3_EHZ_VG_00">IYA3_EHZ_VG_00</option>
                                                    <option value="JPT_EHZ_VG_00">JPT_EHZ_VG_00</option>
                                                    <option value="JRMZ_EHZ_VG_00">JRMZ_EHZ_VG_00</option>
                                                    <option value="KALO_EHE_VG_00">KALO_EHE_VG_00</option>
                                                    <option value="KALO_EHN_VG_00">KALO_EHN_VG_00</option>
                                                    <option value="KALO_EHZ_VG_00">KALO_EHZ_VG_00</option>
                                                    <option value="KBY_EHZ_VG_00">KBY_EHZ_VG_00</option>
                                                    <option value="KBY_SHZ_VG_00">KBY_SHZ_VG_00</option>
                                                    <option value="KDP_SHE_VG_00">KDP_SHE_VG_00</option>
                                                    <option value="KDP_SHN_VG_00">KDP_SHN_VG_00</option>
                                                    <option value="KDP_SHZ_VG_00">KDP_SHZ_VG_00</option>
                                                    <option value="KIE_EHZ_VG_00">KIE_EHZ_VG_00</option>
                                                    <option value="KIN_EHZ_VG_00">KIN_EHZ_VG_00</option>
                                                    <option value="KLA_EHE_VG_00">KLA_EHE_VG_00</option>
                                                    <option value="KLA_EHN_VG_00">KLA_EHN_VG_00</option>
                                                    <option value="KLA_EHZ_VG_00">KLA_EHZ_VG_00</option>
                                                    <option value="KLAS_EHZ_VG_00">KLAS_EHZ_VG_00</option>
                                                    <option value="KLB_EHZ_VG_00">KLB_EHZ_VG_00</option>
                                                    <option value="KLM_EHZ_VG_00">KLM_EHZ_VG_00</option>
                                                    <option value="KLMT_EHZ_VG_00">KLMT_EHZ_VG_00</option>
                                                    <option value="KMRA_EHZ_VG_00">KMRA_EHZ_VG_00</option>
                                                    <option value="KOAK_EHZ_VG_00">KOAK_EHZ_VG_00</option>
                                                    <option value="KPLO_EHZ_VG_00">KPLO_EHZ_VG_00</option>
                                                    <option value="KRA1_EHZ_VG_00">KRA1_EHZ_VG_00</option>
                                                    <option value="KRA2_EHZ_VG_00">KRA2_EHZ_VG_00</option>
                                                    <option value="KRA3_EHZ_VG_00">KRA3_EHZ_VG_00</option>
                                                    <option value="KRC1_EHZ_VG_00">KRC1_EHZ_VG_00</option>
                                                    <option value="KRC2_EHZ_VG_00">KRC2_EHZ_VG_00</option>
                                                    <option value="KTH_EHZ_VG_00">KTH_EHZ_VG_00</option>
                                                    <option value="KUBU_BDF_VG_00">KUBU_BDF_VG_00</option>
                                                    <option value="KUBU_BHE_VG_00">KUBU_BHE_VG_00</option>
                                                    <option value="KUBU_BHN_VG_00">KUBU_BHN_VG_00</option>
                                                    <option value="KUBU_BHZ_VG_00">KUBU_BHZ_VG_00</option>
                                                    <option value="KWG_EHZ_VG_00">KWG_EHZ_VG_00</option>
                                                    <option value="KWHG_EHZ_VG_00">KWHG_EHZ_VG_00</option>
                                                    <option value="KWR_SHZ_VG_00">KWR_SHZ_VG_00</option>
                                                    <option value="LBLG_EHZ_VG_00">LBLG_EHZ_VG_00</option>
                                                    <option value="LDLR_BHX_VG_00">LDLR_BHX_VG_00</option>
                                                    <option value="LDLR_BHY_VG_00">LDLR_BHY_VG_00</option>
                                                    <option value="LDLR_BHZ_VG_00">LDLR_BHZ_VG_00</option>
                                                    <option value="LEKR_EHZ_VG_00">LEKR_EHZ_VG_00</option>
                                                    <option value="LGP_EHZ_VG_00">LGP_EHZ_VG_00</option>
                                                    <option value="LIA_EHZ_VG_00">LIA_EHZ_VG_00</option>
                                                    <option value="LIDI_EHZ_VG_00">LIDI_EHZ_VG_00</option>
                                                    <option value="LKA_EHZ_VG_00">LKA_EHZ_VG_00</option>
                                                    <option value="LMNG_EHZ_VG_00">LMNG_EHZ_VG_00</option>
                                                    <option value="LRNG_EHZ_VG_00">LRNG_EHZ_VG_00</option>
                                                    <option value="LWL_EHZ_VG_00">LWL_EHZ_VG_00</option>
                                                    <option value="MAH_EHZ_VG_00">MAH_EHZ_VG_00</option>
                                                    <option value="MDG_BHE_VG_00">MDG_BHE_VG_00</option>
                                                    <option value="MDG_BHN_VG_00">MDG_BHN_VG_00</option>
                                                    <option value="MDG_BHZ_VG_00">MDG_BHZ_VG_00</option>
                                                    <option value="MHW_EHZ_VG_00">MHW_EHZ_VG_00</option>
                                                    <option value="MIS_EHE_VG_00">MIS_EHE_VG_00</option>
                                                    <option value="MIS_EHZ_VG_00">MIS_EHZ_VG_00</option>
                                                    <option value="MKR_BHE_VG_00">MKR_BHE_VG_00</option>
                                                    <option value="MKR_BHN_VG_00">MKR_BHN_VG_00</option>
                                                    <option value="MKR_BHZ_VG_00">MKR_BHZ_VG_00</option>
                                                    <option value="MLGT_EHZ_VG_00">MLGT_EHZ_VG_00</option>
                                                    <option value="MLTN_EHZ_VG_00">MLTN_EHZ_VG_00</option>
                                                    <option value="MMD_SHE_VG_00">MMD_SHE_VG_00</option>
                                                    <option value="MMD_SHN_VG_00">MMD_SHN_VG_00</option>
                                                    <option value="MMD_SHZ_VG_00">MMD_SHZ_VG_00</option>
                                                    <option value="MWG_BHE_VG_00">MWG_BHE_VG_00</option>
                                                    <option value="MWG_BHN_VG_00">MWG_BHN_VG_00</option>
                                                    <option value="MWG_BHZ_VG_00">MWG_BHZ_VG_00</option>
                                                    <option value="ONA_EHZ_VG_00">ONA_EHZ_VG_00</option>
                                                    <option value="PACT_EHZ_VG_00">PACT_EHZ_VG_00</option>
                                                    <option value="PANG_EHZ_VG_00">PANG_EHZ_VG_00</option>
                                                    <option value="PASB_HHZ_VG_00">PASB_HHZ_VG_00</option>
                                                    <option value="PAUH_BDF_VG_00">PAUH_BDF_VG_00</option>
                                                    <option value="PAUH_BHE_VG_00">PAUH_BHE_VG_00</option>
                                                    <option value="PAUH_BHN_VG_00">PAUH_BHN_VG_00</option>
                                                    <option value="PAUH_BHZ_VG_00">PAUH_BHZ_VG_00</option>
                                                    <option value="PBK_SHZ_VG_00">PBK_SHZ_VG_00</option>
                                                    <option value="PBTG_EHZ_VG_00">PBTG_EHZ_VG_00</option>
                                                    <option value="PCAK_BDF_VG_00">PCAK_BDF_VG_00</option>
                                                    <option value="PCAK_BHE_VG_00">PCAK_BHE_VG_00</option>
                                                    <option value="PCAK_BHN_VG_00">PCAK_BHN_VG_00</option>
                                                    <option value="PCAK_BHZ_VG_00">PCAK_BHZ_VG_00</option>
                                                    <option value="PCSL_EHZ_VG_00">PCSL_EHZ_VG_00</option>
                                                    <option value="PIN_EHZ_VG_00">PIN_EHZ_VG_00</option>
                                                    <option value="PLAS_EHZ_VG_00">PLAS_EHZ_VG_00</option>
                                                    <option value="PLWG_EHZ_VG_00">PLWG_EHZ_VG_00</option>
                                                    <option value="PNCK_EHZ_VG_00">PNCK_EHZ_VG_00</option>
                                                    <option value="POSG_BLE_VG_00">POSG_BLE_VG_00</option>
                                                    <option value="POSG_BLN_VG_00">POSG_BLN_VG_00</option>
                                                    <option value="POSG_BLZ_VG_00">POSG_BLZ_VG_00</option>
                                                    <option value="POST_EHN_VG_00">POST_EHN_VG_00</option>
                                                    <option value="POST_EHZ_VG_00">POST_EHZ_VG_00</option>
                                                    <option value="PPD_EHZ_VG_00">PPD_EHZ_VG_00</option>
                                                    <option value="PRAH_EHZ_VG_00">PRAH_EHZ_VG_00</option>
                                                    <option value="PRTS_EHZ_VG_00">PRTS_EHZ_VG_00</option>
                                                    <option value="PSAG_EHZ_VG_00">PSAG_EHZ_VG_00</option>
                                                    <option value="PSML_EHZ_VG_00">PSML_EHZ_VG_00</option>
                                                    <option value="PST_SHE_VG_00">PST_SHE_VG_00</option>
                                                    <option value="PST_SHN_VG_00">PST_SHN_VG_00</option>
                                                    <option value="PST_SHZ_VG_00">PST_SHZ_VG_00</option>
                                                    <option value="PTM_EHZ_VG_00">PTM_EHZ_VG_00</option>
                                                    <option value="PTRZ_EHZ_VG_00">PTRZ_EHZ_VG_00</option>
                                                    <option value="PULO_EHZ_VG_00">PULO_EHZ_VG_00</option>
                                                    <option value="PUN_BHE_VG_00">PUN_BHE_VG_00</option>
                                                    <option value="PUN_BHN_VG_00">PUN_BHN_VG_00</option>
                                                    <option value="PUN_BHZ_VG_00">PUN_BHZ_VG_00</option>
                                                    <option value="PUSS_EHZ_VG_00">PUSS_EHZ_VG_00</option>
                                                    <option value="PUT_SHZ_VG_00">PUT_SHZ_VG_00</option>
                                                    <option value="REND_BHZ_VG_00">REND_BHZ_VG_00</option>
                                                    <option value="RIE_EHZ_VG_00">RIE_EHZ_VG_00</option>
                                                    <option value="RIN_EHE_VG_00">RIN_EHE_VG_00</option>
                                                    <option value="RIN_EHN_VG_00">RIN_EHN_VG_00</option>
                                                    <option value="RIN_EHZ_VG_00">RIN_EHZ_VG_00</option>
                                                    <option value="ROPA_EHZ_VG_00">ROPA_EHZ_VG_00</option>
                                                    <option value="ROTA_EHZ_VG_00">ROTA_EHZ_VG_00</option>
                                                    <option value="RTUX_EHE_VG_00">RTUX_EHE_VG_00</option>
                                                    <option value="RTUY_EHN_VG_00">RTUY_EHN_VG_00</option>
                                                    <option value="RTUZ_EHZ_VG_00">RTUZ_EHZ_VG_00</option>
                                                    <option value="RUA_EHZ_VG_00">RUA_EHZ_VG_00</option>
                                                    <option value="SABU_BHE_VG_00">SABU_BHE_VG_00</option>
                                                    <option value="SABU_BHN_VG_00">SABU_BHN_VG_00</option>
                                                    <option value="SABU_BHZ_VG_00">SABU_BHZ_VG_00</option>
                                                    <option value="SABU_EHE_VG_00">SABU_EHE_VG_00</option>
                                                    <option value="SABU_EHN_VG_00">SABU_EHN_VG_00</option>
                                                    <option value="SABU_EHZ_VG_00">SABU_EHZ_VG_00</option>
                                                    <option value="SBY_SHZ_VG_00">SBY_SHZ_VG_00</option>
                                                    <option value="SDG_EHZ_VG_00">SDG_EHZ_VG_00</option>
                                                    <option value="SEA_EHZ_VG_00">SEA_EHZ_VG_00</option>
                                                    <option value="SGG_SHZ_VG_00">SGG_SHZ_VG_00</option>
                                                    <option value="SGN_EHZ_VG_00">SGN_EHZ_VG_00</option>
                                                    <option value="SIR_EHZ_VG_00">SIR_EHZ_VG_00</option>
                                                    <option value="SKN_SHZ_VG_00">SKN_SHZ_VG_00</option>
                                                    <option value="SLAM_EHZ_VG_00">SLAM_EHZ_VG_00</option>
                                                    <option value="SLK_SHE_VG_00">SLK_SHE_VG_00</option>
                                                    <option value="SLK_SHN_VG_00">SLK_SHN_VG_00</option>
                                                    <option value="SLK_SHZ_VG_00">SLK_SHZ_VG_00</option>
                                                    <option value="SLN_EHZ_VG_00">SLN_EHZ_VG_00</option>
                                                    <option value="SLR1_EHZ_VG_00">SLR1_EHZ_VG_00</option>
                                                    <option value="SOLA_EHZ_VG_00">SOLA_EHZ_VG_00</option>
                                                    <option value="SOP_EHZ_VG_00">SOP_EHZ_VG_00</option>
                                                    <option value="SRTG_EHZ_VG_00">SRTG_EHZ_VG_00</option>
                                                    <option value="SUMB_EHZ_VG_00">SUMB_EHZ_VG_00</option>
                                                    <option value="SUND_EHZ_VG_00">SUND_EHZ_VG_00</option>
                                                    <option value="SURI_EHZ_VG_00">SURI_EHZ_VG_00</option>
                                                    <option value="TBG2_EHZ_VG_00">TBG2_EHZ_VG_00</option>
                                                    <option value="TDKT_EHZ_VG_00">TDKT_EHZ_VG_00</option>
                                                    <option value="TEKO_EHZ_VG_00">TEKO_EHZ_VG_00</option>
                                                    <option value="TENG_EHZ_VG_00">TENG_EHZ_VG_00</option>
                                                    <option value="TES_EHZ_VG_00">TES_EHZ_VG_00</option>
                                                    <option value="TLR_EHZ_VG_00">TLR_EHZ_VG_00</option>
                                                    <option value="TMKS_EHZ_VG_00">TMKS_EHZ_VG_00</option>
                                                    <option value="TNGK_BDF_VG_00">TNGK_BDF_VG_00</option>
                                                    <option value="TNGK_BHE_VG_00">TNGK_BHE_VG_00</option>
                                                    <option value="TNGK_BHN_VG_00">TNGK_BHN_VG_00</option>
                                                    <option value="TNGK_BHZ_VG_00">TNGK_BHZ_VG_00</option>
                                                    <option value="TNJG_EHZ_VG_00">TNJG_EHZ_VG_00</option>
                                                    <option value="TOBI_EHN_VG_00">TOBI_EHN_VG_00</option>
                                                    <option value="TOBI_EHZ_VG_00">TOBI_EHZ_VG_00</option>
                                                    <option value="TOGK_EHZ_VG_00">TOGK_EHZ_VG_00</option>
                                                    <option value="TOWZ_EHZ_VG_00">TOWZ_EHZ_VG_00</option>
                                                    <option value="TRTS_EHZ_VG_00">TRTS_EHZ_VG_00</option>
                                                    <option value="UMBK_EHZ_VG_00">UMBK_EHZ_VG_00</option>
                                                    <option value="WER_EHZ_VG_00">WER_EHZ_VG_00</option>
                                                    <option value="WLN_EHE_VG_00">WLN_EHE_VG_00</option>
                                                    <option value="WLN_EHN_VG_00">WLN_EHN_VG_00</option>
                                                    <option value="WLN_EHZ_VG_00">WLN_EHZ_VG_00</option>
                                                    <option value="WNR_EHZ_VG_00">WNR_EHZ_VG_00</option>
                                                    <option value="WWR_EHZ_VG_00">WWR_EHZ_VG_00</option>
                                                    <option value="YHKR_BHZ_VG_00">YHKR_BHZ_VG_00</option>
                                                    <option value="YMP_EHZ_VG_00">YMP_EHZ_VG_00</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Range Tanggal</label>
                                                <div class="input-group input-daterange">
                                                    <input id="start" type="text" class="form-control" value="{{ empty(old('start')) ? now()->subDays(30)->format('Y-m-d') : old('start')}}" name="start">
                                                    <div class="input-group-addon"> - </div>
                                                    <input id="end" type="text" class="form-control" value="{{ empty(old('end')) ? now()->format('Y-m-d') : old('end')}}" name="end">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Periode RSAM</label>
                                                <select id="periode" class="form-control m-b" name="periode">
                                                    <option value="60">1 Menit</option>
                                                    <option value="600">10 Menit</option>
                                                    <option value="3600">1 Jam</option>
                                                    <option value="21600">6 Jam</option>
                                                </select>
                                            </div>

                                            <hr>
                                            <button id="submit" class="btn btn-magma" type="submit">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12 rsam" style="display: none;">
            <div class="hpanel">
                <div class="panel-heading rsam-heading">
                    Grafik RSAM
                </div>
                <div class="panel-body">
                    <div class="row p-md">
                        <div class="progress m-t-xs full progress-striped active">
                            <div style="width: 90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class=" progress-bar progress-bar-success">Loading....
                            </div>
                        </div>
                        <div id="rsam" style="min-width: 310px; min-height: 680px; margin: 0 auto; display: none;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('add-vendor-script')
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<script src="{{ asset('vendor/moment/moment.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function () {

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

    $('.input-daterange').datepicker({
        startDate: '2015-05-01',
        endDate: '{{ now()->format('Y-m-d') }}',
        language: 'id',
        todayHighlight: true,
        todayBtn: 'linked',
        enableOnReadonly: false
    });

    $('#form').submit(function(e) {
        e.preventDefault();

        $('.rsam').show();
        $('#rsam').hide();
        $('.progress').show();

        var channel = $('#channel').val();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{ URL::signedRoute('chambers.json.rsam') }}',
            data: $(this).serialize(),
            type: 'POST',
            success: function(data) {
                console.log(data);
                plotRSAM(data,channel);
            },
            complete: function() {
                $('.progress').hide();
                $('#rsam').show();
            },
        });
    });

    function plotRSAM(data,channel)
    {
        Highcharts.stockChart('rsam', {
            rangeSelector: {
                selected: 4,
            },
            chart: {
                zoomType: 'x',
            },
            title: {
                text: 'Grafik RSAM - '+channel,
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'RSAM Count',
                }
            },
            legend: {
                enabled: false,
            },
            plotOptions: {
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
            },
            scrollbar: {
                enabled: false,
            },
            rangeSelector: {
                buttons: [{
                    type: 'hour',
                    count: 1,
                    text: '1H'
                }, {
                    type: 'hour',
                    count: 12,
                    text: '12H'
                }, {
                    type: 'day',
                    count: 1,
                    text: '1D'
                }, {
                    type: 'all',
                    count: 1,
                    text: 'All'
                }],
                selected: 3,
                inputEnabled: false
            },
            series: [{
                marker: {
                    enabled: true,
                    radius: 3
                },
                type: 'area',
                name: 'RSAM',
                data: data,
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, '#1E88E5'],
                        [1, '#ffffff']
                    ]
                },
            }],
            exporting: {
                enabled: true,
                scale: 1,
                sourceHeight: 800,
                sourceWidth: 1200,
            }
        });
    }

});
</script>
@endsection
