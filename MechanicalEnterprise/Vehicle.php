<?php

namespace App\Zpv55081\MechanicalEnterprise;

use App\Zpv55081\MechanicalEnterprise\Models\VehicleCategory;

/**
 * Класс транспортное средство
 * 
 * (описывает транспортное средство)
 */
class Vehicle extends Machine
{
    /**
     * Категория ТС
     */
    private ?VehicleCategory $сategory;

    /**
     * Грузоподъемность (в тоннах)
     */
    private float $loadCapacity;

    /**
     * Средняя скорость (км/ч), 
     */
    private int $averagesSpeed;

    /**
     * Уровень топлива
     */
    private $fuelLevel;

    public function __construct(VehicleCategory $vehicleCategory = null)
    {
        $this->сategory = $vehicleCategory;
    }
    
    protected function setWeight(float $weightsValue) :bool 
    {
        $this->weight = $weightsValue - $this->fuelLevel->getWeight;
        return true;
    }

    public function getCategory() :?VehicleCategory
    {
        return $this->сategory;
    }

    protected function getLoadCapacity() :float|null
    {
        return $this->loadCapacity;
    }   
}