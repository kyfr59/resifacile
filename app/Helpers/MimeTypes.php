<?php

namespace App\Helpers;

class MimeTypes
{
    public const MINE_TYPES = [
        'application/pdf',
        'application/octet-stream',
    ];

    /**
     * @return string
     */
    public static function authorized(): string
    {
        return implode(',', self::MINE_TYPES);
    }

    /**
     * @return string
     */
    public static function authorizedWithQuotes(): string
    {
        return "'" . implode("','", self::MINE_TYPES) . "'";
    }
}
