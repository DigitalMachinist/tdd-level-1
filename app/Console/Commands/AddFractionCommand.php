<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class AddFractionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fraction:add
                            { fractions* : The fraction to add, as string imporper fractions (e.g. 4/3). }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an arbitrary number of fractions together. Must be written as improper fractions.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fractions = $this->argument('fractions');

        foreach ($fractions as $fraction) {
            if (!preg_match('/^\d+\/\d+$/', $fraction)) {
                throw new Exception('Invalid fraction: ' . $fraction);
            }
        }

        $parsedFractions = array_map(function ($fraction) {
            return explode('/', $fraction);
        }, $fractions);

        // dump($parsedFractions);

        $denominator = array_reduce($parsedFractions, function ($carry, $item) {
            return $carry * $item[1];
        }, 1);

        // dump($denominator);

        $scaledFractions = array_map(function ($fraction) use ($denominator) {
            return [
                $fraction[0] * ($denominator / $fraction[1]),
                $denominator,
            ];
        }, $parsedFractions);

        // dump($scaledFractions);

        $numerator = array_reduce($scaledFractions, function ($carry, $item) {
            return $carry + $item[0];
        }, 0);

        // dump($numerator);

        $fraction = [
            $numerator,
            $denominator,
        ];

        // dump($fraction);

        $i = 2;
        $reducedFraction = $fraction;
        while ($i <= min($reducedFraction[0], $reducedFraction[1])) {
            if ($reducedFraction[0] % $i === 0 && $reducedFraction[1] % $i === 0) {
                $reducedFraction = [
                    $reducedFraction[0] / $i,
                    $reducedFraction[1] / $i,
                ];
                // dump($reducedFraction);
                $i = 2;
            }
            else {
                $i++;
            }
        }

        if ($reducedFraction[1] > 1) {
            $this->info($reducedFraction[0] . '/' . $reducedFraction[1]);
        }
        else {
            $this->info($reducedFraction[0]);
        }
    }
}
