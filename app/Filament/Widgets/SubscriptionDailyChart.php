<?php

namespace App\Filament\Widgets;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SubscriptionDailyChart extends ChartWidget
{
    protected static ?string $heading = 'Abonnements en période d\'essai, en cours, en rédemption et annulés journaliers';

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
        $dataTrial = Trend::query(
            Subscription::query()
                ->where('status', SubscriptionStatus::TRIAL->value)
                ->when(auth()->user()->email === 'j.gandillon@gmail.com', fn ($query, $filter) => $query->whereHas('customer', fn ($query) => $query->whereRaw("data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL")))
        )
            ->dateColumn('updated_at')
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        $dataRecurring = Trend::query(
            Subscription::query()
                ->where('status', SubscriptionStatus::RECURRING->value)
                ->when(auth()->user()->email === 'j.gandillon@gmail.com', fn ($query, $filter) => $query->whereHas('customer', fn ($query) => $query->whereRaw("data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL")))
        )
            ->dateColumn('updated_at')
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        $dataLatePayment = Trend::query(
            Subscription::query()
                ->where('status', SubscriptionStatus::LATE_PAYMENT->value)
                ->when(auth()->user()->email === 'j.gandillon@gmail.com', fn ($query, $filter) => $query->whereHas('customer', fn ($query) => $query->whereRaw("data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL")))
        )
            ->dateColumn('updated_at')
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        $dataCanceled= Trend::query(
            Subscription::query()
                ->where('status', SubscriptionStatus::CANCELED->value)
                ->when(auth()->user()->email === 'j.gandillon@gmail.com', fn ($query, $filter) => $query->whereHas('customer', fn ($query) => $query->whereRaw("data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL")))
        )
            ->dateColumn('updated_at')
            ->between(now()->subDays((int)$this->filter), now())
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Abos en période d\'essai',
                    'data' => $dataTrial->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#52525a',
                    'backgroundColor' => '#fafafa',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Abos en cours',
                    'data' => $dataRecurring->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4ca054',
                    'backgroundColor' => '#f2fdf5',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Abos en rédemption',
                    'data' => $dataLatePayment->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#cc7c2e',
                    'backgroundColor' => '#fefbed',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Abos annulés',
                    'data' => $dataCanceled->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#ca3a31',
                    'backgroundColor' => '#fcf2f2',
                    'yAxisID' => 'y',
                ],
            ],
            'labels' => $dataTrial->map(fn (TrendValue $value) => (new Carbon($value->date))->format('d/m/Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
