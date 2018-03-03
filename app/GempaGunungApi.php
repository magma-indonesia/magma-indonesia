<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GempaGunungApi extends Model
{

    protected function jumlah()
    {
        $sum = 0;
        $sum += \App\EqApg::sum('jumlah');
        $sum += \App\EqApl::sum('jumlah');
        $sum += \App\EqDev::sum('jumlah');
        $sum += \App\EqDpt::sum('jumlah');
        $sum += \App\EqGtb::sum('jumlah');
        $sum += \App\EqGug::sum('jumlah');
        $sum += \App\EqHbs::sum('jumlah');
        $sum += \App\EqHrm::sum('jumlah');
        $sum += \App\EqHyb::sum('jumlah');
        $sum += \App\EqLof::sum('jumlah');
        $sum += \App\EqLts::sum('jumlah');
        $sum += \App\EqMtr::sum('jumlah');
        $sum += \App\EqTej::sum('jumlah');
        $sum += \App\EqTel::sum('jumlah');
        $sum += \App\EqTor::sum('jumlah');
        $sum += \App\EqTre::sum('jumlah');
        $sum += \App\EqTrs::sum('jumlah');
        $sum += \App\EqVlp::sum('jumlah');
        $sum += \App\EqVta::sum('jumlah');
        $sum += \App\EqVtb::sum('jumlah');
        
        return $sum;
    }

    public function JumlahGempaGunungApi()
    {
        return static::jumlah();
    }
}
