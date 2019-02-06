<?php

namespace App\Components\Utils;

class ValidatorCheck
{
    public static function email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
