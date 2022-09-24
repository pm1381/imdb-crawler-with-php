<?php

namespace App\Models;

use App\Databases\Database;
use App\Helpers\Tools;

class Company extends Database {
    private string $name;
    private string $url;
    private string $specialId;

    public function __construct($url, $name="")
    {
        $url = Tools::uniteUrls($url);
        $this->setUrl($url);
        $this->setName($name);
        $this->setSpecialId($url);
    }

    public function setDatabaseTable()
    {
        $this->showSelectedDb()->setTable('Company');
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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;
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
        $this->specialId = explode("co", $urlArray[2])[1];
        return $this;
    }
}