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
        if ($dialog = $this->getDialogue($sender, $receiver)) {
            $this->save($dialog, $sender, $receiver, $message);
        } elseif ($dialog = $this->getDialogue($receiver, $sender)) {
            $this->save($dialog, $sender, $receiver, $message);
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
        $dialogue->setUpdatedAt(new \DateTime());
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

    public function edit($message)
    {
        $this->doctrine->getManager()->persist($message);
        $this->doctrine->getManager()->flush();
    }

    public function remove(Message $message)
    {
        $this->doctrine->getManager()->remove($message);
        $this->doctrine->getManager()->flush();
    }

    public function removeDialogue(Dialogue $dialogue)
    {
        $this->doctrine->getManager()->remove($dialogue);
        $this->doctrine->getManager()->flush();
    }

    public function changeMessageStatus(Message $message)
    {
        $message->setStatus(1);
        $this->edit($message);
    }
}
