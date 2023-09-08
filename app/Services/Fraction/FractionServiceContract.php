<?php

namespace App\Services\Fraction;

use ArrayAccess;
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
     * @return string
     */
    public function toImproperFractionString(Fraction $fraction): string;
}