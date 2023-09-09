<?php

namespace Tests\Feature;

use App\Services\Fraction\Fraction;
use App\Services\Fraction\FractionServiceContract;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FractionServiceTest extends TestCase
{
    use RefreshDatabase;

    private FractionServiceContract $fractionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fractionService = app(FractionServiceContract::class);
    }

    public function testFractionServiceFromFractionClonesExistingFractionAsNewFraction(): void
    {
        $fraction1 = new Fraction(1, 2);
        $fraction2 = $this->fractionService->fromFraction($fraction1);

        $this->assertNotSame($fraction1, $fraction2);
        $this->assertEquals($fraction1->numerator, $fraction2->numerator);
        $this->assertEquals($fraction1->denominator, $fraction2->denominator);
    }

    public function testFractionServiceFromStringCreatesNewFraction(): void
    {
        $result = $this->fractionService->fromString('1/2');

        $this->assertEquals(1, $result->numerator);
        $this->assertEquals(2, $result->denominator);
    }

    public function testFractionServiceFromStringThrowsExceptionForInvalidInputs(): void
    {
        $this->expectException(Exception::class);
        $this->fractionService->fromString('1/2/3');

        $this->expectException(Exception::class);
        $this->fractionService->fromString('3 1/3');

        $this->expectException(Exception::class);
        $this->fractionService->fromString('0.25');
    }

    public function testFractionServiceAddHandlesSimpleFractions(): void
    {
        $result = $this->fractionService->add([
            new Fraction(1, 2),
            new Fraction(1, 3),
        ]);

        $this->assertEquals(5, $result->numerator);
        $this->assertEquals(6, $result->denominator);
    }

    public function testFractionServiceAddHandlesImproperFractions(): void
    {
        $result = $this->fractionService->add([
            new Fraction(1, 2),
            new Fraction(4, 3),
        ]);

        $this->assertEquals(11, $result->numerator);
        $this->assertEquals(6, $result->denominator);
    }

    public function testFractionServiceAddHandlesNegativeFractions(): void
    {
        $result = $this->fractionService->add([
            new Fraction(-1, 2),
            new Fraction(4, -3),
        ]);

        $this->assertEquals(11, $result->numerator);
        $this->assertEquals(-6, $result->denominator);
    }

    public function testFractionServiceAddReducesResults(): void
    {
        $result = $this->fractionService->add([
            new Fraction(10, 4),
            new Fraction(8, 4),
        ]);

        $this->assertEquals(9, $result->numerator);
        $this->assertEquals(2, $result->denominator);
    }

    public function testFractionServiceToImproperFractionStringReducesResultsToWholeNumbers(): void
    {
        $result = $this->fractionService->toImproperFractionString(new Fraction(16, 4));

        $this->assertEquals('4', $result);
    }

    public function testFractionServiceToImproperFractionStringReducesNegativeResultsToWholeNumbers(): void
    {
        $result = $this->fractionService->toImproperFractionString(new Fraction(-16, 4));

        $this->assertEquals('-4', $result);
    }

    public function testFractionServiceToImproperFractionStringMovesNegativeOnDenominatorToNumerator(): void
    {
        $result = $this->fractionService->toImproperFractionString(new Fraction(3, -4));

        $this->assertEquals('-3/4', $result);
    }

    public function testFractionServiceToImproperFractionStringReducesDoubleNegativeResultsToPositiveResults(): void
    {
        $result = $this->fractionService->toImproperFractionString(new Fraction(-3, -4));

        $this->assertEquals('3/4', $result);
    }

    public function testFractionServiceToImproperFractionStringDoesNotReduceResultsWhenReduceFlagIsFalse(): void
    {
        $result = $this->fractionService->toImproperFractionString(new Fraction(10, 4), false);

        $this->assertEquals('10/4', $result);
    }
}
