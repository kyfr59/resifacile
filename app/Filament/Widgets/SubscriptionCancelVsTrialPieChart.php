<?php

namespace App\Filament\Widgets;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionCancelVsTrialPieChart extends ChartWidget
{
    protected static ?string $heading = 'Abonnements en période d\'essai et annulés sur les 15 derniers jours';

    protected static ?int $sort = 1;

    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $data = Subscription::query()
            ->whereIn('status', [
                SubscriptionStatus::CANCELED->value,
                SubscriptionStatus::TRIAL->value,
            ])
            ->whereBetween('updated_at', [
                now()->subDays(15),
                now(),
            ])
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
                        '#52525a',
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
