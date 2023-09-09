<?php

namespace App\Services\Fraction\Actions;

use App\Services\Fraction\Fraction;

class FormatFractionAsImproperFractionString
{
    private readonly ReduceFraction $reduceFraction;

    public function __construct(ReduceFraction $reduceFraction)
    {
        $this->reduceFraction = $reduceFraction;
    }

    /**
     * @param Fraction $fraction
     * @param bool $reduce
     * @return string
     */
    public function execute(Fraction $fraction, bool $reduce = true): string
    {
        if ($reduce) {
            // Reduce the fraction to its lowest terms.
            $fraction = $this->reduceFraction->execute($fraction);
        }

        $numerator = $fraction->numerator;
        $denominator = $fraction->denominator;

        // If the denominator is negative, move the negative sign to the numerator.
        // This also cancels out double-negatives in a single positive integer.
        if ($denominator < 0) {
            $numerator *= -1;
            $denominator *= -1;
        }

        return $denominator != 1
            ? "{$numerator}/{$denominator}"
            : (string) $numerator;
    }
}