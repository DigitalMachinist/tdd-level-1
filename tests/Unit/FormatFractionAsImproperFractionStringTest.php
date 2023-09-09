<?php

namespace Tests\Unit\Actions;

use App\Services\Fraction\Actions\FormatFractionAsImproperFractionString;
use App\Services\Fraction\Fraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormatFractionAsImproperFractionStringTest extends TestCase
{
    use RefreshDatabase;

    private readonly FormatFractionAsImproperFractionString $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(FormatFractionAsImproperFractionString::class);
    }

    public function testFormatFractionAsImproperFractionStringReducesResultsToWholeNumbers(): void
    {
        $result = $this->action->execute(new Fraction(16, 4));

        $this->assertEquals('4', $result);
    }

    public function testFromatFractionAsImproperFractionStringReducesNegativeResultsToWholeNumbers(): void
    {
        $result = $this->action->execute(new Fraction(-16, 4));

        $this->assertEquals('-4', $result);
    }

    public function testFromatFractionAsImproperFractionStringMovesNegativeOnDenominatorToNumerator(): void
    {
        $result = $this->action->execute(new Fraction(3, -4));

        $this->assertEquals('-3/4', $result);
    }

    public function testFromatFractionAsImproperFractionStringReducesDoubleNegativeResultsToPositiveResults(): void
    {
        $result = $this->action->execute(new Fraction(-3, -4));

        $this->assertEquals('3/4', $result);
    }

    public function testFromatFractionAsImproperFractionStringDoesNotReduceResultsWhenReduceFlagIsFalse(): void
    {
        $result = $this->action->execute(new Fraction(10, 4), false);

        $this->assertEquals('10/4', $result);
    }
}
