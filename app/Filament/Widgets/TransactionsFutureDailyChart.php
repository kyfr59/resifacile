<?php

namespace App\Filament\Widgets;

use App\Enums\SubscriptionStatus;
use App\Helpers\Accounting;
use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionsFutureDailyChart extends ChartWidget
{
    protected static ?string $heading = 'Encaissements (ht) prévetionnels journaliers';

    public ?string $filter = '31';

    protected int | string | array $columnSpan = [
        'sm' => 2,
    ];

    protected static ?string $maxHeight = '200px';

    protected function getFilters(): ?array
    {
        return [
            '91' => '90 jours glissants',
            '61' => '60 jours glissants',
            '31' => '30 jours glissants',
            '16' => '15 jours glissants',
            '8' => '7 jours glissants',
        ];
    }

    protected function getData(): array
    {
        $dataCaptured = Trend::query(Subscription::query()->whereIn('status', [
            SubscriptionStatus::TRIAL->value,
            SubscriptionStatus::RECURRING->value,
            SubscriptionStatus::LATE_PAYMENT->value,
        ]))
            ->dateColumn('current_period_end_at')
            ->between(now()->addDay(), now()->addDays((int)$this->filter))
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Encaissements prévisionnels (ht)',
                    'data' => $dataCaptured->map(fn (TrendValue $value) => $value->aggregate * Accounting::removeTax(39.90)),
                    'borderColor' => '#4ca054',
                    'backgroundColor' => '#f2fdf5',
                    'yAxisID' => 'y',
                    'order' => 2,
                ],
                [
                    'label' => 'Nbr d\'Abos à encaisser',
                    'data' => $dataCaptured->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4ca054',
                    'backgroundColor' => '#f2fdf5',
                    'yAxisID' => 'y1',
                    'order' => 1,
                    'type' => 'line',
                ]
            ],
            'labels' => $dataCaptured->map(fn (TrendValue $value) => (new Carbon($value->date))->format('d/m/Y')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
