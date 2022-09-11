<?php

namespace App\Models;

use App\Helpers\Tools;
use APP\Models\Movie;

class Imdb{
    private Watchable $watchable;
    private string $searchedUrl;
    private $page;

    public function __construct(Watchable $watchable, $url = "")
    {
        $this->watchable = $watchable;
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
        $this->getWatchable()->setTrailerUrl($result['trailer']['embedUrl']);
        $this->getWatchable()->setDuration($result['duration']);

        $directors = [];
        foreach ($result['director'] as $eachDirector) {
            $directors[] = new Cast($eachDirector['name'], "");
        }
        $this->getWatchable()->setDirector($directors);

        $creators  = [];
        foreach ($result['creator'] as $creatorData) {
            if (array_key_exists('name', $creatorData)) {
                $creators[]  = new Cast($creatorData['name'], "");
            }
        }
        $this->getWatchable()->setWriter($creators);
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
        $result = Tools::getAllMatches('~<a class="ipc-metadata-list-item__list-content-item ipc-metadata-list-item__list-content-item--link" rel="" href="(.*)\?ref.*>(.*)<\/a>~iUs', $this->getPage());
        $data = [];
        $i = 0;
        foreach ($result[1] as $val) {
            $data[] = new Company(trim($val), trim($result[2][$i]));
            $i++;
        }
        $this->getWatchable()->setCompany($data);
        return $result;
    }

    public function findAwards($url = "")
    {
        $this->checkEmptyUrl($url);
        if ($url == "") {
            $newUrl = CRAWLER_ON . $this->getWatchable()->getUrl() . "awards/";
            $this->setPage(Tools::manageCUrl([], [], $newUrl));
        }
        $result = Tools::getAllMatches('~<td class="title_award_outcome" rowspan="\d">[\r,\n]*\s+<b>(.*)<\/b><br \/>[\r,\n]*\s+<span class="award_category">(.*)<\/span>[\r,\n]*\s+<\/td>[\r,\n]*\s+<td class="award_description">[\r,\n]+\s+([a-zA-Z].*)<br \/>~iUs', $this->getPage());
        $data = [];
        $i = 0;
        if (count($result) == 4) {
            foreach ($result[1] as $value) {
                $data[] = [
                    'status' => trim($value),
                    'award' => trim($result[2][$i]),
                    'for' => trim($result[3][$i]),
                ];
                $i++;
            }
        }
        $this->getWatchable()->setAwards($data);
        $this->setPageToDefault();
    }

    public function findPictures($url = "")
    {
    }

    public function findProducers($url = "")
    {
        $this->checkEmptyUrl($url);
        if ($url == "") {
            $newUrl = CRAWLER_ON . $this->getWatchable()->getUrl() . "fullcredits/";
            $this->setPage(Tools::manageCUrl([], [], $newUrl));
        }
        $producersTable = Tools::getAllMatches('~<h4[\r\n]*\s*name="producer" id="producer".*<\/h4>[\r\n]*\s*<table .*<\/table>~iUs', $this->getPage());
        $result = Tools::getAllMatches('~<tr>[\r\n]*\s*<td class="name">[\r\n]*\s*<a href="(.*)\?.*"[\r\n]*\s*>\s*([a-zA-Z].*)[\r\n]+\s*.*~iUs', $producersTable[0][0]);
        $data = [];
        $i = 0;
        foreach ($result[1] as $value) {
            $data[] = new Cast($result[2][$i], $value);
            $i++;
        }
        $this->getWatchable()->setProducer($data);
        $this->setPageToDefault();
    }

    public function setPageToDefault()
    {
        $this->setPage(Tools::manageCUrl([], [], $this->getSearchedUrl()));
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