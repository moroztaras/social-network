<?php

namespace App\Services;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;

class HighChartsService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * HighChartsService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getYear()
    {
        $time = new \DateTime();

        return $time->format('Y');
    }

    public function getMonth()
    {
        $time = new \DateTime();
        $month = $time->format('n');
        $months = [];
        $i = 1;
        while ($i <= $month) {
            array_push($months, $i);
            ++$i;
        }

        return $months;
    }

    public function getMonths($initial_month, $final_month)
    {
        $months = [];
        if ($initial_month <= $final_month) {
            while ($initial_month <= $final_month) {
                array_push($months, $initial_month);
                ++$initial_month;
            }
        } else {
            while ($initial_month <= 12) {
                array_push($months, $initial_month);
                ++$initial_month;
            }
            $initial_month = 1;
            while ($initial_month <= $final_month) {
                array_push($months, $initial_month);
                ++$initial_month;
            }
        }

        return $months;
    }

    public function getUsers()
    {
        $users = [];
        $months = $this->getMonth();
        foreach ($months as $value) {
            array_push(
              $users,
              $this->doctrine->getRepository(User::class)->findUsersByMonth($value, $this->getYear()));
        }

        return $users;
    }

    public function getComments()
    {
        $comments = [];
        $months = $this->getMonth();
        foreach ($months as $value) {
            array_push(
              $comments,
              $this->doctrine->getRepository(Comment::class)->findCommentsByMonth($value, $this->getYear()));
        }

        return $comments;
    }

    public function getSvistyns()
    {
        $svistyns = [];
        $months = $this->getMonth();
        foreach ($months as $value) {
            array_push(
              $svistyns,
              count($this->doctrine->getRepository(Svistyn::class)->findSvistynsByMonth($value, $this->getYear())));
        }

        return $svistyns;
    }

    public function getVies()
    {
        $views = [];
        $months = $this->getMonth();
        foreach ($months as $value) {
            array_push(
              $views,
              $this->doctrine->getRepository(Svistyn::class)->getCountAllViewsSvistynsByMonth($value, $this->getYear()));
        }

        return $views;
    }

    public function getFilterUsers($filter_months, $initial_year)
    {
        $filter_users = [];
        foreach ($filter_months as $value) {
            array_push(
              $filter_users,
              $this->doctrine->getRepository(User::class)->findUsersByMonth($value, $initial_year));
            if (12 == $value) {
                ++$initial_year;
            }
        }

        return $filter_users;
    }

    public function getFilterComments($filter_months, $initial_year)
    {
        $filter_comments = [];
        foreach ($filter_months as $value) {
            array_push(
              $filter_comments,
              $this->doctrine->getRepository(Comment::class)->findCommentsByMonth($value, $initial_year));
            if (12 == $value) {
                ++$initial_year;
            }
        }

        return $filter_comments;
    }

    public function getFilterSvistyns($filter_months, $initial_year)
    {
        $filter_svistyns = [];
        foreach ($filter_months as $value) {
            array_push(
              $filter_svistyns,
              count($this->doctrine->getRepository(Svistyn::class)->findSvistynsByMonth($value, $initial_year))
            );
            if (12 == $value) {
                ++$initial_year;
            }
        }

        return $filter_svistyns;
    }

    public function getFilterViews($filter_months, $initial_year)
    {
        $filter_views = [];
        foreach ($filter_months as $value) {
            array_push(
              $filter_views,
              $this->doctrine->getRepository(Svistyn::class)->getCountAllViewsSvistynsByMonth($value, $initial_year)
            );
            if (12 == $value) {
                ++$initial_year;
            }
        }

        return $filter_views;
    }
}
