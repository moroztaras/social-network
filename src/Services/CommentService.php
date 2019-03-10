<?php

namespace App\Services;

use App\Entity\Svistyn;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ManagerRegistry;

class CommentService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * CommentService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function new($id, $user)
    {
        $comment = new Comment();
        $svistyn = $this->getSvistyn($id);
        $comment->setSvistyn($svistyn);
        $comment->setUser($user);

        return $comment;
    }

    public function save(Comment $comment)
    {
        $this->doctrine->getManager()->persist($comment);
        $this->doctrine->getManager()->flush();

        return $comment;
    }

    public function getCommentsForSvistyn(Svistyn $svistyn)
    {
        $comments = $this->doctrine->getManager()->getRepository('App:Comment')->getCommentsForSvistyn($svistyn->getId());

        return $comments;
    }
    public function remove(Comment $comment)
    {
        $this->doctrine->getManager()->remove($comment);
        $this->doctrine->getManager()->flush();

        return $comment;
    }

    private function getSvistyn($id)
    {
        $article = $this->doctrine->getManager()->getRepository('App:Svistyn')->find($id);

        return $article;
    }
}
