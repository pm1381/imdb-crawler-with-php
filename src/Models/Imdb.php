<?php

namespace App\Models;

use App\Helpers\Tools;
use APP\Models\Movie;

class Imdb{
    private Watchable $watchable;
    private string $searchedUrl;
    private $page;

    public function __construct($url = "")
    {
        $this->watchable = new Watchable();
        if ($url != "") {
            $this->setSearchedUrl($url);
            $this->setPage(Tools::manageCUrl([], [], $this->getSearchedUrl()));
            $this->watchable->setUrl($url);
        }      
    }

    /**
     * Get the value of searchedUrl
    */ 
    public function getSearchedUrl()
    {
        return $this->searchedUrl;
    }

    /**
     * Set the value of searchedUrl
     *
     * @return  self
    */ 
    public function setSearchedUrl($searchedUrl)
    {
        $this->searchedUrl = $searchedUrl;

        return $this;
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

    /**
     * Get the value of watchable
     */ 
    public function getWatchable()
    {
        return $this->watchable;
    }

    private function setWatchableData(array $result)
    {
        $this->getWatchable()->setType(new WatchableType($result['@type']));
        $this->getWatchable()->setUrl($result['url']);
        $this->getWatchable()->setPoster($result['image']);
        $this->getWatchable()->setDescription($result['description']);
        $this->getWatchable()->setRating($result['aggregateRating']['ratingValue']);
        $this->getWatchable()->setRatingCount($result['aggregateRating']['ratingCount']);
        $this->getWatchable()->setEsrb($result['contentRating']);
        $this->getWatchable()->setReleseDate($result['datePublished']);
        $this->getWatchable()->setTrailerUrl($result['embedUrl']);
        $this->getWatchable()->setDirector($result['director']['name']);
        $this->getWatchable()->setDuration($result['duration']);

        $creators  = [];
        foreach ($result['creator'] as $creatorData) {
            if (array_key_exists('name', $creatorData)) {
                $creators[]  = $creatorData['name'];
            }
        }
        $this->getWatchable()->setCreator($creators);
    }

    public function singlePageSchema($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getFirstMatch('~<script type="application\/ld\+json">(.*)<\/script>~iUs', $this->getPage());        
        $result = json_decode($result, true);
        $this->setWatchableData($result);
    }

    public function findTitle($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getFirstMatch('~<h1 textlength="\d+" data-testid="hero-title-block__title" class=".*">(.*)<\/h1>~iUs', $this->getPage());
        $this->getWatchable()->setTitle($result);
        return $result;
    }

    public function findCountry($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getAllMatches('~<a class="ipc-metadata-list-item__list-content-item ipc-metadata-list-item__list-content-item--link" rel="" href="/search/title/\?country_of_origin=.*">(.*)</a>~iUs', $this->getPage());
        $this->getWatchable()->setCountry($result[1]);
        return $result;
    }

    public function findBudget($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getFirstMatch('~<span class="ipc-metadata-list-item__label">Budget</span><div class="ipc-metadata-list-item__content-container"><ul class="ipc-inline-list ipc-inline-list--show-dividers ipc-inline-list--inline ipc-metadata-list-item__list-content base" role="presentation"><li role="presentation" class="ipc-inline-list__item"><span class="ipc-metadata-list-item__list-content-item">(.*)</span>~iUs', $this->getPage());
        if (strpos($result, '(') !== false) {
            explode("(", $result);
        }
        trim($result);
        $this->getWatchable()->setBudget($result);
        return $result;
    }

    public function findLanguages($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getAllMatches('~<a class="ipc-metadata-list-item__list-content-item ipc-metadata-list-item__list-content-item--link" rel="" href="/search/title\?title_type=feature&amp;primary_language=.*>(.*)</a>~iUs', $this->getPage());
        $this->getWatchable()->setLanguage($result[1]);
        return $result;
    }

    public function findCompany($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getAllMatches('~<a class="ipc-metadata-list-item__list-content-item ipc-metadata-list-item__list-content-item--link" rel="" href="/company/.*>(.*)</a>~iUs', $this->getPage());
        $data = [];
        $i = 0;
        foreach ($result[0] as $val) {
            // val must be changed to url(slug);
            $data[] = new Company($val, $result[1][$i]);
            $i++;
        }
        $this->getWatchable()->setCompany($data);
        return $result;
    }

    public function findPictures($url = "")
    {
        $this->checkEmptyUrl($url);
    }

    private function checkEmptyUrl($url)
    {
        if ($url != "") {
            $this->setSearchedUrl($url);
            $this->setPage(Tools::manageCUrl([], [], $url));
            $this->watchable->setUrl($url);
        }
    }
}