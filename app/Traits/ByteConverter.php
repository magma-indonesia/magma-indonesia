<?php

namespace App\Traits;

trait ByteConverter
{
    public function kiloByte(int $bytes): string
    {
        return number_format($bytes / 1024, 2, '.', '') . ' KB';
    }

    public function megaByte(int $bytes): string
    {
        return number_format($bytes / 1048576, 2, '.', '') . ' MB';
    }

    public function gigaByte(int $bytes): string
    {
        return number_format($bytes / 1073741824, 2, '.', '') . ' GB';
    }
}
