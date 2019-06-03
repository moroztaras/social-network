<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Services\ExportService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class ExportController.
 *
 * @Route("/admin")

 */
class ExportController extends Controller
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * ExportController constructor.
     *
     * @param FlashBagInterface $flashBag
     */
    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/users/export/xlsx", name="admin_users_export_xlsx")
     *
     * @param ExportService $exportService
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function adminUsersExportXlsx(ExportService $exportService)
    {
        $spreadsheet = $exportService->exportUsers();

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'users_social_network.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/users/export/csv", name="admin_users_export_csv")
     *
     * @param ExportService $exportService
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function adminUsersExportCsv(ExportService $exportService)
    {
        $spreadsheet = $exportService->exportUsers();

        // Create your Office 2007 Excel (CSV Format)
        $writer = new Csv($spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);

        $writer->save('user_social_network.csv');

        // Create a Temporary file in the system
        $fileName = 'users_social_network.csv';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the csv file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the csv file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/comments/export/xlsx", name="admin_comments_export_xlsx")
     *
     * @param ExportService $exportService
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function adminCommentsExportXlsx(ExportService $exportService)
    {
        $spreadsheet = $exportService->exportComments();

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'comments_social_network.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/comments/export/csv", name="admin_comments_export_csv")
     *
     * @param ExportService $exportService
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function adminCommentsExportCsv(ExportService $exportService)
    {
        $spreadsheet = $exportService->exportComments();

        // Create your Office 2007 Excel (CSV Format)
        $writer = new Csv($spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);

        $writer->save('user_social_network.csv');

        // Create a Temporary file in the system
        $fileName = 'comments_social_network.csv';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the csv file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the csv file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/comments/import/xlsx", methods={"GET"}, name="admin_comments_import_xlsx")
     */
    public function importCommentsList(EntityManagerInterface $entityManager)
    {
//        $form = $this->createForm(CommentImportForm::class, $commentModel);
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load('comments_social_network.xlsx');
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $i = 2;
        for ($i = 2; $i <= count($rows); ++$i) {
            $comment = [];
            foreach ($rows[$i] as $row) {
                array_push($comment, $row);
            }
            $commentRow = new Comment();
            for ($j = 0; $j <= (count($rows[$i]) - 1); ++$j) {
                switch ($j) {
                   case 0: $svist = $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->find($comment[0]);
                           $commentRow->setSvistyn($svist);
                   break;
                   case 1: $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($comment[1]);
                           $commentRow->setUser($user);
                   break;
                   case 2: $commentRow->setComment($comment[2]);
                   break;
                   case 3:$commentRow->setCreatedAt(new \DateTime($comment[3]));
                   break;
                   case 4: $commentRow->setApproved($comment[4]);
                   break;
                }
            }
            $entityManager->persist($commentRow);
            $entityManager->flush($commentRow);
            unset($commentRow);
        }
        $this->flashBag->add('success', 'file_xlsx_successfully_imported');

        return $this->redirectToRoute('admin_comments_list');
    }
}
