<?php

namespace App\Services\Fraction\Actions;

use App\Services\Fraction\Fraction;

class ReduceFraction
{
    private readonly CloneFraction $cloneFraction;

    public function __construct(CloneFraction $cloneFraction)
    {
        $this->cloneFraction = $cloneFraction;
    }

    /**
     * @param Fraction $fraction
     * @return Fraction
     */
    public function execute(Fraction $fraction): Fraction
    {
        // Reduce the fraction to its lowest terms.
        // TODO: Could be improved by separating out the algo for obtaining prime factors.
        $i = 2;
        $result = $this->cloneFraction->execute($fraction);
        while ($i <= abs(min($result->numerator, $result->denominator))) {
            // If the numerator or denominator is not divisible by the current value of $i, then it is not a common factor. Keep counting.
            if ($result->numerator % $i !== 0 || $result->denominator % $i !== 0) {
                $i++;
                continue;
            }

            // If this is a common factor, divide both the numerator and denominator by it and start over.
            $result->numerator /= $i;
            $result->denominator /= $i;
            $i = 2;
        }

        return $result;
    }
}