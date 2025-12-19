<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class OptionData extends Data
{
    /**
     * @param string|int $name
     * @param float|array $price
     */
    public function __construct(
        public string|int $name,
        public float|array $price,
    ){
    }
}
