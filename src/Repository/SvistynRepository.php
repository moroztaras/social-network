<?php

namespace App\Repository;

use App\Components\Utils\Pagination;
use App\Entity\Friends;
use App\Entity\Svistyn;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class SvistynRepository extends EntityRepository
{
    public function queryByOptions(QueryBuilder $query, $options)
    {
        $limit = isset($options['limit']) ? $options['limit'] : 10;
        $page = isset($options['page']) && $options['page'] > 1 ? $options['page'] : 1;
        if (isset($options['marking'])) {
            $query->andWhere('sv.marking = :marking');
            $query->setParameter('marking', $options['marking']);
        }
        if ($page > 0) {
            $query->setFirstResult(($limit * $page) - $limit);
        }
        $query->setMaxResults($limit);

        return $query;
    }

    public function advancedFinder(array $options, User $user = null)
    {
        $query = $this->createQueryBuilder('sv');
        $query->select('sv');
        if ($user) {
            $query->andWhere('sv.user = :user');
            $query->setParameter('user', $user->getId());
        }
        $query->andWhere('sv.status = :status');
        $query->setParameter('status', 1);
        $this->queryByOptions($query, $options);
        $query->orderBy('sv.id', 'desc');
        $results = $query->getQuery()->execute();
        $ids = array_map(function ($o) { return $o->getId(); }, $results);
        $posts = $this->loadMultiple($ids);

        return $posts;
    }

    public function advancedFinderPagination(array $options, Pagination $pagination, User $user = null)
    {
        //Improvement, logic to find move easy check for next page
        $options['limit'] = 10;
        $options['page'] = $pagination->getPage() + 1;
        $query = $this->createQueryBuilder('sv');
        $query->select('sv');
        if ($user) {
            $query->andWhere('sv.user = :user');
            $query->setParameter('user', $user->getId());
        }
        $this->queryByOptions($query, $options);
        $query->orderBy('sv.id', 'desc');

        $results = $query->getQuery()->getResult();
        if ($results) {
            $next = $pagination->getPage() + 1;
            $pagination->setNext($next);
        }
    }

    public function findNew(User $user, $new = true)
    {
        $svist = $this->findOneBy(['user' => $user->getId(), 'marking' => 'new']);
        if (!$svist && $new) {
            $svist = new Svistyn();
            $svist->setUser($user);
            $this->_em->persist($svist);
            $this->_em->flush();
        }

        return $svist;
    }

    public function load($id)
    {
        $result = $this->loadMultiple([$id]);

        return reset($result);
    }

    public function loadMultiple(array $ids)
    {
        $results = $this->findBy(['id' => $ids], ['id' => 'DESC']);
        $posts = [];
        $postsAll = [];
        /** @var Svistyn $result */
        foreach ($results as $result) {
            $posts[$result->getId()] = $result;
            $postsAll[$result->getId()] = $result;
            if ($result->getParent()) {
                $parent = $result->getParent();
                $postsAll[$parent->getId()] = $parent;
            }
        }
        $this->shareLoad($postsAll);

        return $posts;
    }

    public function shareLoad($posts)
    {
        $ids = array_map(function ($o) { return $o->getId(); }, $posts);
        $qry = $this->createQueryBuilder('s')->select('COUNT(s.id) as ttl, s.state, sp.id as parent');
        $qry->leftJoin('s.parent', 'sp');
        $qry->andWhere('s.parent IN (:ids)')->groupBy('s.state', 's.parent');
        $qry->setParameter('ids', $ids);
        $results = $qry->getQuery()->getResult();

        foreach ($results as $result) {
            $state = $result['state'];
            $parent = $result['parent'];
            /** @var Svistyn $post */
            $post = $posts[$parent];
            switch ($state) {
                case 1:
                    $post->setCountSvists($result['ttl']);
                    break;
                case 2:
                    $post->setCountZvizds($result['ttl']);
                    break;
             }
        }
    }

    public function counterSvistynsByUser(User $user)
    {
        return $this
          ->createQueryBuilder('sv')
          ->andWhere('sv.status = :status')
          ->setParameter('status', 1)
          ->andWhere('sv.user = :user')
          ->setParameter('user', $user->getId())
          ->getQuery()
          ->getResult();
    }

    public function findAllPostsOfFriends(User $user)
    {
        return $this
          ->createQueryBuilder('sv')
          ->leftJoin('sv.user', 'user')
          ->leftJoin('user.friends', 'friends')
          ->where('friends.user = :user')
          ->setParameter('user', $user)
          ->getQuery()
          ->getResult();
    }

    public function getAllSvistyns()
    {
        return $this
          ->createQueryBuilder('sv')
          ->select('sv')
          ->addOrderBy('sv.id', 'DESC')
          ->getQuery()
          ->getResult();
    }

    public function findBlockSvistyns()
    {
        return $this
          ->createQueryBuilder('sv')
          ->select('sv')
          ->where('sv.status = :status')
          ->setParameter('status', 0)
          ->addOrderBy('sv.id', 'DESC')
          ->getQuery()
          ->getResult();
    }

    public function FindSvistynsByMonth($month, $year)
    {
        return $this
          ->createQueryBuilder('sv')
          ->select()
          ->andWhere('YEAR(sv.created) = :year')
          ->setParameter('year', $year)
          ->andWhere('MONTH(sv.created) = :month')
          ->setParameter('month', $month)
          ->getQuery()
          ->getResult();
    }

    public function getCountAllViewsSvistynsByMonth($month, $year)
    {
        $svistyns = $this
            ->createQueryBuilder('sv')
            ->select()
            ->andWhere('YEAR(sv.created) = :year')
            ->setParameter('year', $year)
            ->andWhere('MONTH(sv.created) = :month')
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult();
        $views = 0;
        foreach ($svistyns as $svistyn) {
            $views += $svistyn->getViews();
        }

        return $views;
    }

    public function getFilterSvistyns()
    {
        $svistyns = $this
          ->createQueryBuilder('sv')
          ->select()
          ->getQuery()
          ->getResult();

        $svists = [];
        foreach ($svistyns as $svistyn) {
            $user = $svistyn->getUser();
            if(count($user->getSvistyns())>=2)
            {
                array_push($svists, $svistyn);
            }
        }
    return $svists;
    }
}
