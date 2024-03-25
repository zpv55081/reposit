<?php

namespace App\Zpv55081\MechanicalEnterprise;

abstract class Machine
{
    /**
     * Собственный вес
     * 
     * @var float
     */
    protected float $weight;

    protected function getWeight() :float
    {
        return $this->weight;
    }

    /**
     * Задать собственный вес
     */
    abstract protected function setWeight(float $weightsValue) :bool;    
}