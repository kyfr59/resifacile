<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Jeffgreco13\FilamentBreezy\Livewire\TwoFactorAuthentication;

class BreezyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Livewire::component(
            'two_factor_authentication',
            TwoFactorAuthentication::class
        );
    }
}