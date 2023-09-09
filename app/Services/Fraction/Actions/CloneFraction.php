<?php

namespace App\Services\Fraction\Actions;

use App\Services\Fraction\Fraction;

class CloneFraction
{
    /**
     * @param Fraction $fraction
     * @return Fraction
     */
    public function execute(Fraction $fraction): Fraction
    {
        return new Fraction($fraction->numerator, $fraction->denominator);
    }
}