<?php

namespace Tests\Unit\Actions;

use App\Services\Fraction\Actions\CreateFractionFromImproperFractionString;
use App\Services\Fraction\Fraction;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFractionFromImproperFractionStringTest extends TestCase
{
    use RefreshDatabase;

    private readonly CreateFractionFromImproperFractionString $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(CreateFractionFromImproperFractionString::class);
    }

    public function testCreateFractionFromImproperFractionStringExecutesSuccessfully(): void
    {
        $fraction1 = new Fraction(1, 2);
        $fraction2 = $this->action->execute('1/2');

        $this->assertNotSame($fraction1, $fraction2);
        $this->assertEquals($fraction1->numerator, $fraction2->numerator);
        $this->assertEquals($fraction1->denominator, $fraction2->denominator);
    }

    public function testCreateFractionFromImproperFractionStringThrowsExceptionForInvalidInputs(): void
    {
        $this->expectException(Exception::class);
        $this->action->execute('1/2/3');

        $this->expectException(Exception::class);
        $this->action->execute('3 1/3');

        $this->expectException(Exception::class);
        $this->action->execute('0.25');
    }
}