<?php

namespace App\Filament\Widgets;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\DB;

class GlobalStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Durée moyenne en mois', round(Subscription::selectRaw('ROUND(AVG(EXTRACT(MONTH FROM AGE(cancellation_request_at, created_at))), 2) AS months')
                ->where('status', SubscriptionStatus::RECURRING->value)
                ->first()->months, 2)),
            Stat::make('Moyen des acquisitions/30J', round(Trend::query(Subscription::query()->where('status', SubscriptionStatus::TRIAL->value))
                ->between(now()->subDays(30), now())
                ->perDay()
                ->count()
                ->average(fn ($value) => $value->aggregate), 2)),
            Stat::make('Abonnements période d\'essai', round(Subscription::where('status', SubscriptionStatus::TRIAL->value)->count(), 2))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Abonnements Actifs', round(Subscription::where('status', SubscriptionStatus::RECURRING->value)->count(), 2)),
            Stat::make('Abos annulés', round(Subscription::where('status', SubscriptionStatus::CANCELED->value)->count(), 2)),
            Stat::make('Abos avec réglement en retard', round(Subscription::where('status', SubscriptionStatus::LATE_PAYMENT->value)->count(), 2))
        ];
    }

    public static function canView(): bool
    {
        return match (auth()->user()->email) {
            'jofrey@qilink.fr' => true,
            'samuel@eurocb.net' => true,
            'thomas@mediagroup.app' => true,
            default => false,
        };
    }
}
