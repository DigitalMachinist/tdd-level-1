<?php

namespace App\Services\Fraction;

use Exception;

interface FractionServiceContract
{
    /**
     * @param Fraction $fraction
     * @return Fraction
     */
    public function fromFraction(Fraction $fraction): Fraction;

    /**
     * @param string $fraction
     * @return Fraction
     * @throws Exception
     */
    public function fromString(string $fraction): Fraction;

    /**
     * @param array<int, Fraction> $fractions
     * @return Fraction
     */
    public function add(array $fractions): Fraction;

    /**
     * @param Fraction $fraction
     * @return Fraction
     */
    public function reduce(Fraction $fraction): Fraction;

    /**
     * @param Fraction $fraction
     * @param bool $reduce
     * @return string
     */
    public function toImproperFractionString(Fraction $fraction, bool $reduce = true): string;
}