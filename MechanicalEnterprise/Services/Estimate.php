<?php

namespace App\Zpv55081\MechanicalEnterprise\Services;

/**
 * Интерфейс Смета
 */
interface Estimate
{
    /**
     * Вычислить
     */
    public function evaluate(): array;
}