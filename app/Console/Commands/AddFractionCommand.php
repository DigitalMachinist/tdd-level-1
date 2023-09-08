<?php

namespace App\Console\Commands;

use App\Services\Fraction\FractionServiceContract;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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
    public function handle(FractionServiceContract $fractionService)
    {
        $inputs = new Collection($this->argument('fractions'));

        $fractions = $inputs->map(fn (string $x) => $fractionService->fromString($x));

        $result = $fractionService->add($fractions->toArray());

        $this->info($fractionService->toImproperFractionString($result));
    }
}
