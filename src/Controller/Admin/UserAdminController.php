<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends Controller
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * UserAdminController constructor.
     *
     * @param FlashBagInterface $flashBag
     */
    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/admin", methods={"GET"}, name="admin_user")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function userAdmin()
    {
        $user = $this->getUser();

        if (!$user) {
            $this->flashBag->add('danger', 'user_not_found');
        }

        return $this->render('Admin/User/dashboard.html.twig', [
          'user' => $user,
        ]);
    }

    public function getCountAllUsers()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return new Response(count($users));
    }

    public function getAdminCountAllSvistyns()
    {
        $svistyns = $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->findAll();
        if ($svistyns) {
            return new Response(count($svistyns));
        } else {
            return new Response('0');
        }
    }

    public function getAdminCountAllUsers()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return new Response(count($users));
    }

    public function getAdminCountAllComments()
    {
        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
        if ($comments) {
            return new Response(count($comments));
        } else {
            return new Response('0');
        }
    }
}
