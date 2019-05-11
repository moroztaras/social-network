<?php

namespace App\Services;

use App\Entity\Svistyn;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommentService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * CommentService constructor.
     *
     * @param ManagerRegistry   $doctrine
     * @param FlashBagInterface $flashBag
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
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

    public function findBlockComments()
    {
        $comments = $this->doctrine->getManager()->getRepository('App:Comment')->findBlockComments();

        return $comments;
    }

    public function block(Comment $comment)
    {
        if (true == $comment->getApproved()) {
            $comment->setApproved(false);
            $this->flashBag->add('danger', 'comment_is_blocked');
        } else {
            $comment->setApproved(true);
            $this->flashBag->add('success', 'comment_unblocked');
        }
        $this->save($comment);

        return $this;
    }

    public function remove(Comment $comment)
    {
        $this->doctrine->getManager()->remove($comment);
        $this->doctrine->getManager()->flush();

        return $comment;
    }

    private function getSvistyn($id)
    {
        $svistyn = $this->doctrine->getManager()->getRepository('App:Svistyn')->find($id);

        return $svistyn;
    }

    private function getComment($id)
    {
        $comment = $this->doctrine->getManager()->getRepository('Comment')->find($id);

        return $comment;
    }
}
