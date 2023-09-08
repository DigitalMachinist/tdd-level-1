<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddFractionCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testAddFractionCommandAddsSimpleFractions(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['1/2', '1/3'],
            ])
            ->expectsOutputToContain('5/6')
            ->execute();
    }

    public function testAddFractionCommandAddsImproperFractions(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['1/2', '4/3'],
            ])
            ->expectsOutputToContain('11/6')
            ->execute();
    }

    public function testAddFractionCommandReducesResults(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['10/4', '8/4'],
            ])
            ->expectsOutputToContain('9/2')
            ->execute();
    }

    public function testAddFractionCommandReducesResultsToWholeNumbers(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['10/4', '6/4'],
            ])
            ->expectsOutputToContain('4')
            ->execute();
    }

    public function testAddFractionCommandReducesNegativeResultsToWholeNumbers(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['-10/4', '-6/4'], // Need the -- to satisfy bash that this isn't a flag.
            ])
            ->expectsOutputToContain('-4')
            ->execute();
    }
    

    public function testAddFractionCommandReducesDoubleNegativeResultsToPositiveResults(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['1/4', '-1/2'],
            ])
            ->expectsOutputToContain('-1/4')
            ->execute();
    }

    public function testAddFractionCommandMovesNegativeOnDenominatorToNumerator(): void
    {
        $this
            ->artisan('fraction:add', [
                'fractions' => ['1/-4', '1/-2'],
            ])
            ->expectsOutputToContain('-3/4')
            ->execute();
    }

    public function testAddFractionCommandThrowsExceptionForInvalidInputs(): void
    {
        $this->expectException(Exception::class);
        $this
            ->artisan('fraction:add', [
                'fractions' => ['0.25', '.5'],
            ])
            ->execute();
    }

}
