<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;

class MakeCouFromCampaign
{
    /**
     * @param string $login
     * @param array $files
     * @return string
     */
    public function handle(string $login, array $files): string
    {
        $cou  = "CLIENT_ID=" . $login . "\n";
        $cou .= "NB_FILE=" . count($files) . "\n";
        $cou .= "GATEWAY=PAPER\n";

        foreach ($files as $index => $file) {
            $cou .= "FILE_NAME_" . ($index + 1) . "=" . last(explode('/', $file)) . "\n";
            $cou .= "FILE_SIZE_" . ($index + 1) . "=" . Storage::size($file) . "\n";
        }

        return utf8_decode($cou);
    }
}
