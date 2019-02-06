<?php

namespace App\Components\User\Models;

use Symfony\Component\Validator\Constraints as Assert;

class RecoverUserModel
{
    /**
     * @Assert\NotBlank()
     */
    public $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
