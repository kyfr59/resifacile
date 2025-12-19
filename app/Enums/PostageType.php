<?php

namespace App\Enums;

use App\Settings\PricingSettings;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

enum PostageType: string
{
    case FOLLOWED_LETTER = 'FOLLOWED_LETTER';
    case GREEN_LETTER = 'GREEN_LETTER';
    case REGISTERED_LETTER = 'REGISTERED_LETTER';
    case ECONOMIC_LETTER = 'ECONOMIC_LETTER';
    case DOWNLOADABLE_LETTER = 'DOWNLOADABLE_LETTER';

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this)
        {
            self::FOLLOWED_LETTER => 'Lettre suivie',
            self::GREEN_LETTER => 'Lettre verte',
            self::REGISTERED_LETTER => 'Lettre recommandée',
            self::ECONOMIC_LETTER => 'Lettre éco',
            self::DOWNLOADABLE_LETTER => 'Lettre téléchargée',
        };
    }

    /**
     * @return array
     */
    public function price(): array
    {
        $pricing = App::make(PricingSettings::class);

        return match($this)
        {
            self::FOLLOWED_LETTER => $pricing->followed_letter,
            self::GREEN_LETTER, self::ECONOMIC_LETTER => $pricing->green_letter,
            self::REGISTERED_LETTER => $pricing->registered_letter,
            self::DOWNLOADABLE_LETTER => [],
        };
    }

    /**
     * @return array
     */
    public function option(): array
    {
        $pricing = App::make(PricingSettings::class);

        return match($this)
        {
            self::FOLLOWED_LETTER => [
                'sms_notification' => $pricing->sms_notification
            ],
            self::GREEN_LETTER, self::ECONOMIC_LETTER, self::DOWNLOADABLE_LETTER => [],
            self::REGISTERED_LETTER => [
                'receipt' => $pricing->receipt,
                'sms_notification' => $pricing->sms_notification
            ],
        };
    }
}
