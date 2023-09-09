<?php

namespace Tests\Unit\Actions;

use App\Services\Fraction\Actions\AddFractions;
use App\Services\Fraction\Fraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddFractionsTest extends TestCase
{
    use RefreshDatabase;

    private readonly AddFractions $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(AddFractions::class);
    }

    public function testAddFractionsHandlesSimpleFractions(): void
    {
        $result = $this->action->execute([
            new Fraction(1, 2),
            new Fraction(1, 3),
        ]);

        $this->assertEquals(5, $result->numerator);
        $this->assertEquals(6, $result->denominator);
    }

    public function testAddFractionsHandlesImproperFractions(): void
    {
        $result = $this->action->execute([
            new Fraction(1, 2),
            new Fraction(4, 3),
        ]);

        $this->assertEquals(11, $result->numerator);
        $this->assertEquals(6, $result->denominator);
    }

    public function testAddFractionsHandlesNegativeFractions(): void
    {
        $result = $this->action->execute([
            new Fraction(-1, 2),
            new Fraction(4, -3),
        ]);

        $this->assertEquals(11, $result->numerator);
        $this->assertEquals(-6, $result->denominator);
    }

    public function testAddFractionsReducesResults(): void
    {
        $result = $this->action->execute([
            new Fraction(10, 4),
            new Fraction(8, 4),
        ]);

        $this->assertEquals(9, $result->numerator);
        $this->assertEquals(2, $result->denominator);
    }
}
