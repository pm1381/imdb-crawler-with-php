<?php

namespace App\Models;

use App\Helpers\Tools;

class Cast extends Person {
    private string $specialId;
    private array $pictures = [];
    public function __construct($name, $url, $pictures = [])
    {
        $url = Tools::uniteUrls($url);
        parent::__construct($name, $url);
        $this->setSpeciaId($url);
        $this->setPictures($pictures);
    }

    /**
     * Get the value of speciaId
     */ 
    public function getSpeciaId()
    {
        return $this->specialId;
    }

    /**
     * Set the value of speciaId
     *
     * @return  self
     */ 
    public function setSpeciaId($specialId)
    {
        $urlArray = explode("/", $specialId);
        $this->specialId = explode("nm", $urlArray[2])[1];
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
}