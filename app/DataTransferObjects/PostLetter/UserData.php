<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    /**
     * @param string $auth_type
     * @param string $login
     * @param string $password
     */
    public function __construct(
        #[Required]
        public string $auth_type,
        #[Required,Size(60)]
        public string $login,
        #[Required,Size(60)]
        public string $password,
    ){
    }
}
