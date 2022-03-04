<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaModel extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'magma_v1';
}
