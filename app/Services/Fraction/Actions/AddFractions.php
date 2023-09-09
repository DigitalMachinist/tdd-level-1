<?php

namespace App\Services\Fraction\Actions;

use App\Services\Fraction\Fraction;
use Illuminate\Support\Collection;

class AddFractions
{
    private readonly ReduceFraction $reduceFraction;

    public function __construct(ReduceFraction $reduceFraction)
    {
        $this->reduceFraction = $reduceFraction;
    }

    /**
     * @param array<int, Fraction> $fractions
     * @return Fraction
     */
    public function execute(array $fractions): Fraction
    {
        $fractions = new Collection($fractions);

        // Find a common demoninator by multiplying all denominators together.
        // TODO: This could be improved by finding the least common multiple via prime factorization.
        $denominator = $fractions->reduce(fn (int $carry, Fraction $fraction) => $carry * $fraction->denominator, 1);

        // Scale all of the input fractions to be expressed using the common denominator.
        $scaledFractions = $fractions->map(fn (Fraction $fraction) => new Fraction(
            $fraction->numerator * ($denominator / $fraction->denominator),
            $denominator,
        ));

        // Add the numerators together to get the numerator scaled to the common denominator and make a fraction from it.
        $numerator = $scaledFractions->reduce(fn (int $carry, Fraction $fraction) => $carry + $fraction->numerator, 0);
        $result = new Fraction($numerator, $denominator);

        // Reduce the fraction to its lowest terms.
        $result = $this->reduceFraction->execute($result);

        return $result;
    }
}