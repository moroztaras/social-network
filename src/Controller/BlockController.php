<?php

namespace App\Controller;

use App\Entity\Svistyn;
use App\Entity\User;
use App\Entity\Friends;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlockController extends Controller
{
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
}
