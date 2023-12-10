<?php

namespace App\Service;

class CreditProgram
{
    public function evaluate(
        ?float $vehiclePrice,
        ?float $initialPayment,
        ?float $monthlyPaymentBefore,
        ?int $creditTerm,
    ) {
        if (
            $initialPayment > 200000
            &&
            $monthlyPaymentBefore <= 10000
            &&
            $creditTerm < 60
        ) {
            $minimal_rate = 12.3;
            $fee = 9800;
        } else {
            $minimal_rate = 15;
            $fee = 9800;
        }

        $debt = $vehiclePrice - $initialPayment;

        $amount = $debt + $debt*$minimal_rate/100*$creditTerm/12;

        return [
            'amount' => $amount,
            'minimal_rate' => $minimal_rate,
            'fee' => $fee,
        ];
    }
}
