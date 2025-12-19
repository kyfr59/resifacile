<?php

namespace App\Enums;

enum PaymentMethodType: string
{
    case CARD = 'CARD';

    public function label(): string
    {
        return match($this)
        {
            self::CARD => 'Carte',
        };
    }

    public function asHtml(): string
    {
        return match ($this) {
            self::CARD => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-red-100 text-red-600 dark:bg-red-400 dark:text-red-900"><span class="mr-1 -ml-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20" class="inline-block" role="presentation"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></span>' . self::CARD->label() . '</span>',
        };
    }
}
