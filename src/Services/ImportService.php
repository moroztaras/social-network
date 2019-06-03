<?php

namespace App\Services;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use App\Form\Comment\Model\CommentImportFileModel;
use Doctrine\Common\Persistence\ManagerRegistry;

class ImportService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * ImportService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param CommentImportFileModel $commentImportFileModel
     *
     * @return $this
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importComments(CommentImportFileModel $commentImportFileModel)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($commentImportFileModel->getCommentFile()->getPathName());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        for ($i = 2; $i <= count($rows); ++$i) {
            $comment = new Comment();
            foreach ($rows[$i] as $key => $value) {
                switch ($key) {
                    case 'A':
                        $svist = $this->doctrine->getManager()->getRepository(Svistyn::class)->find($value);
                        $comment->setSvistyn($svist);
                        break;
                    case 'B':
                        $user = $this->doctrine->getManager()->getRepository(User::class)->find($value);
                        $comment->setUser($user);
                        break;
                    case 'C':
                        $comment->setComment($value);
                        break;
                    case 'D':
                        $comment->setCreatedAt(new \DateTime($value));
                        break;
                    case 'E':
                        $comment->setApproved($value);
                        break;
                }
            }
            $this->doctrine->getManager()->persist($comment);
            $this->doctrine->getManager()->flush();
        }

        return $this;
    }
}
