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

    /**
     * @Route("/user/{id_user}/friends/{id_friend}/add", name="friend_add", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function add($id_user, $id_friend)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        $this->friendsService->save($id_user, $id_friend);

        return $this->redirectToRoute('svistyn_post_user',
        [
          'id' => $id_friend,
        ]);
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
}
