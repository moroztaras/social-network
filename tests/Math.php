<?php

namespace App\Tests;

class Math
{
    public function sqrt($x)
    {
        return sqrt($x);
    }

    public function true($x)
    {
        if ($x == 36)
        {
            return true;
        }
        if ($x == 49)
        {
            return false;
        }
    }

    public function getArray($array)
    {
        return explode("|",$array);
    }
}
