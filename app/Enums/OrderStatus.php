<?php

namespace App\Enums;

enum OrderStatus: string
{
    case UNPAID = 'UNPAID';
    case PAID = 'PAID';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Non réglée',
            self::PAID => 'Réglée',
        };
    }

    public function asHtml(): string
    {
        return match ($this) {
            self::UNPAID => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-red-100 text-red-600 dark:bg-red-400 dark:text-red-900"><span class="mr-1 -ml-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20" class="inline-block" role="presentation"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></span>' . self::UNPAID->label() . '</span>',
            self::PAID => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-green-100 text-green-600 dark:bg-green-400 dark:text-green-900"><span class="mr-1 -ml-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20" class="inline-block" role="presentation"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></span>' . self::PAID->label() . '</span>',
        };
    }

    public static function values(): array
    {
        return [
            self::UNPAID->value => self::UNPAID->label(),
            self::PAID->value => self::PAID->label(),
        ];
    }
}
