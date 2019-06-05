<?php

namespace App\Components\User\Models;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordModel
{
    /**
     * @Assert\Length(min="8", max="50")
     */
    public $plainPassword;
}
