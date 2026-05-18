<?php

namespace App\Service;

class TantiemeCalculator
{
    public function calculateTantieme(float $roomSurface, float $totalSurface): float
    {
        if ($totalSurface <= 0) {
            throw new \InvalidArgumentException("La superficie totale doit être supérieure à 0.");
        }
        return ($roomSurface / $totalSurface) * 100;
    }

    public function calculateShare(float $invoiceAmount, float $tantieme): float
    {
        return $invoiceAmount * ($tantieme / 100);
    }
}