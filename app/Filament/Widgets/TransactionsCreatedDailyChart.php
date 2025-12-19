<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatus;
use App\Helpers\Accounting;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionsCreatedDailyChart extends ChartWidget
{
    protected static ?string $heading = 'Encaissements (ht), nbr d\'abos encaissées, nbr de refus et de chargebacks journaliers';

    public ?string $filter = '30';

    protected int | string | array $columnSpan = [
        'sm' => 2,
    ];

    protected static ?string $maxHeight = '200px';

    protected function getFilters(): ?array
    {
        return [
            '90' => '90 jours glissants',
            '60' => '60 jours glissants',
            '30' => '30 jours glissants',
            '15' => '15 jours glissants',
            '7' => '7 jours glissants',
        ];
    }

    protected function getData(): array
    {
        $dataCapturedAmount = Trend::query(Transaction::query()->where('status', TransactionStatus::CAPTURED->value))
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->sum('amount');

        $dataCapturedCount = Trend::query(Transaction::query()->where('amount', 3990)->where('status', TransactionStatus::CAPTURED->value))
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        $dataRefused = Trend::query(Transaction::query()->whereIn('status', [
            TransactionStatus::BLOCKED->value,
            TransactionStatus::DENIED->value,
            TransactionStatus::REFUSED->value
        ]))
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        $dataChargeBacked = Trend::query(Transaction::query()->where('status', TransactionStatus::CHARGED_BACK->value))
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Capturées',
                    'data' => $dataCapturedAmount->map(fn (TrendValue $value) => Accounting::removeTax($value->aggregate) / 100),
                    'borderColor' => '#4ca054',
                    'backgroundColor' => '#f2fdf5',
                    'yAxisID' => 'y',
                    'order' => 4,
                ],
                [
                    'label' => 'Nbr d\'Abos Capturées',
                    'data' => $dataCapturedCount->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4ca054',
                    'backgroundColor' => '#f2fdf5',
                    'yAxisID' => 'y1',
                    'type' => 'line',
                    'order' => 3,
                ],
                [
                    'label' => 'Nbr Refusées',
                    'data' => $dataRefused->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#ca3a31',
                    'backgroundColor' => '#fcf2f2',
                    'yAxisID' => 'y1',
                    'type' => 'line',
                    'order' => 2,
                ],
                [
                    'label' => 'Nbr Chargebacks',
                    'data' => $dataChargeBacked->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#52525a',
                    'backgroundColor' => '#fafafa',
                    'yAxisID' => 'y1',
                    'type' => 'line',
                    'order' => 1,
                ]
            ],
            'labels' => $dataCapturedAmount->map(fn (TrendValue $value) => (new Carbon($value->date))->format('d/m/Y')),
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
