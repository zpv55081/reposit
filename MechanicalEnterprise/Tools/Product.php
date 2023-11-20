<?php

namespace App\Zpv55081\MechanicalEnterprise\Tools;

interface Product
{
    public function setCost(\App\Zpv55081\MechanicalEnterprise\Tools\Price $price);

    public function getCost() :?float;

    public function getProductionDate() :string;
}