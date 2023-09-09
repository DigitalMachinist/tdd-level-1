<?php

namespace App\Services\Fraction;

use App\Services\Fraction\Actions\AddFractions;
use App\Services\Fraction\Actions\CloneFraction;
use App\Services\Fraction\Actions\CreateFractionFromImproperFractionString;
use App\Services\Fraction\Actions\FormatFractionAsImproperFractionString;
use App\Services\Fraction\Actions\ReduceFraction;
use Exception;
use Illuminate\Support\Collection;

/**
 * This is an attempt at handling Fractions in a action-based way, bundling actions using a service as a facade.
 * This is often a good approach for dealing with a model that has a lot of business logic and clearly defined states to transitions between.
 * It's still probably not a great approach for this particular problem, but it's a good example of how to use actions and services together.
 */
class FractionService implements FractionServiceContract
{
    private readonly AddFractions $addFractions;
    private readonly CloneFraction $cloneFraction;
    private readonly CreateFractionFromImproperFractionString $createFractionFromImproperFractionString;
    private readonly FormatFractionAsImproperFractionString $formatFractionAsImproperFractionString;
    private readonly ReduceFraction $reduceFraction;

    public function __construct(
        CloneFraction $cloneFraction,
        CreateFractionFromImproperFractionString $createFractionFromImproperFractionString,
        AddFractions $addFractions,
        ReduceFraction $reduceFraction,
        FormatFractionAsImproperFractionString $formatFractionAsImproperFractionString,
    ) {
        $this->cloneFraction = $cloneFraction;
        $this->createFractionFromImproperFractionString = $createFractionFromImproperFractionString;
        $this->addFractions = $addFractions;
        $this->reduceFraction = $reduceFraction;
        $this->formatFractionAsImproperFractionString = $formatFractionAsImproperFractionString;
    }

    /**
     * @param Fraction $fraction
     * @return Fraction
     */
    public function fromFraction(Fraction $fraction): Fraction
    {
        return $this->cloneFraction->execute($fraction);
    }

    /**
     * @param string $fraction
     * @return Fraction
     * @throws Exception
     */
    public function fromString(string $fraction): Fraction
    {
        return $this->createFractionFromImproperFractionString->execute($fraction);
    }

    /**
     * @param array<int, Fraction> $fractions
     * @return Fraction
     */
    public function add(array $fractions): Fraction
    {
        return $this->addFractions->execute($fractions);
    }

    /**
     * @param Fraction $fraction
     * @return Fraction
     */
    public function reduce(Fraction $fraction): Fraction
    {
        return $this->reduceFraction->execute($fraction);
    }

    /**
     * @param Fraction $fraction
     * @param bool $reduce
     * @return string
     */
    public function toImproperFractionString(Fraction $fraction, bool $reduce = true): string
    {
        return $this->formatFractionAsImproperFractionString->execute($fraction, $reduce);
    }
}