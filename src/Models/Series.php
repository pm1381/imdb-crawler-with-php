<?php

namespace App\Models;

class Series extends Watchable {
    private array $genre;
    private array $actors = [];
    private array $seasons = [];
    //watchable --> Movie --- Series --- document --- show

    /**
     * Get the value of genre
    */ 
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set the value of genre
     *
     * @return  self
    */ 
    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

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