<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionPieDailyChart extends ChartWidget
{
    protected static ?string $heading = 'Statut des abonnements';

    protected static ?int $sort = 0;

    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $data = Subscription::query()
            ->get()
            ->groupBy('status')
            ->map(fn ($status) => $status->count())
            ->toArray();

        return [
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#ca3a31',
                        '#4ca054',
                        '#52525a',
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
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
