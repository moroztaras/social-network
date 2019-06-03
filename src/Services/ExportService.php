<?php

namespace App\Services;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * ExportService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportUsers()
    {
        $spreadsheet = new Spreadsheet();

        $id = 1;
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A'.$id, 'Id');
        $sheet->setCellValue('B'.$id, 'FullName');
        $sheet->setCellValue('C'.$id, 'Email');
        $sheet->setCellValue('D'.$id, 'Birthday');
        $sheet->setCellValue('E'.$id, 'Gender');
        $sheet->setCellValue('F'.$id, 'Country');
        $sheet->setCellValue('G'.$id, 'Status');
        $sheet->setCellValue('H'.$id, 'Registration Date');

        while ($id <= count($this->doctrine->getManager()->getRepository(User::class)->findAll())) {
            $user = $this->doctrine->getManager()->getRepository(User::class)->find($id);
            ++$id;
            $sheet->setCellValue('A'.$id, $user->getId());
            $sheet->setCellValue('B'.$id, $user->getFullname());
            $sheet->setCellValue('C'.$id, $user->getEmail());
            $sheet->setCellValue('D'.$id, $user->getBirthday());
            $sheet->setCellValue('E'.$id, $user->getGender());
            $sheet->setCellValue('F'.$id, $user->getRegion());
            $sheet->setCellValue('G'.$id, $user->getStatus());
            $sheet->setCellValue('H'.$id, $user->getCreated());
        }
        $sheet->setTitle('Users');

        return $spreadsheet;
    }

    public function exportComments()
    {
        $spreadsheet = new Spreadsheet();

        $id = 1;
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A'.$id, 'SvistynId');
        $sheet->setCellValue('B'.$id, 'UserId');
        $sheet->setCellValue('C'.$id, 'Comment');
        $sheet->setCellValue('D'.$id, 'CreatedAt');
        $sheet->setCellValue('E'.$id, 'Approved');

        while ($id <= count($this->doctrine->getManager()->getRepository(Comment::class)->findAll())) {
            $comment = $this->doctrine->getManager()->getRepository(Comment::class)->find($id);
            ++$id;
            $svistyn = $comment->getSvistyn();
            $user = $comment->getUser();
            $sheet->setCellValue('A'.$id, $svistyn->getId());
            $sheet->setCellValue('B'.$id, $user->getId());
            $sheet->setCellValue('C'.$id, $comment->getComment());
            $sheet->setCellValue('D'.$id, $comment->getCreatedAt());
            $sheet->setCellValue('E'.$id, $comment->getApproved());
        }
        $sheet->setTitle('Comments');

        return $spreadsheet;
    }
}
