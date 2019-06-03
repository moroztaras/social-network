<?php

namespace App\Controller;

use App\Form\Comment\CommentImportForm;
use App\Form\Comment\Model\CommentImportFileModel;
use App\Services\ImportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class ImportController.
 *
 * @Route("/admin")
 */
class ImportController extends Controller
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ImportService
     */
    private $importService;

    /**
     * ImportController constructor.
     *
     * @param ImportService     $importService
     * @param FlashBagInterface $flashBag
     */
    public function __construct(ImportService $importService, FlashBagInterface $flashBag)
    {
        $this->importService = $importService;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/comments/import/xlsx", methods={"GET","POST"}, name="admin_comments_import_xlsx")
     */
    public function importCommentsList(Request $request, CommentImportFileModel $commentImportFileModel)
    {
        $form = $this->createForm(CommentImportForm::class, $commentImportFileModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->importService->importComments($commentImportFileModel);
            $this->flashBag->add('success', 'file_xlsx_successfully_imported');

            return $this->redirectToRoute('admin_comments_list');
        }

        return $this->render('Admin/Comment/import.html.twig', [
          'form' => $form->createView(),
          'user' => $this->getUser(),
        ]);
    }
}
