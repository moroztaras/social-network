<?php

namespace App\Tests;
use App\Tests\MyException;

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

    public function getEx($x)
    {
        if ($x == 10)
        {
            throw new MyException('Wrong data');
        }
        else
        {
            return true;
        }
    }
}
