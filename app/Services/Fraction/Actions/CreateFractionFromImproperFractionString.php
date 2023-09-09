<?php

namespace App\Services\Fraction\Actions;

use App\Services\Fraction\Fraction;
use Exception;

class CreateFractionFromImproperFractionString
{
    /**
     * @param string $fraction
     * @return Fraction
     */
    public function execute(string $fraction): Fraction
    {
        if (!preg_match('/^-?\d+\/-?\d+$/', $fraction)) {
            throw new Exception('Invalid fraction: ' . $fraction);
        }

        return new Fraction(...explode('/', $fraction));
    }
}