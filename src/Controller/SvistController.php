<?php

namespace App\Controller;

use App\Form\Svistyn\SvistynForm;
use App\Form\Svistyn\Model\SvistynModel;
use App\Components\Svistyn\SvistynApi;
use App\Components\Utils\Form\EntityDeleteForm;
use App\Components\Utils\Pagination;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Svistyn;
use App\Entity\User;
use App\Services\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class SvistController extends Controller
{
    /**
     * @var CommentService
     */
    public $commentService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * SvistController constructor.
     *
     * @param CommentService    $commentService
     * @param FlashBagInterface $flashBag
     */
    public function __construct(CommentService $commentService, FlashBagInterface $flashBag)
    {
        $this->commentService = $commentService;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/post", name="svistyn_post")
     */
    public function list(Request $request)
    {
        $repo = $this->svistynRepo();
        $user = $this->getUser();
        $router = $this->container->get('router');
        $pagination = new Pagination($router);
        $pagination->setRoute('svistyn_post');
        $pagination->setPage($request->query->get('page'));
        $options = [
          'page' => $pagination->getPage(),
          'marking' => 'active',
        ];
        $posts = $repo->advancedFinder($options, $user);
        $repo->advancedFinderPagination($options, $pagination, $user);

        return $this->render('Svistyn/list.html.twig', [
          'posts' => $posts,
          'user' => $user,
          'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("user/{id}/post", methods={"GET"}, name="svistyn_post_user", requirements={"id"="\d+"})
     */
    public function userPosts($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            $this->flashBag->add('danger', 'user_not_found');

            return $this->redirectToRoute('svistyn_post');
        }

        $repo = $this->svistynRepo();
        $router = $this->container->get('router');
        $pagination = new Pagination($router);
        $pagination->setRoute('svistyn_post');
        $pagination->setPage($request->query->get('page'));
        $options = [
            'page' => $pagination->getPage(),
            'marking' => 'active',
        ];
        $posts = $repo->advancedFinder($options, $user);
        $repo->advancedFinderPagination($options, $pagination, $user);

        return $this->render('Svistyn/list.html.twig', [
            'user' => $user,
            'this_user' => $this->getUser(),
            'posts' => $posts,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/post/{id}", methods={"GET"}, name="svistyn_post_view", requirements={"id"="\d+"})
     */
    public function view($id, Request $request)
    {
        $svistyn = $this->getDoctrine()->getRepository(Svistyn::class)->find($id);
        $comments = $this->commentService->getCommentsForSvistyn($svistyn);

        return $this->render('Svistyn/view.html.twig', [
          'svistyn' => $svistyn,
          'comments' => $comments,
        ]);
    }

    /**
     * @param Request      $request
     * @param SvistynModel $svistynModel
     * @Security("is_granted('ROLE_USER')")
     * @Route("/post/add", methods={"GET", "POST"}, name="svistyn_add")
     *
     * @return Response
     */
    public function add(Request $request, SvistynModel $svistynModel)
    {
        $svistRepo = $this->getDoctrine()->getRepository(Svistyn::class);
        $svistyn = $svistRepo->findNew($this->getUser());
        $svistynModel->setSvistynEntity($svistyn);
        $form = $this->createForm(SvistynForm::class, $svistynModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $svistynModel->save();
            $this->flashBag->add('success', 'added_new_svist_successfully');

            return $this->redirectToRoute('svistyn_post');
        }

        return $this->render('Svistyn/add.html.twig', [
          'form' => $form->createView(),
          'svistyn' => $svistyn,
        ]);
    }

    /**
     * @Route("/post/{id}/edit", name="svistyn_edit", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit($id, Request $request, SvistynModel $svistynModel)
    {
        /** @var User $user */
        $user = $this->getUser();
        $svistyn = $this->svistynRepo()->find($id);
        if ($user != $svistyn->getUser()) {
            $this->flashBag->add('danger', 'edit_svist_is_forbidden');

            return $this->redirectToRoute('svistyn_post');
        }
        $svistynModel->setSvistynEntity($svistyn);
        $form = $this->createForm(SvistynForm::class, $svistynModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $svistynModel->save();
            $this->flashBag->add('success', 'svist_edited_successfully');

            return $this->redirectToRoute('svistyn_post');
        }

        return $this->render('Svistyn/edit.html.twig', [
          'form' => $form->createView(),
          'svistyn' => $svistyn,
        ]);
    }

    /**
     * @Route("/post/{id}/delete", name="svistyn_delete", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete($id, Request $request, SvistynModel $svistynModel, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();
        $svistynRepo = $this->svistynRepo();
        $svistyn = $svistynRepo->find($id);

        if (!$svistyn || !$svistyn->getUser() || $svistyn->getUser()->getId() != $user->getId()) {
            $this->flashBag->add('danger', 'delete_svist_is_forbidden');

            return $this->redirectToRoute('svistyn_post');
        }
        $svistynModel->setSvistynEntity($svistyn);
        $form = $this->createForm(EntityDeleteForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            $svistynModel->save();
            $entityManager->remove($svistyn);
            $entityManager->flush();
            $this->flashBag->add('danger', 'svist_was_deleted_successfully');

            return $this->redirectToRoute('svistyn_post');
        }

        return $this->render('Svistyn/delete.html.twig', [
          'form' => $form->createView(),
    ]);
    }

    /**
     * @Route("/post/{id}/share/{state}", name="svistyn_share", requirements={"id"="\d+", "state" : "1|2"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function share($id, $state, Request $request, SvistynModel $svistynModel)
    {
        /** @var User $user */
        $user = $this->getUser();
        $svistynRepo = $this->svistynRepo();
        /** @var Svistyn $svistyn */
        $svistyn = $svistynRepo->find($id);
        if (!$svistyn || $svistyn->getUser()->getId() == $user->getId() || null != $svistyn->getParent()) {
            return $this->redirectToRoute('svistyn_post');
        }
        $newSvist = new Svistyn();
        $newSvist->setState($state)->setParent($svistyn);

        $svistynModel->setSvistynEntity($newSvist);

        $form = $this->createForm(SvistynForm::class, $svistynModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $svistynModel->save();
            $this->flashBag->add('success', 'svist_share_successfully');

            return $this->redirectToRoute('svistyn_post');
        }

        return $this->render('Svistyn/share.html.twig', [
          'form' => $form->createView(),
          'svistyn' => $newSvist,
        ]);
    }

    /**
     * @Route("/feed", methods={"GET"}, name="svistyn_feed_following")
     * @Security("is_granted('ROLE_USER')")
     */
    public function feed(Request $request, PaginatorInterface $paginator)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());

        $svistyns = $paginator->paginate(
          $this->getDoctrine()->getRepository(Svistyn::class)->findAllPostsOfFriends($user),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Svistyn/feed.html.twig', [
          'svistyns' => $svistyns,
        ]);
    }

    /**
     * @Route("/api/svistyn", name="svistyn_api")
     */
    public function api(SvistynApi $svistynApi)
    {
        return $svistynApi->handlerRequest();
    }

    protected function svistynRepo()
    {
        return  $this->getDoctrine()->getManager()->getRepository(Svistyn::class);
    }
}
