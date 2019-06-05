<?php

namespace App\Form\Message\Model;

class MessageModel
{
    /**
     * @var string
     */
    public $message;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }
}
