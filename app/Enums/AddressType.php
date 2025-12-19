<?php

namespace App\Enums;

enum AddressType: string
{
    case PROFESSIONAL = 'PROFESSIONAL';
    case PERSONAL = 'PERSONAL';

    public function maileva(): string
    {
        return match($this)
        {
            self::PROFESSIONAL => 'PROFESSIONAL',
            self::PERSONAL => 'INDIVIDUAL',
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::PROFESSIONAL => 'Adresse professionnelle',
            self::PERSONAL => 'Adresse personnelle',
        };
    }

    public function asHtml(): string
    {
        return match($this)
        {
            self::PROFESSIONAL => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-blue-100 text-blue-700 dark:bg-blue-700 dark:text-blue-400">professionnel</span>',
            self::PERSONAL => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-gray-100 text-gray-500 dark:bg-gray-900 dark:text-gray-400">personnel</span>',
        };
    }
}
