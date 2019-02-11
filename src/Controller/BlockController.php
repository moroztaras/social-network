<?php

namespace App\Controller;

use App\Entity\Svistyn;
use App\Entity\User;
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
}
