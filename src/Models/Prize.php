<?php

namespace App\Models;

class Prize{
    private string $prizeName;
    private string $winner;
    private array $nominees = [];

    /**
     * Get the value of nominees
     */ 
    public function getNominees()
    {
        return $this->nominees;
    }

    /**
     * Set the value of nominees
     *
     * @return  self
     */ 
    public function setNominees($nominees)
    {
        $this->nominees = $nominees;

        return $this;
    }

    /**
     * Get the value of winner
     */ 
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set the value of winner
     *
     * @return  self
     */ 
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get the value of prizeName
     */ 
    public function getPrizeName()
    {
        return $this->prizeName;
    }

    /**
     * Set the value of prizeName
     *
     * @return  self
     */ 
    public function setPrizeName($prizeName)
    {
        $this->prizeName = $prizeName;

        return $this;
    }
}