<?php

namespace App\v1;

use App\v1\OldModelVar;

class MagmaVar extends OldModelVar
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    protected $fillable = [
        'var_image',
        'var_image_create',
        'var_issued',
        'ga_code',
        'var_noticenumber',
        'ga_nama_gapi',
        'ga_id_smithsonian',
        'cu_status',
        'pre_status',
        'var_source',
        'volcano_location',
        'area',
        'summit_elevation',
        'var_perwkt',
        'periode',
        'var_visibility',
        'var_cuaca',
        'var_curah_hujan',
        'var_suhumin',
        'var_suhumax',
        'var_kelembabanmin',
        'var_kelembabanmax',
        'var_tekananmin',
        'var_tekananmax',
        'var_kecangin',
        'var_arangin',
        'var_asap',
        'var_tasap_min',
        'var_tasap',
        'var_wasap',
        'var_intasap',
        'var_tekasap',
        'var_viskawah',
        'var_tej',
        'var_tej_amin',
        'var_tej_amax',
        'var_tej_spmin',
        'var_tej_spmax',
        'var_tej_dmin',
        'var_tej_dmax',
        'var_tel',
        'var_tel_amin',
        'var_tel_amax',
        'var_tel_spmin',
        'var_tel_spmax',
        'var_tel_dmin',
        'var_tel_dmax',
        'var_vlp',
        'var_vlp_amin',
        'var_vlp_amax',
        'var_vlp_dmin',
        'var_vlp_dmax',
        'var_dpt',
        'var_dpt_amin',
        'var_dpt_amax',
        'var_dpt_dmin',
        'var_dpt_dmax',
        'var_dev',
        'var_dev_amin',
        'var_dev_amax',
        'var_dev_spmin',
        'var_dev_spmax',
        'var_dev_dmin',
        'var_dev_dmax',
        'var_vta',
        'var_vta_amin',
        'var_vta_amax',
        'var_vta_spmin',
        'var_vta_spmax',
        'var_vta_dmin',
        'var_vta_dmax',
        'var_vtb',
        'var_vtb_amin',
        'var_vtb_amax',
        'var_vtb_dmin',
        'var_vtb_dmax',
        'var_hyb' ,
        'var_hyb_amin',
        'var_hyb_amax',
        'var_hyb_spmin',
        'var_hyb_spmax',
        'var_hyb_dmin',
        'var_hyb_dmax',
        'var_lof',
        'var_lof_amin',
        'var_lof_amax',
        'var_lof_dmin',
        'var_lof_dmax',
        'var_tor',
        'var_tor_amin',
        'var_tor_amax',
        'var_tor_dmin',
        'var_tor_dmax',
        'var_hrm',
        'var_hrm_amin',
        'var_hrm_amax',
        'var_hrm_dmin',
        'var_hrm_dmax',
        'var_tre',
        'var_tre_amin',
        'var_tre_amax',
        'var_tre_dmin',
        'var_tre_dmax',
        'var_mtr',
        'var_mtr_amin',
        'var_mtr_amax',
        'var_mtr_adom',
        'var_hbs',
        'var_hbs_amin',
        'var_hbs_amax',
        'var_hbs_dmin',
        'var_hbs_dmax',
        'var_gug',
        'var_gug_amin',
        'var_gug_amax',
        'var_gug_dmin',
        'var_gug_dmax',
        'var_gug_rmin',
        'var_gug_rmax',
        'var_gug_alun',
        'var_apg',
        'var_apg_amin',
        'var_apg_amax',
        'var_apg_dmin',
        'var_apg_dmax',
        'var_apg_rmin',
        'var_apg_rmax',
        'var_apg_alun',
        'var_apl',
        'var_apl_amin',
        'var_apl_amax',
        'var_apl_dmin',
        'var_apl_dmax',
        'var_apl_rmin',
        'var_apl_rmax',
        'var_apl_alun',
        'var_lts',
        'var_lts_amin',
        'var_lts_amax',
        'var_lts_dmin',
        'var_lts_dmax',
        'var_lts_tmin',
        'var_lts_tmax',
        'var_lts_wasap',
        'var_gtb',
        'var_gtb_amin',
        'var_gtb_amax',
        'var_gtb_dmin',
        'var_gtb_dmax',
        'var_trs',
        'var_trs_amin',
        'var_trs_amax',
        'var_trs_spmin',
        'var_trs_spmax',
        'var_trs_dmin',
        'var_trs_dmax',
        'var_trs_skalamin',
        'var_trs_skalamax',
        'var_ketlain',
        'var_rekom',
        'var_nip_pelapor',
        'var_nama_pelapor',
        'var_data_date',
        'var_nip_pemeriksa_pj',
        'var_nama_pemeriksa_pj',
        'var_nip_pemeriksa',
        'var_nama_pemeriksa'
    ];

    protected $guard = ['id'];

    protected $appends = [
        'data_date',
    ];

    protected $casts = [
        'var_issued' => 'datetime:Y-m-d H:i:s',
        'var_log' => 'datetime:Y-m-d H:i:s',
        'var_data_date' => 'date:Y-m-d'
    ];

    public function getVarKetlainAttribute($value)
    {
        return (empty($value) || strlen($value) < 7) ? null : $value;
    }

    public function getDataDateAttribute($value)
    {
        return $this->attributes['var_data_date'];
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }

    public function compile()
    {
        return $this->hasOne('App\v1\CompileVar','ga_code','ga_code');
    }
    
    // protected $dates = ['var_data_date'];
}
