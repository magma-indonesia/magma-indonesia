<?php

namespace App\Traits;

trait IgnoreMutators
{
    public $preventAttrSet = false;

    public function ignoreMutators(bool $ignore = true): void
    {
        $this->preventAtrrSet = $ignore;
    }
}
