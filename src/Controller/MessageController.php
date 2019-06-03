<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\Message\MessageForm;
use App\Form\Message\MessageEditForm;
use App\Form\Message\Model\MessageModel;
use App\Services\MessageService;
use Proxies\__CG__\App\Entity\Dialogue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Knp\Component\Pager\PaginatorInterface;

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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * MessageController constructor.
     *
     * @param MessageService    $messageService
     * @param FlashBagInterface $flashBag
     */
    public function __construct(MessageService $messageService, FlashBagInterface $flashBag, PaginatorInterface $paginator)
    {
        $this->messageService = $messageService;
        $this->flashBag = $flashBag;
        $this->paginator = $paginator;
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
     * @Route("/messages/{id_dialogue}_{id_receiver}", name="user_dialogue_messages_list", requirements={"id_dialogue"="\d+","id_receiver"="\d+"})
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

        $messages = $this->paginator->paginate(
          $this->getDoctrine()->getRepository(Message::class)->getMessagesForDialogue($id_dialogue),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

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
     * @Route("/message/{id_message}/delete", name="message_delete", requirements={"id_message"="\d+"})
     *
     * @param $id_message
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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

    /**
     * @Route("/dialogue/{id_dialogue}/delete", methods={"GET"}, name="dialogue_delete", requirements={"id_dialogue"="\d+"})
     *
     * @param $id_dialogue
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeDialogue($id_dialogue)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        if (!$dialogue = $this->getDoctrine()->getRepository(Dialogue::class)->find($id_dialogue)) {
            $this->flashBag->add('danger', 'dialogue_not_found');

            return $this->redirectToRoute('user_messages_list');
        }
        if ($dialogue->getCreator() != $user) {
            $this->flashBag->add('danger', 'delete_dialogue_is_forbidden');

            return $this->redirectToRoute('user_messages_list');
        }
        $this->messageService->removeDialogue($dialogue);
        $this->flashBag->add('success', 'dialogue_was_deleted');

        return $this->redirectToRoute('user_messages_list');
    }

    public function getCountAllNotReadMessages()
    {
        $user = $this->getUser();
        $messages = $this->getDoctrine()->getRepository(Message::class)->getCountAllNotReadMessages($user);

        return $this->render('Dialogue/ModeView/not_read_messages.html.twig', [
          'messages' => count($messages),
        ]);
    }

    public function messageRead(Message $message)
    {
        $this->messageService->changeMessageStatus($message);

        return new Response();
    }

    public function getCountNotReadMessages($id_dialogue)
    {
        $user = $this->getUser();
        $messages = $this->getDoctrine()->getRepository(Message::class)->getCountNotReadMessagesInDialogue($id_dialogue, $user);

        return $this->render('Dialogue/ModeView/not_read_messages.html.twig', [
          'messages' => count($messages),
        ]);
    }

//    /**
//     * @Route("/messages/{id_dialogue}/scroll", name="message_infinite_scroll")
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function infiniteScroll($id_dialogue)
//    {
//        return $this->render('Message/infiniteScroll.html.twig', [
//          'id_dialogue' => $id_dialogue
//        ]);
//    }
//
//    /**
//     * @Route("/message/{id_dialogue}", name="get_all_messages_for_dialogue")
//     * @param $id_dialogue
//     * @return \Symfony\Component\HttpFoundation\JsonResponse
//     */
//    public function getMessagesForDialogue($id_dialogue)
//    {
//        return $this->json([
//          'message' => $this->getDoctrine()->getRepository(Message::class)->getMessagesForDialogue($id_dialogue)
//        ]);
//    }
}
