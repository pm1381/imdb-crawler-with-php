<?php

namespace App\Models;

use App\Helpers\Tools;

class Cast extends Person {
    private string $specialId;
    public function __construct($name, $url)
    {
        parent::__construct($name, Tools::uniteUrls($url));
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
        $this->specialId = $specialId;
        return $this;
    }
}