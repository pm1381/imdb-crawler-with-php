<?php

namespace App\Models;

class Movie  extends Watchable{
    private array $actors = [];
    //watchable --> Movie --- Series --- document --- show

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