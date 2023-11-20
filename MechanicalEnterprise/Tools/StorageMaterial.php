<?php

namespace App\Zpv55081\MechanicalEnterprise\Tools;

/**
 * Складской материал
 */
interface StorageMaterial
{
    public function getWeight() :?float;
}