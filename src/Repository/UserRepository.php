<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this
          ->createQueryBuilder('u')
          ->where('u.username = :username OR u.email = :email')
          ->setParameter('username', $username)
          ->setParameter('email', $username)
          ->getQuery()
          ->getOneOrNullResult();
    }

    public function findUsersByData($data)
    {
        return $this
          ->createQueryBuilder('u')
          ->where('u.fullname LIKE :data')
          ->setParameter('data', '%'.$data.'%')
          ->getQuery()
          ->getResult();
    }

    public function findUsersBlock()
    {
        return $this
          ->createQueryBuilder('u')
          ->where('u.status = :status')
          ->setParameter('status', 0)
          ->getQuery()
          ->getResult();
    }
}
