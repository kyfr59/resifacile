<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class ChargebackMonthlyByMidChart extends ChartWidget
{
    protected static ?string $heading = 'Chargebacks mensuels par MID';

    protected int | string | array $columnSpan = [
        'sm' => 2,
    ];

    public ?string $filter = '1';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '200px';

    protected function getFilters(): ?array
    {
        return [
            '1' => '2 mois glissant',
            '2' => '3 mois glissants',
            '5' => '6 mois glissants',
            '11' => '12 mois glissants',
        ];
    }

    protected function getData(): array
    {
        $dataAll = Trend::query(
            Transaction::query()
                ->where('status', TransactionStatus::CHARGED_BACK->value)
                ->when(
                    auth()->user()->email === 'j.gandillon@gmail.com',
                    fn ($query, $filter) => $query
                        ->whereHas('transactionable', fn ($query) => $query
                            ->whereHas('customer', fn ($query) => $query->whereRaw("data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL"))
                        )
                )
            )
            ->between(now()->subMonths((int)$this->filter)->firstOfMonth(), now()->lastOfMonth())
            ->perMonth()
            ->count();

        $dataMid1 = Trend::query(
            Transaction::query()
                ->where('status', TransactionStatus::CHARGED_BACK->value)
                ->where('mid', '00001337396')
                ->when(
                    auth()->user()->email === 'j.gandillon@gmail.com',
                    fn ($query, $filter) => $query->whereHas('transactionable', fn ($query) => $query
                        ->whereHas('customer', fn ($query) => $query
                            ->whereRaw("customers.data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL"))
                    )
                )
            )
            ->between(now()->subMonths((int)$this->filter)->firstOfMonth(), now()->lastOfMonth())
            ->perMonth()
            ->count();

        $dataMid2 = Trend::query(
            Transaction::query()->where('status',
                TransactionStatus::CHARGED_BACK->value)
                    ->where('mid', '00001337397')
                    ->when(
                        auth()->user()->email === 'j.gandillon@gmail.com',
                        fn ($query, $filter) => $query->whereHas('transactionable', fn ($query) => $query
                            ->whereHas('customer', fn ($query) => $query
                                ->whereRaw("customers.data ->> 'partner' <> 'none'")->whereRaw("data ->> 'partner' IS NOT NULL"))
                        )
                    )
            )
            ->between(now()->subMonths((int)$this->filter), now())
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tous les MID',
                    'data' => $dataAll->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#52525a',
                    'backgroundColor' => '#fafafa',
                    'yAxisID' => 'y',
                    'type' => 'line',
                ],
                [
                    'label' => 'MID 00001337396',
                    'data' => $dataMid1->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#52525a',
                    'backgroundColor' => '#fafafa',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'MID 00001337397',
                    'data' => $dataMid2->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4ca054',
                    'backgroundColor' => '#f2fdf5',
                    'yAxisID' => 'y',
                ],
            ],
            'labels' => $dataMid1->map(fn (TrendValue $value) => (new Carbon($value->date))->format('m/Y')),
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
            'j.gandillon@gmail.com' => true,
            'samuel@eurocb.net' => true,
            'thomas@mediagroup.app' => true,
            default => false,
        };
    }
}
