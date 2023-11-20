<?php

namespace App\Zpv55081\MechanicalEnterprise;

use App\Zpv55081\MechanicalEnterprise\Tools\Cargo;
use App\Zpv55081\MechanicalEnterprise\Tools\CostGetter;
use App\Zpv55081\MechanicalEnterprise\Tools\StorageMaterial;

/**
 * Запчасть
 */
class Part implements
    Cargo,
    StorageMaterial,
    CostGetter
{
    use Tools\Cost;

    /**
     * Масса запчасти
     */
    private float $weight;

    /**
     * Артикул
     */
    private string $vendorCode;

    /**
     * Стоимость
     */
    private $cost;

    protected function getVendorCode(): string
    {
        return $this->vendorCode;
    }

    protected function setVendorCode($vendorCode): ?bool
    {
        if ($this->vendorCode = $vendorCode) {
            return true;
        };
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }
}
