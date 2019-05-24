<?php

namespace App\Services;

use App\Entity\Message;
use App\Entity\Dialogue;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class MessageService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * MessageService constructor.
     *
     * @param ManagerRegistry   $doctrine
     * @param FlashBagInterface $flashBag
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
    }

    public function send(User $sender, User $receiver, $message)
    {
        if (null !== $this->getDialogue($sender, $receiver)) {
            $this->save($this->getDialogue($sender, $receiver), $sender, $receiver, $message);
        } elseif (null !== $this->getDialogue($receiver, $sender)) {
            $this->save($this->getDialogue($receiver, $sender), $sender, $receiver, $message);
        } else {
            $this->newDialogue($sender, $receiver, $message);
        }
        $this->flashBag->add('success', 'message_send');

        return $this;
    }

    private function getDialogue(User $userFirst, User $userSecond)
    {
        $dialogue = $this->doctrine->getRepository(Dialogue::class)->findOneBy(['creator' => $userFirst, 'receiver' => $userSecond]);

        return $dialogue;
    }

    private function save(Dialogue $dialogue, User $sender, User $receiver, $message)
    {
        $new_message = new Message();
        $new_message
          ->setMessage($message)
          ->setDialogue($dialogue)
          ->setSender($sender)
          ->setReceiver($receiver);

        $this->doctrine->getManager()->persist($new_message);
        $this->doctrine->getManager()->flush();
    }

    private function newDialogue(User $sender, User $receiver, $message)
    {
        $new_dialogue = new Dialogue();
        $new_message = new Message();
        $new_dialogue
          ->setCreator($sender)
          ->setReceiver($receiver);
        $new_message
          ->setMessage($message)
          ->setDialogue($new_dialogue)
          ->setSender($sender)
          ->setReceiver($receiver);

        $this->doctrine->getManager()->persist($new_message);
        $this->doctrine->getManager()->persist($new_dialogue);
        $this->doctrine->getManager()->flush();
    }
}
