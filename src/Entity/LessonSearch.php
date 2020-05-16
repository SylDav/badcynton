<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class LessonSearch
{
    /**
     *  @var ArrayCollection
     */
    private $clubs;

    /**
     *  @var ArrayCollection
     */
    private $themes;


    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->themes = new ArrayCollection();
    }


    /**
     *  @return ArrayCollection
     */
    public function getClubs(): ArrayCollection
    {
        return $this->clubs;
    }

    /**
     *  @param ArrayCollection $options
     */
    public function setClubs(ArrayCollection $clubs): void
    {
        $this->clubs = $clubs;
    }

    /**
     *  @return ArrayCollection
     */
    public function getThemes(): ArrayCollection
    {
        return $this->themes;
    }

    /**
     *  @param ArrayCollection $options
     */
    public function setThemes(ArrayCollection $themes): void
    {
        $this->themes = $themes;
    }

}
