<?php

namespace App\Models;

use App\Databases\Database;

class Watchable extends Database
{
    private int $ratingCount;
    private float $rating;
    private array $awards = [];
    private array $country = [];
    private array $director = [];
    private array $language = [];
    private array $writer = [];
    private array $company = [];
    private array $pictures = [];//code
    private array $musicComposer = [];
    private array $producer = [];
    private string $title;
    private string $trailerUrl;
    private string $esrb;
    private string $releseDate;
    private string $url;
    private string $description;
    private string $duration;
    private string $poster;
    private string $budget;
    private WatchableType $type;


    /**
     * Get the value of releseDate
     */ 
    public function getReleseDate()
    {
        return $this->releseDate;
    }

    /**
     * Set the value of releseDate
     *
     * @return  self
     */ 
    public function setReleseDate($releseDate)
    {
        $this->releseDate = $releseDate;

        return $this;
    }

    /**
     * Get the value of trailerUrl
     */ 
    public function getTrailerUrl()
    {
        return $this->trailerUrl;
    }

    /**
     * Set the value of trailerUrl
     *
     * @return  self
     */ 
    public function setTrailerUrl($trailerUrl)
    {
        $this->trailerUrl = $trailerUrl;

        return $this;
    }

    /**
     * Get the value of awards
     */ 
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * Set the value of awards
     *
     * @return  self
     */ 
    public function setAwards($awards)
    {
        $this->awards = $awards;

        return $this;
    }

    /**
     * Get the value of country
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */ 
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of director
     */ 
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set the value of director
     *
     * @return  self
     */ 
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get the value of language
     */ 
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the value of language
     *
     * @return  self
     */ 
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get the value of rating
     */ 
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     *
     * @return  self
     */ 
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get the value of budget
     */ 
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set the value of budget
     *
     * @return  self
     */ 
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get the value of duration
     */ 
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     *
     * @return  self
     */ 
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of pictures
     */ 
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set the value of pictures
     *
     * @return  self
     */ 
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get the value of musicComposer
     */ 
    public function getMusicComposer()
    {
        return $this->musicComposer;
    }

    /**
     * Set the value of musicComposer
     *
     * @return  self
     */ 
    public function setMusicComposer($musicComposer)
    {
        $this->musicComposer = $musicComposer;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of url
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */ 
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of poster
     */ 
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set the value of poster
     *
     * @return  self
     */ 
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get the value of ratingCount
     */ 
    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    /**
     * Set the value of ratingCount
     *
     * @return  self
     */ 
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;

        return $this;
    }

    /**
     * Get the value of esrb
     */ 
    public function getEsrb()
    {
        return $this->esrb;
    }

    /**
     * Set the value of esrb
     *
     * @return  self
     */ 
    public function setEsrb($esrb)
    {
        $this->esrb = $esrb;
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
     * Get the value of company
     */ 
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @return  self
     */ 
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get the value of producer
     */ 
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * Set the value of producer
     *
     * @return  self
     */ 
    public function setProducer($producer)
    {
        $this->producer = $producer;
        return $this;
    }
}
