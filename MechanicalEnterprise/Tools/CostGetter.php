<?php

namespace App\Zpv55081\MechanicalEnterprise\Tools;

interface CostGetter
{
    public function getCost() :?float;

    public function getForeignCurrencyCost() :?float;
}