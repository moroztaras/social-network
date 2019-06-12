<?php

namespace App\Controller;

use App\Entity\GroupUsers;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\GroupUsers\GroupUsersForm;
use App\Form\GroupUsers\GroupEditForm;
use App\Form\GroupUsers\Model\GroupEditModel;
use App\Services\GroupUsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var GroupEditModel
     */
    private $groupEditModel;

    /**
     * GroupUsersController constructor.
     *
     * @param GroupUsersService $groupUsersService
     * @param FlashBagInterface $flashBag
     * @param GroupEditModel    $groupEditModel
     */
    public function __construct(GroupUsersService $groupUsersService, FlashBagInterface $flashBag, GroupEditModel $groupEditModel)
    {
        $this->groupUsersService = $groupUsersService;
        $this->flashBag = $flashBag;
        $this->groupEditModel = $groupEditModel;
    }

    /**
     * @Route("s", methods={"GET"}, name="user_groups_list")
     * @Security("is_granted('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listGroups()
    {
        $this->userCheck();
        $groups = $this->getDoctrine()->getRepository(GroupUsers::class)->findAll();

        return $this->render('Group/list.html.twig', [
          'user' => $this->getUser(),
          'groups' => $groups,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="user_group_new")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newGroup(Request $request)
    {
        $this->userCheck();
        $usersGroup = new GroupUsers();
        $groupForm = $this->createForm(GroupUsersForm::class, $usersGroup);

        $groupForm->handleRequest($request);
        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $this->groupUsersService->save($usersGroup, $this->getUser());

            return $this->redirectToRoute('user_groups_list');
        }

        return $this->render('Group/new.html.twig', [
          'user' => $this->getUser(),
          'group_form' => $groupForm->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="group_show")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showGroup($slug)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);

        if (!$usersGroup) {
            return $this->redirectToRoute('user_groups_list');
        }

        return $this->render('Group/show.html.twig', [
          'usersGroup' => $usersGroup,
          'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{slug}/dashboard", methods={"GET"}, name="group_dashboard")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function dashboardGroup($slug)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);
        if ($usersGroup->getAdmin() != $this->getUser()) {
            $this->flashBag->add('danger', 'group_editing_is_not_allowed');

            return $this->redirectToRoute('group_show', ['slug' => $slug]);
        }
        if (!$usersGroup) {
            return $this->redirectToRoute('user_groups_list');
        }

        return $this->render('Group/dashboard.html.twig', [
          'usersGroup' => $usersGroup,
        ]);
    }

    /**
     * @Route("/{slug}/edit", methods={"GET", "POST"}, name="group_edit")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param $slug
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editGroup($slug, Request $request)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);
        if ($usersGroup->getAdmin() != $this->getUser()) {
            $this->flashBag->add('danger', 'group_editing_is_not_allowed');

            return $this->redirectToRoute('group_show', ['slug' => $slug]);
        }
        if (!$usersGroup) {
            return $this->redirectToRoute('user_groups_list');
        }
        $this->groupEditModel->setGropUsers($usersGroup);
        $form = $this->createForm(GroupEditForm::class, $this->groupEditModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupEditModel->save($usersGroup);
            $this->flashBag->add('success', 'group_edit_save');

            return $this->redirectToRoute('group_dashboard', ['slug' => $slug]);
        }

        return $this->render('Group/edit.html.twig', [
          'usersGroup' => $usersGroup,
          'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/followers", methods={"GET", "POST"}, name="group_list_followers")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function followersListGroup($slug)
    {
        $this->userCheck();

        return $this->render('Group/followers_list.html.twig', [
          'usersGroup' => $this->getGroup($slug),
        ]);
    }

    /**
     * @Route("/{slug}/follower/{id}/add", methods={"GET", "POST"}, name="group_add_follower", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function addRemoveFollower($slug, $id, EntityManagerInterface $entityManager)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if ('open' == $usersGroup->getConfidentiality()) {
            $usersGroup->addUser($user);
            $entityManager->persist($usersGroup);
            $entityManager->flush();
            $this->flashBag->add('success', 'you_joined_the_group');
        }

        return $this->redirectToRoute('group_show', ['slug' => $slug]);
    }

    private function getGroup($slug)
    {
        return $this->getDoctrine()->getRepository(GroupUsers::class)->findOneBy(['slug' => $slug]);
    }

    private function userCheck()
    {
        $user = $this->getUser();
        if (null != $user && 0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        }
    }
}
