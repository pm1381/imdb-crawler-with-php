<?php

namespace App\Models;

use App\Helpers\Tools;

class Cast extends Person {
    private string $specialId;
    private array $pictures = [];
    private string $page;

    public function __construct($url, $name = "",  $pictures = [])
    {
        $url = Tools::checkUrlType($url);
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

    public function findCastSchema()
    {
        $result = Tools::getFirstMatch('~<script type="application\/ld\+json">(.*)<\/script>~iUs', $this->getPage());
        $result = json_decode($result, true);
        $this->setSchemaData($result);
    }

    private function setSchemaData(array $result)
    {
        $this->setUrl($result['url']);
        $this->setName($result['name']);
        $this->setPictures([$result['image']]);
        if (array_key_exists('birthDate', $result)) {
            $this->setBirthDate($result['birthDate']);
        }
        if (array_key_exists('description', $result)) {
            $this->setDescription($result['description']);
        }
    }

    public function getCastData()
    {
        $this->setPage(Tools::manageCUrl([], [], DOMAIN . $this->getUrl()));
        $this->findCastSchema();
    }

    /**
     * Get the value of page
    */ 
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @return  self
    */ 
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }
}