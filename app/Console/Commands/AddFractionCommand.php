<?php

namespace App\Console\Commands;

use App\Services\Fraction\FractionServiceContract;
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
    public function handle(FractionServiceContract $fractionService)
    {
        $fractions = array_map(
            fn (string $input) => $fractionService->fromString($input),
            $this->argument('fractions')
        );

        $result = $fractionService->add($fractions);

        $this->info($fractionService->toImproperFractionString($result));
    }
}
