<?php

namespace App\Controller;

use App\Entity\GroupUsers;
use App\Form\Svistyn\SvistynForm;
use App\Form\Svistyn\Model\SvistynModel;
use App\Form\GroupUsers\GroupUsersForm;
use App\Form\GroupUsers\GroupEditForm;
use App\Form\GroupUsers\Model\GroupEditModel;
use App\Services\GroupUsersService;
use App\Services\SvistService;
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
     * @var SvistService
     */
    private $svistService;

    /**
     * @var SvistynModel
     */
    private $svistynModel;

    /**
     * GroupUsersController constructor.
     *
     * @param GroupUsersService $groupUsersService
     * @param FlashBagInterface $flashBag
     * @param GroupEditModel    $groupEditModel
     * @param SvistService      $svistService
     * @param SvistynModel      $svistynModel
     */
    public function __construct(GroupUsersService $groupUsersService, FlashBagInterface $flashBag, GroupEditModel $groupEditModel, SvistService $svistService, SvistynModel $svistynModel)
    {
        $this->groupUsersService = $groupUsersService;
        $this->flashBag = $flashBag;
        $this->groupEditModel = $groupEditModel;
        $this->svistService = $svistService;
        $this->svistynModel = $svistynModel;
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
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showGroup($slug, Request $request)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);

        if (!$usersGroup) {
            return $this->redirectToRoute('user_groups_list');
        }

        $svistyns = $this->svistService->getSvistynsForGroup($usersGroup, $request);

        return $this->render('Group/show.html.twig', [
          'usersGroup' => $usersGroup,
          'user' => $this->getUser(),
          'svistyns' => $svistyns,
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
     *
     * @param $slug
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addRemoveFollower($slug, $id)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);
        if ('open' == $usersGroup->getConfidentiality()) {
            $this->groupUsersService->saveFollower($usersGroup, $id);
        } else {
            $this->flashBag->add('danger', 'group_close_or_private');
        }

        return $this->redirectToRoute('group_show', ['slug' => $slug]);
    }

    /**
     * @Route("/{slug}/svist/new", methods={"GET", "POST"}, name="group_svist_new")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param $slug
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addSvist($slug, Request $request)
    {
        $this->userCheck();
        $usersGroup = $this->getGroup($slug);

        $svistRepo = $this->svistService->svistynRepo();
        $svistyn = $svistRepo->findNew($this->getUser());

        $this->svistynModel->setSvistynEntity($svistyn);
        $form = $this->createForm(SvistynForm::class, $this->svistynModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->svistynModel->saveInGroup($usersGroup);
            $this->flashBag->add('success', 'added_new_svist_in_group_successfully');

            return $this->redirectToRoute('group_show', ['slug' => $slug]);
        }

        return $this->render('Svistyn/add.html.twig', [
          'form' => $form->createView(),
          'svistyn' => $svistyn,
        ]);
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
