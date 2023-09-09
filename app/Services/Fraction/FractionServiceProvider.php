<?php

namespace App\Services\Fraction;

use App\Services\Fraction\Actions\AddFractions;
use App\Services\Fraction\Actions\CloneFraction;
use App\Services\Fraction\Actions\CreateFractionFromImproperFractionString;
use App\Services\Fraction\Actions\FormatFractionAsImproperFractionString;
use App\Services\Fraction\Actions\ReduceFraction;
use Illuminate\Support\ServiceProvider;

class FractionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            FractionServiceContract::class,
            fn () => new FractionService(
                $this->app->make(CloneFraction::class),
                $this->app->make(CreateFractionFromImproperFractionString::class),
                $this->app->make(AddFractions::class),
                $this->app->make(ReduceFraction::class),
                $this->app->make(FormatFractionAsImproperFractionString::class),
            ),
        );
    }
}