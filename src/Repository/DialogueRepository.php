<?php

namespace App\Repository;

use App\Entity\Dialogue;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dialogue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dialogue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dialogue[]    findAll()
 * @method Dialogue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DialogueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dialogue::class);
    }

    public function getDialoguesForUser(User $user)
    {
        return $this
          ->createQueryBuilder('dialogue')
          ->select('dialogue')
          ->where('dialogue.receiver = :user OR dialogue.creator = :user')
          ->addOrderBy('dialogue.updatedAt', 'DESC')
          ->setParameter('user', $user)
          ->getQuery()
          ->getResult();
    }
}
