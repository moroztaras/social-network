<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getMessagesForDialogue($id)
    {
        return $this
          ->createQueryBuilder('m')
          ->select('m')
          ->where('m.dialogue = :id')
          ->addOrderBy('m.id', 'DESC')
          ->setParameter('id', $id)
          ->getQuery()
          ->getResult();
    }

    public function getCountNotReadMessagesInDialogue($id, User $user)
    {
        return $this
          ->createQueryBuilder('m')
          ->select('m')
          ->where('m.dialogue = :id')
          ->addOrderBy('m.id', 'DESC')
          ->setParameter('id', $id)
          ->andWhere('m.status = :status')
          ->setParameter('status', 0)
          ->leftJoin('m.receiver', 'receiver')
          ->andWhere('receiver = :user')
          ->setParameter('user', $user)
          ->getQuery()
          ->getResult();
    }
}
