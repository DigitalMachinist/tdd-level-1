<?php

namespace App\Services\Fraction;

use Exception;
use Illuminate\Support\Collection;

/**
 * This is an attempt at handling Fractions in a services-based way. Not sure I like the result as compared to an object-oriented approach, 
 * but it has interesting implications for being able to swap out implementation of the service for situations where you want to use different
 * algorithmic approaches. I can't imagine why you'd do that with Fractions, but it's an interesting idea.
 */
class FractionService implements FractionServiceContract
{
    public function fromFraction(Fraction $fraction): Fraction
    {
        return new Fraction($fraction->numerator, $fraction->denominator);
    }

    public function fromString(string $fraction): Fraction
    {
        if (!preg_match('/^-?\d+\/-?\d+$/', $fraction)) {
            throw new Exception('Invalid fraction: ' . $fraction);
        }

        return new Fraction(...explode('/', $fraction));
    }

    /**
     * @param array<int, Fraction> $fractions
     * @return Fraction
     */
    public function add(array $fractions): Fraction
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
        $unscaledResult = new Fraction($numerator, $denominator);

        // Reduce the fraction to its lowest terms.
        // TODO: Could be improved by separating out the algo for obtaining prime factors.
        $i = 2;
        $result = $unscaledResult;
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

    public function toImproperFractionString(Fraction $fraction): string
    {
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