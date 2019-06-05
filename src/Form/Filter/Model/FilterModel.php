<?php

namespace App\Form\Filter\Model;

class FilterModel
{
    /**
     * @var int
     */
    public $initial_month;

    /**
     * @var int
     */
    public $final_month;

    /**
     * @var int
     */
    public $initial_year;

    /**
     * @var int
     */
    public $final_year;

    /**
     * @return int
     */
    public function getInitialMonth()
    {
        return $this->initial_month;
    }

    /**
     * @param $initial_month
     */
    public function setInitialMonth($initial_month): void
    {
        $this->initial_month = $initial_month;
    }

    /**
     * @return int
     */
    public function getInitialYear()
    {
        return $this->initial_year;
    }

    /**
     * @param $initial_year
     */
    public function setInitialYear($initial_year): void
    {
        $this->initial_year = $initial_year;
    }

    /**
     * @return int
     */
    public function getFinalMonth()
    {
        return $this->final_month;
    }

    public function setFinalMonth($final_month): void
    {
        $this->final_month = $final_month;
    }

    /**
     * @return int
     */
    public function getFinalYear()
    {
        return $this->final_year;
    }

    /**
     * @param $final_year
     */
    public function setFinalYear($final_year): void
    {
        $this->final_year = $final_year;
    }
}
