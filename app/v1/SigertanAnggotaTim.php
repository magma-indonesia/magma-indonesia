<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class SigertanAnggotaTim extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'atm_idx';

    protected $table = 'magma_qls_atm';

    public function getQlsAtmAttribute($value)
    {
        $nip = $value == '196308231993031001' ? '196308231993061001' : $value;
        $nip = $nip == '197307232006041002' ? '197307232006041001' : $nip;

        return $nip;
    }
}
