<?php

namespace App\Controller;

use App\Entity\Friends;
use App\Entity\User;
use App\Services\FriendsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FriendsController.
 */
class FriendsController extends Controller
{
    /**
     * @var FriendsService
     */
    private $friendsService;

    /**
     * FriendsController constructor.
     *
     * @param FriendsService $friendsService
     */
    public function __construct(FriendsService $friendsService)
    {
        $this->friendsService = $friendsService;
    }

    public function status($id_user, $id_friend)
    {
        $status = $this->friendsService->check($id_user, $id_friend);

        return $this->render('Friends/status.html.twig', [
          'status' => $status,
        ]);
    }

    /**
     * @Route("/user/{id}/followers", name="user_list_followers", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function userListFollowers($id)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('Friends/followers_list.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/following", name="user_list_following", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function userListFollowing($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $followers = $this->getDoctrine()->getRepository(Friends::class)->findBy(['user' => $user]);

        return $this->render('Friends/following_list.html.twig', [
          'followers' => $followers,
        ]);
    }

    public function applicationsInFriend()
    {
        $user = $this->getUser();
        $applications = $this->getDoctrine()->getRepository(Friends::class)->findBy(['friend' => $user, 'status' => 0]);

        return $this->render('Dialogue/ModeView/not_read_messages.html.twig', [
          'messages' => count($applications),
        ]);
    }

    /**
     * @Route("/user/applications", methods={"GET"}, name="user_list_applications")
     * @Security("is_granted('ROLE_USER')")
     */
    public function applications()
    {
        $friends = $this->getDoctrine()->getRepository(Friends::class)->findBy(['friend' => $this->getUser(), 'status' => 0]);

        return $this->render('Friends/applications_list.html.twig', [
          'friends' => $friends,
        ]);
    }

    /**
     * @Route("/user/friend/{id_friend}/status/{status}/add", methods={"GET"}, name="user_friend_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function addFriend($id_friend, $status)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        $this->friendsService->changeStatusFriendship($user, $id_friend, $status);

        if (0 == $status) {
            return $this->redirectToRoute('svistyn_post_user', ['id' => $id_friend]);
        }

        return $this->redirectToRoute('user_list_applications');
    }
}
