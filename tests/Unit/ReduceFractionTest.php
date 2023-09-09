<?php

namespace Tests\Unit\Actions;

use App\Services\Fraction\Actions\ReduceFraction;
use App\Services\Fraction\Fraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReduceFractionTest extends TestCase
{
    use RefreshDatabase;

    private readonly ReduceFraction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(ReduceFraction::class);
    }

    public function testReduceFractionExecutesSuccessfully(): void
    {
        $result = $this->action->execute(new Fraction(10, 4));

        $this->assertEquals(5, $result->numerator);
        $this->assertEquals(2, $result->denominator);
    }
}
