<?php

namespace App\Controller;

use App\Entity\GroupUsers;
use App\Form\GroupUsers\GroupUsersForm;
use App\Services\GroupUsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController.
 *
 * @Route("/group")
 */
class GroupUsersController extends AbstractController
{
    /**
     * @var GroupUsersService
     */
    private $groupUsersService;

    public function __construct(GroupUsersService $groupUsersService)
    {
        $this->groupUsersService = $groupUsersService;
    }

    /**
     * @Route("s", name="user_groups_list")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listGroups()
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }

        return $this->render('Group/list.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="user_group_new")
     */
    public function newGroup(Request $request)
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
        $usersGroup = new GroupUsers();
        $groupForm = $this->createForm(GroupUsersForm::class, $usersGroup);

        $groupForm->handleRequest($request);
        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $this->groupUsersService->save($usersGroup, $user);

            return $this->redirectToRoute('user_groups_list');
        }

        return $this->render('Group/new.html.twig', [
          'user' => $user,
          'group_form' => $groupForm->createView(),
        ]);
    }
}
