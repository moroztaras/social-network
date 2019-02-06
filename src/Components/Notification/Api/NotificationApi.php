<?php

namespace App\Components\Notification\Api;

use App\Components\Api\ApiBaseInterface;
use App\Components\Notification\NotificationManager;
use App\Components\User\UserManager;
use App\Components\Utils\View\ViewJson;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NotificationApi implements ApiBaseInterface
{
    private $em;

    private $request;

    private $userManager;

    private $container;
    private $notificationManager;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $requestStack,
                               UserManager $userManager, ContainerInterface  $container, NotificationManager $notificationManager)
    {
        $this->em = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
        $this->userManager = $userManager;
        $this->container = $container;
        $this->notificationManager = $notificationManager;
    }

    public function init()
    {
        // TODO: Implement init() method.
    }

    public function access()
    {
        return true;
    }

    public function handler()
    {
        $cmd = $this->request->query->get('cmd');
        switch ($cmd) {
      case 'user_notification':
        $this->userNotificationHandler();
        break;
      case 'mask_as_read':
        $this->maskAsReadHandler();
        break;
      case 'fetch':
        $this->fetchHandler();
        break;
    }
    }

    private function userNotificationHandler()
    {
        $user = $this->userManager->currentUser();
        if (!$user) {
            return;
        }

        $isLoad = $this->request->query->get('is_load');
        $twig = $this->container->get('twig');
        $notifications = $this->em->getRepository(Notification::class)->findByUser($user, ['limit' => 5]);

        $notificationList = $this->notificationManager->render($notifications);
        if (1 == $isLoad) {
            ViewJson::add('notification-list', implode('', $notificationList));
        } else {
            $template = $twig->render('Notification/notification-short.html.twig', [
        'notifications' => $notificationList,
      ]);
            ViewJson::add('template', $template);
            //return entire html templates
        }
    }

    private function maskAsReadHandler()
    {
        $user = $this->userManager->currentUser();
        if (!$user) {
            return;
        }
        $this->em->getRepository(Notification::class)->maskAsRead($user);
        ViewJson::addJs('html', [
      'obj' => '.notification-total',
      'value' => 0,
    ]);
        ViewJson::addJs('invoke', [
      'method' => 'removeClass',
      'obj' => '.notification-total',
      'value' => 'not-yes',
    ]);
    }

    private function fetchHandler()
    {
        $user = $this->userManager->currentUser();
        if (!$user) {
            return;
        }
        $options = $this->request->query->get('options');
        $notifications = $this->em->getRepository(Notification::class)->findByUser($user, $options);

        $notificationList = $this->notificationManager->render($notifications);
        ViewJson::add('notifications', $notificationList);
        if (count($notifications) < 20) {
            ViewJson::addJs('invoke', [
        'method' => 'remove',
        'obj' => '#notification_view .load-more-page',
        'value' => null,
      ]);
        }
    }
}
