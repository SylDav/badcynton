<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class PaymentSearch
{

    private $club;

    /**
     *  @var boolean
     */
    private $paid;


    public function __construct()
    {
        $this->club;
        $this->paid;
    }


    public function getClub()
    {
        return $this->club;
    }

    public function setClub(?Club $club): void
    {
        $this->club = $club;
    }


    public function getPaid()
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): void
    {
        $this->paid = $paid;
    }

}
