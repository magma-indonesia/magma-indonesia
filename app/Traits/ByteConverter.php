<?php

namespace App\Traits;

trait ByteConverter
{
    public function getKiloByte($bytes)
    {
        return number_format($bytes / 1024, 2, '.','') . ' KB';
    }

    public function getMegaByte($bytes)
    {
        return number_format($bytes / 1048576, 2, '.','') . ' MB';
    }

    public function getGigaByte($bytes)
    {
        return number_format($bytes / 1073741824, 2, '.','') . ' GB';
    }
}