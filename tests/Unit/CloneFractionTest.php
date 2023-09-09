<?php

namespace Tests\Unit\Actions;

use App\Services\Fraction\Actions\CloneFraction;
use App\Services\Fraction\Fraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CloneFractionTest extends TestCase
{
    use RefreshDatabase;

    private readonly CloneFraction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(CloneFraction::class);
    }

    public function testCloneFractionExecutesSuccessfully(): void
    {
        $fraction1 = new Fraction(1, 2);
        $fraction2 = $this->action->execute($fraction1);

        $this->assertNotSame($fraction1, $fraction2);
        $this->assertEquals($fraction1->numerator, $fraction2->numerator);
        $this->assertEquals($fraction1->denominator, $fraction2->denominator);
    }
}
