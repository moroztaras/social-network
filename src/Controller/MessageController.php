<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Message\MessageForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    /**
     * @Route("/messages", name="user_messages_list")
     */
    public function userMessages()
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }

        return $this->render('Message/list.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/message", name="user_message")
     */
    public function userMessage($id)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(MessageForm::class);

        return $this->render('Message/add.html.twig', [
          'receiver' => $receiver,
          'form' => $form->createView(),
        ]);
    }
}
