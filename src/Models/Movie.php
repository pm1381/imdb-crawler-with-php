<?php

namespace App\Models;

class Movie  extends Watchable{
    private string $genre;
    private array $casts = [];
    private string $writer;
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
     * Get the value of writer
     */ 
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Set the value of writer
     *
     * @return  self
     */ 
    public function setWriter($writer)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Get the value of casts
     */ 
    public function getCasts()
    {
        return $this->casts;
    }

    /**
     * Set the value of casts
     *
     * @return  self
     */ 
    public function setCasts($casts)
    {
        $this->casts = $casts;

        return $this;
    }
}