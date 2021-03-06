<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsForSvistyn($id, $approved = true)
    {
        $query = $this->createQueryBuilder('c')
          ->select('c')
          ->where('c.svistyn = :id')
          ->addOrderBy('c.id', 'DESC')
          ->setParameter('id', $id);
        if (false === is_null($approved)) {
            $query->andWhere('c.approved = :approved')
              ->setParameter('approved', $approved);
        }

        return $query->getQuery()->getResult();
    }

    public function getAllComments()
    {
        return $this
          ->createQueryBuilder('c')
          ->select('c')
          ->addOrderBy('c.id', 'DESC')
          ->getQuery()
          ->getResult();
    }

    public function findBlockComments()
    {
        return $this
          ->createQueryBuilder('c')
          ->select('c')
          ->where('c.approved = :approved')
          ->setParameter('approved', false)
          ->addOrderBy('c.id', 'DESC')
          ->getQuery()
          ->getResult();
    }

    public function FindCommentsByMonth($month, $year)
    {
        return count($this
          ->createQueryBuilder('c')
          ->select()
          ->andWhere('MONTH(c.createdAt) = :month')
          ->setParameter('month', $month)
          ->getQuery()
          ->getResult()
        );
    }
}
