<?php

namespace App\Zpv55081\MechanicalEnterprise\Tools;

interface CostCorrector extends CostGetter
{
    public function setCost(Price $price);
}