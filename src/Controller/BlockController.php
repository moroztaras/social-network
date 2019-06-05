<?php

namespace App\Controller;

use App\Entity\Svistyn;
use App\Entity\User;
use App\Entity\Friends;
use App\Entity\Dialogue;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * BlockController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function userCover($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            return new Response();
        }
        $count_svistyns = $this->getDoctrine()->getRepository(Svistyn::class)->counterSvistynsByUser($user);

        return $this->render('User/cover.html.twig', [
          'user' => $user,
          'count_svistyns' => $count_svistyns,
        ]);
    }

    public function userAdminCover()
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response();
        }

        return $this->render('Admin/User/cover.html.twig', [
          'user' => $user,
        ]);
    }

    public function userCountSvistyn($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            return new Response();
        }
        $count_svistyns = $this->getDoctrine()->getRepository(Svistyn::class)->counterSvistynsByUser($user);

        return $this->render('User/user_svistyn.html.twig', [
          'count_svistyns' => $count_svistyns,
        ]);
    }

    //number followers
    public function followers($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('Friends/followers.html.twig', [
          'user' => $user,
        ]);
    }

    //number following
    public function following($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $followings = $this->getDoctrine()->getRepository(Friends::class)->findBy(['user' => $user]);

        return $this->render('Friends/following.html.twig', [
          'followings' => $followings,
          'user' => $user,
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $users = null;
        $count_users = 0;

        if ($request->query->get('search')) {
            $users = $this->userService->getlistSerarchUsers($request);
            $count_users = count($this->getDoctrine()->getRepository(User::class)->findUsersByData($request->query->get('search')));
            unset($request);
        } elseif ($request->query->get('search_input')) {
            $users = $this->userService->getlistSerarchUsers($request);
            $count_users = count($this->getDoctrine()->getRepository(User::class)->findUsersByData($request->query->get('search_input')));
            unset($request);
        }

        return $this->render(
          'User/search.html.twig', [
          'users' => $users,
          'count_users' => $count_users,
        ]);
    }

    public function dialoguesList()
    {
        $user = $this->getUser();
        $dialogues = $this->getDoctrine()->getRepository(Dialogue::class)->getDialoguesForUser($user);

        return $this->render(
          'Dialogue/list.html.twig', [
          'user' => $user,
          'dialogues' => $dialogues,
        ]);
    }
}
