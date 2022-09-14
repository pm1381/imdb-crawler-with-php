<?php

namespace App\Models;

use App\Helpers\Tools;

class Episode {
    private string $airDate;
    private string $title;
    private string $description;
    private string $url;
    private float $score;
    private int $ratingCount;
    private int $specialId;
    private int $season;

    public function __construct($season, $ratingCount, $score, $url, $description, $title, $airDate)
    {
        $url = Tools::uniteUrls($url);
        $this->setSeason($season);
        $this->setScore(floatval($score));
        $this->setDescription($description);
        $this->setTitle($title);
        $this->setAirDate($airDate);
        $this->setUrl($url);
        $this->setSpecialId($url);
        $this->setRatingCount($ratingCount);
    }
    
    /**
     * Get the value of season
    */ 
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set the value of season
     *
     * @return  self
    */ 
    public function setSeason($season)
    {
        $this->season = $season;
        return $this;
    }

    /**
     * Get the value of airDate
     */ 
    public function getAirDate()
    {
        return $this->airDate;
    }

    /**
     * Set the value of airDate
     *
     * @return  self
     */ 
    public function setAirDate($airDate)
    {
        $this->airDate = $airDate;

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
        $count = explode(",", $ratingCount);
        $num = implode("", $count);
        $this->ratingCount = intval($num);
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
     * Get the value of score
     */ 
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set the value of score
     *
     * @return  self
     */ 
    public function setScore($score)
    {
        $this->score = $score;

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
        $urlArray = explode("/", $specialId);
        $this->specialId = explode("tt", $urlArray[2])[1];
        return $this;
    }
}