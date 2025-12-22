<?php

namespace App\Filament\Pages;

use Filament\Panel;
use Filament\Support\Enums\MaxWidth;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function shouldRegisterNavigation(): bool
    {
        return match (auth()->user()->email) {
            'kyfr59@gmail.com' => true,
            'j.gandillon@gmail.com' => true,
            'juliette@tiny-fox.com' => true,
            'fabien@tiny-fox.com' => true,
            default => false,
        };
    }
}
