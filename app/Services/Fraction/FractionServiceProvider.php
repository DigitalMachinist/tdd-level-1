<?php

namespace App\Services\Fraction;

use Illuminate\Support\ServiceProvider;

class FractionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            FractionServiceContract::class,
            fn () => new FractionService(),
        );
    }
}