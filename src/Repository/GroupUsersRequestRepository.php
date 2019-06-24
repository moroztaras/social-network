<?php

namespace App\Repository;

use App\Entity\GroupUsers;
use App\Entity\GroupUsersRequest;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method GroupUsersRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupUsersRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupUsersRequest[]    findAll()
 * @method GroupUsersRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupUsersRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupUsersRequest::class);
    }

    public function getRequestUsersForGroup(GroupUsers $groupUsers)
    {
        return $this
          ->createQueryBuilder('gur')
          ->select('gur')
          ->addOrderBy('gur.id', 'DESC')
          ->andWhere('gur.statusRequest = :status')
          ->setParameter('status', 'send')
          ->leftJoin('gur.groupUsers', 'groupUsers')
          ->andWhere('groupUsers = :groupUsers')
          ->setParameter('groupUsers', $groupUsers)
          ->getQuery()
          ->getResult();
    }
}
