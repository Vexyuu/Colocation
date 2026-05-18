<?php

namespace App\Tests\Service;

use App\Service\TantiemeCalculator;
use PHPUnit\Framework\TestCase;

class TantiemeCalculatorTest extends TestCase
{
    public function testCalculateTantiemeAndInvoiceShare(): void
    {
        $calculator = new TantiemeCalculator();
        $totalSurface = 100.0;
        $invoiceAmount = 200.0;

        $scenarios = [
            'Locataire A' => ['surface' => 15.0, 'expectedTantieme' => 15.0, 'expectedShare' => 30.0],
            'Locataire B' => ['surface' => 12.0, 'expectedTantieme' => 12.0, 'expectedShare' => 24.0],
            'Locataire C' => ['surface' => 18.0, 'expectedTantieme' => 18.0, 'expectedShare' => 36.0],
        ];

        foreach ($scenarios as $locataire => $data) {
            $tantieme = $calculator->calculateTantieme($data['surface'], $totalSurface);
            $this->assertEquals($data['expectedTantieme'], $tantieme);

            $share = $calculator->calculateShare($invoiceAmount, $tantieme);
            $this->assertEquals($data['expectedShare'], $share);
        }
    }
}