<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\Message\MessageForm;
use App\Form\Message\MessageEditForm;
use App\Form\Message\Model\MessageModel;
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class MessageController extends Controller
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * MessageController constructor.
     *
     * @param MessageService    $messageService
     * @param FlashBagInterface $flashBag
     */
    public function __construct(MessageService $messageService, FlashBagInterface $flashBag)
    {
        $this->messageService = $messageService;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/messages", name="user_messages_list")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userMessages()
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }

        return $this->render('Message/index.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/messages/{id_dialogue}_{id_receiver}", name="user_dialogue_messages_list", requirements={"id"="\d+"})
     *
     * @param $id_dialogue
     * @param $id_receiver
     * @param Request      $request
     * @param MessageModel $messageModel
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userDialogueMessages($id_dialogue, $id_receiver, Request $request, MessageModel $messageModel)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        $messages = $this->getDoctrine()->getRepository(Message::class)->getMessagesForDialogue($id_dialogue);
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($id_receiver);
        $form = $this->createForm(MessageForm::class, $messageModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageService->send($user, $receiver, $messageModel->getMessage());

            return $this->redirectToRoute('user_dialogue_messages_list',
              [
                'id_dialogue' => $id_dialogue,
                'id_receiver' => $id_receiver,
              ]);
        }

        return $this->render('Message/list.html.twig', [
          'user' => $user,
          'messages' => $messages,
          'form_message' => $form->createView(),
          'receiver' => $receiver,
          'dialogue' => $id_dialogue,
        ]);
    }

    /**
     * @Route("/user/{id}/message", name="user_message", requirements={"id"="\d+"})
     *
     * @param $id
     * @param Request      $request
     * @param MessageModel $messageModel
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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

    /**
     * @Route("/message/{id_message}_{id_dialogue}_{id_receiver}/edit", name="message_edit", requirements={"id_message"="\d+", "id_dialogue"="\d+", "id_receiver"="\d+"})
     *
     * @param $id_message
     * @param $id_dialogue
     * @param $id_receiver
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMessage($id_message, $id_dialogue, $id_receiver, Request $request)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }

        $message = $this->getDoctrine()->getRepository(Message::class)->find($id_message);
        if ($message->getSender() != $user) {
            $this->flashBag->add('danger', 'edit_message_is_forbidden');

            return $this->redirectToRoute('user_dialogue_messages_list',
              [
                'id_dialogue' => $id_dialogue,
                'id_receiver' => $id_receiver,
              ]);
        }
        $messages = $this->getDoctrine()->getRepository(Message::class)->getMessagesForDialogue($id_dialogue);
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($id_receiver);
        $form = $this->createForm(MessageEditForm::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageService->edit($message);

            return $this->redirectToRoute('user_dialogue_messages_list',
              [
                'id_dialogue' => $id_dialogue,
                'id_receiver' => $id_receiver,
              ]);
        }

        return $this->render('Message/edit.html.twig', [
          'user' => $user,
          'receiver' => $receiver,
          'form_message' => $form->createView(),
          'id_dialogue' => $id_dialogue,
          'messages' => $messages,
        ]);
    }

    /**
     * * @Route("/message/{id_message}/delete", name="message_delete", requirements={"id_message"="\d+"})
     */
    public function removeMessage($id_message, Request $request)
    {
        $referer = $request->headers->get('referer');
        $user = $this->getUser();
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id_message);

        if ($message->getSender() != $user) {
            $this->flashBag->add('danger', 'delete_message_is_forbidden');

            return $this->redirect($referer);
        }
        $this->messageService->remove($message);
        $this->flashBag->add('danger', 'message_was_deleted_successfully');

        return $this->redirect($referer);
    }
}
