<?php

namespace App\Enums;

enum VolcanoLevel: int
{
    case Normal = 1;
    case Waspada = 2;
    case Siaga = 3;
    case Awas = 4;
}
