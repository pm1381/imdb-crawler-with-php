<?php

namespace App\Models;

class Award {
    private string $awardTitle; // for example  oscars or golden globe
    private string $slug; // /event/ev00003/
    private string $specialId; //00003
    private int $year; // 2022
    private array $prizes = []; // array of prize

    /**
     * Get the value of awardTitle
     */ 
    public function getAwardTitle()
    {
        return $this->awardTitle;
    }

    /**
     * Set the value of awardTitle
     *
     * @return  self
     */ 
    public function setAwardTitle($awardTitle)
    {
        $this->awardTitle = $awardTitle;
        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get the value of specialId
     */ 
    public function getSpecialId()
    {
        return $this->specialId;
    }

    /**
     * Set the value of specialId
     *
     * @return  self
     */ 
    public function setSpecialId($specialId)
    {
        $this->specialId = $specialId;
        return $this;
    }

    /**
     * Get the value of year
     */ 
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set the value of year
     *
     * @return  self
     */ 
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Get the value of prizes
     */ 
    public function getPrizes()
    {
        return $this->prizes;
    }

    /**
     * Set the value of prizes
     *
     * @return  self
     */ 
    public function setPrizes($prizes)
    {
        $this->prizes = $prizes;
        return $this;
    }
}