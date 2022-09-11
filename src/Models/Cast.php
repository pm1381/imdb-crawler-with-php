<?php

namespace App\Models;

use App\Helpers\Tools;

class Cast extends Person {
    private string $specialId;
    public function __construct($name, $url)
    {
        $url = Tools::uniteUrls($url);
        parent::__construct($name, $url);
        $this->setSpeciaId($url);
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
}