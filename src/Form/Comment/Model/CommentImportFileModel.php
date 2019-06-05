<?php

namespace App\Form\Comment\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommentImportFileModel
{
    public $comment_import_file;

    /**
     * @return UploadedFile
     */
    public function getCommentFile()
    {
        return $this->comment_import_file;
    }

    /**
     * @param mixed $comment_import_file
     */
    public function setCommentFile($comment_import_file): void
    {
        $this->comment_import_file = $comment_import_file;
    }
}
