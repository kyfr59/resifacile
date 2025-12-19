<?php

namespace App\Filament\Widgets;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\DB;

class TinyFoxStats extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = DB::select('SELECT regexp_replace(substring(customers.data ->> \'partner\' FROM \'https?://(.*)\'), \'("|\*\$)\', \'\') AS site, CONCAT(ROUND(CAST(float8 (100 * count(transactions.id)) / (SELECT count(transactions.id) FROM transactions INNER JOIN subscriptions ON subscriptions.id = transactions.transactionable_id INNER JOIN customers ON customers.id = subscriptions.customer_id WHERE customers.data ->> \'partner\' <> \'none\' AND customers.data ->> \'partner\' IS NOT NULL AND transactions.transactionable_type = \'App\Models\Subscription\' AND EXTRACT(MONTH FROM transactions.created_at) = :month AND transactions.amount = 3990) as numeric), 2), \'%\') AS pourcentage, count(transactions.id) AS total FROM transactions INNER JOIN subscriptions ON subscriptions.id = transactions.transactionable_id INNER JOIN customers ON customers.id = subscriptions.customer_id WHERE customers.data ->> \'partner\' <> \'none\' AND customers.data ->> \'partner\' IS NOT NULL AND transactions.transactionable_type = \'App\Models\Subscription\' AND EXTRACT(MONTH FROM transactions.created_at) = :month AND transactions.amount = 3990 GROUP BY site;', [
            'month' => now()->subMonth()->format('m'),
        ]);

        $totalTinyFox = DB::select('SELECT count(transactions.id) AS total FROM transactions INNER JOIN subscriptions ON subscriptions.id = transactions.transactionable_id INNER JOIN customers ON customers.id = subscriptions.customer_id WHERE customers.data ->> \'partner\' <> \'none\' AND customers.data ->> \'partner\' IS NOT NULL AND transactions.transactionable_type = \'App\Models\Subscription\' AND EXTRACT(MONTH FROM transactions.created_at) = :month AND transactions.amount = 3990;', [
            'month' => now()->subMonth()->format('m'),
        ]);

        $total = DB::select('SELECT count(transactions.id) AS total FROM transactions INNER JOIN subscriptions ON subscriptions.id = transactions.transactionable_id INNER JOIN customers ON customers.id = subscriptions.customer_id WHERE transactions.transactionable_type = \'App\Models\Subscription\' AND EXTRACT(MONTH FROM transactions.created_at) = :month AND transactions.amount = 3990;', [
            'month' => now()->subMonth()->format('m'),
        ]);

        $arrayStats = collect($stats)->map(
            fn ($data) => Stat::make($data->site, $data->pourcentage)
                ->description('Revenu généré')
        )->toArray();

        return array_merge([
            Stat::make('Total', round((($totalTinyFox[0]->total / 2) * 100) / $total[0]->total, 2) . '%')
                ->description('Part de revenu versé à Tiny Fox')
                ->extraAttributes([
                    'style' => 'grid-column: span 2 / span 2',
                ]),
        ], $arrayStats);
    }

    public static function canView(): bool
    {
        return match (auth()->user()->email) {
            'jofrey@qilink.fr' => true,
            'j.gandillon@gmail.com' => true,
            'samuel@eurocb.net' => true,
            'thomas@mediagroup.app' => true,
            default => false,
        };
    }
}
