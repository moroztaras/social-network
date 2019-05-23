<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Message\MessageForm;
use App\Form\Message\Model\MessageModel;
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * MessageController constructor.
     *
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

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
     * @Route("/user/{id}/message", name="user_message", requirements={"id"="\d+"})
     */
    public function userMessage($id, Request $request, MessageModel $messageModel)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }

        $receiver = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(MessageForm::class, $messageModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageService->send($user, $receiver, $messageModel->getMessage());

            return $this->redirectToRoute('svistyn_post_user', ['id' => $id]);
        }

        return $this->render('Message/add.html.twig', [
          'receiver' => $receiver,
          'form' => $form->createView(),
        ]);
    }
}
