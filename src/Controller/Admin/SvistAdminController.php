<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Svistyn;
use App\Form\Svistyn\Model\SvistynModel;
use App\Components\Utils\Form\EntityDeleteForm;
use App\Services\SvistService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SvistAdminController.
 *
 * @Route("/admin/svists")
 */
class SvistAdminController extends AbstractController
{
    /**
     * @var SvistService
     */
    private $svistService;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * SvistAdminController constructor.
     *
     * @param SvistService       $svistService
     * @param FlashBagInterface  $flashBag
     * @param PaginatorInterface $paginator
     */
    public function __construct(SvistService $svistService, FlashBagInterface $flashBag, PaginatorInterface $paginator)
    {
        $this->svistService = $svistService;
        $this->flashBag = $flashBag;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @Route("", methods={"GET"}, name="admin_svistyn_list")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function svistynList(Request $request)
    {
        $user = $this->getUser();
        $svistyns = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->getAllSvistyns(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/Svistyn/list.html.twig', [
          'svistyns' => $svistyns,
          'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/block", methods={"GET"}, name="admin_svist_block")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function svistBlock($id)
    {
        $this->svistService->block($id);

        return $this->redirectToRoute('admin_svistyn_list');
    }

    /**
     * @param Request $request
     * @Route("/block", methods={"GET"}, name="admin_svists_list_block")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function svistListBlock(Request $request)
    {
        $user = $this->getUser();

        $svistyns = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->findBlockSvistyns(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/Svistyn/list.html.twig', [
          'svistyns' => $svistyns,
          'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param SvistynModel           $svistynModel
     * @param EntityManagerInterface $entityManager
     * @Route("/{id}/delete", name="admin_svistyn_delete", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function delete($id, Request $request, SvistynModel $svistynModel, EntityManagerInterface $entityManager)
    {
        $svistyn = $this->svistService->getSvistyn($id);
        $svistynModel->setSvistynEntity($svistyn);

        $form = $this->createForm(EntityDeleteForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($svistyn);
            $entityManager->flush();
            $this->flashBag->add('danger', 'svist_was_deleted_successfully');

            return $this->redirectToRoute('admin_svistyn_list');
        }

        return $this->render('Admin/Svistyn/delete.html.twig', [
          'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     */
    public function getAdminCountBlockSvistyns()
    {
        $svistyns = $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->findBlockSvistyns();
        if (!$svistyns) {
            return new Response('0');
        } else {
            return new Response(count($svistyns));
        }
    }

    /**
     * @Route("/filter", methods={"GET"}, name="admin_user_filter_list")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function svistynsFilterList()
    {
        $user = $this->getUser();
        $svistyns = $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->getFilterSvistyns();

        return $this->render('Admin/Svistyn/filter/list.html.twig', [
          'svistyns' => $svistyns,
          'user' => $user,
        ]);
    }
}
