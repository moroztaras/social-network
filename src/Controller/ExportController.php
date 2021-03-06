<?php

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Services\ExportService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class ExportController.
 *
 * @Route("/admin")

 */
class ExportController extends AbstractController
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
}
