<?php

namespace App\Providers;

use App\Registries\PaymentGatewayRegistry;
use App\Services\Payments\HipayDriver;
use App\Services\Payments\StripeDriver;
use App\Services\Payments\SubscriberDriver;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayRegistry::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->app->make(PaymentGatewayRegistry::class)
            ->register('00001337397', new HipayDriver(
                user: config('hipay.00001337397.user', 'azerty'),
                password: config('hipay.00001337397.password', 'azerty'),
                env: config('hipay.00001337397.env', 'azerty'),
            ))
            ->register('00001337396', new HipayDriver(
                user: config('hipay.00001337396.user', 'azerty'),
                password: config('hipay.00001337396.password', 'azerty'),
                env: config('hipay.00001337396.env', 'azerty'),
            ));
    }
}
