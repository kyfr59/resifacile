<?php

namespace App\Providers;

use App\Contracts\Cart;
use App\Contracts\Pdf;
use App\Contracts\PostLetter;
use App\Enums\AppType;
use App\Services\CartService;
use App\Services\DomPdfService;
use App\Services\MailevaService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \URL::forceScheme('https');

        $this->app->singleton(Cart::class, fn() => new CartService());
        $this->app->singleton(Pdf::class, fn() => new DomPdfService());
        $this->app->singleton(PostLetter::class, fn() => new MailevaService(
            login: config('maileva.login'),
            password: config('maileva.password'),
        ));

        Blade::if('access', static fn(AppType $appType) => config('site.type') === $appType->value);

        Blade::directive('size', static function($expression) {
            return "<?php echo match(true) {
                (int)$expression >= 1000000 => number_format(round((int)$expression / 1000000, 2), 2, ',', '') . 'Mo',
                (int)$expression >= 1000 => round((int)$expression / 1000) . 'Ko',
                default => $expression . 'o',
            }; ?>";
        });

        Blade::directive('price', static fn($expression) => "<?php echo number_format(App\Helpers\Accounting::addTax($expression) / 100, 2, ',', ' ') . ' €'; ?>");

        Blade::directive('priceHtml', static fn($expression) => "<?php echo explode('.', App\Helpers\Accounting::addTax($expression) / 100)[0] . '<span class=\"font-light\">,</span>' . explode('.', App\Helpers\Accounting::addTax($expression) / 100)[1] . '<span class=\"font-light\">€</span>'; ?>");
    }
}
