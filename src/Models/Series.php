<?php

namespace App\Models;

class Series extends Watchable {
    private array $actors = [];
    private array $seasons = [];
    //watchable --> Movie --- Series --- Document --- Show

    /**
     * Get the value of seasons
    */ 
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Set the value of seasons
     *
     * @return  self
    */ 
    public function setSeasons($seasons)
    {
        $this->seasons = $seasons;
        return $this;
    }

    /**
     * Get the value of actors
    */ 
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Set the value of actors
     *
     * @return  self
    */ 
    public function setActors($actors)
    {
        $this->actors = $actors;
        return $this;
    }
}