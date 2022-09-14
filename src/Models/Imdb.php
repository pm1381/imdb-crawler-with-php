<?php

namespace App\Models;

use App\Helpers\Tools;
use APP\Models\Movie;

class Imdb{
    private Watchable $watchable;
    private string $searchedUrl;
    private $page;

    public function __construct(Watchable $watchable, $url)
    {
        $this->watchable = $watchable;
        if ($url != "") {
            $this->setSearchedUrl($url);
            $this->setPage(Tools::manageCUrl([], [], $this->getSearchedUrl()));
            $watchableUrl = explode(DOMAIN, $url);
            $this->watchable->setUrl($watchableUrl[1]);
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
        $this->getWatchable()->setTitle($result['name']);
        $this->getWatchable()->setPoster($result['image']);
        $this->getWatchable()->setRating($result['aggregateRating']['ratingValue']);
        $this->getWatchable()->setRatingCount($result['aggregateRating']['ratingCount']);
        $this->getWatchable()->setReleseDate($result['datePublished']);
        $this->getWatchable()->setTrailerUrl($result['trailer']['embedUrl']);
        if (array_key_exists('duration', $result)) {
            $this->getWatchable()->setDuration($result['duration']);
        }
        if (array_key_exists('description', $result)) {
            $this->getWatchable()->setDescription($result['description']);
        }
        if (array_key_exists('contentRating', $result)) {
            $this->getWatchable()->setEsrb($result['contentRating']);
        }
        if (array_key_exists('director', $result)) {
            $directors = [];
            foreach ($result['director'] as $eachDirector) {
                $directors[] = new Cast($eachDirector['name'], $eachDirector['url']);
            }
            $this->getWatchable()->setDirector($directors);
        }
        if (array_key_exists('creator', $result)) {
            $creators  = [];
            foreach ($result['creator'] as $creatorData) {
                if (array_key_exists('name', $creatorData)) {
                    $creators[]  = new Cast($creatorData['name'], $creatorData['url']);
                }
            }
            $this->getWatchable()->setWriter($creators);
        }
        $genres = [];
        foreach ($result['genre'] as $value) {
            $genres[] = $value;
        }
        $this->getWatchable()->setGenre($genres);
    }

    public function singlePageSchema($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getFirstMatch('~<script type="application\/ld\+json">(.*)<\/script>~iUs', $this->getPage());        
        $result = json_decode($result, true);
        $this->setWatchableData($result);
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
        $result = Tools::getAllMatches('~<span class="ipc-metadata-list-item__label">Budget</span><div class="ipc-metadata-list-item__content-container"><ul class="ipc-inline-list ipc-inline-list--show-dividers ipc-inline-list--inline ipc-metadata-list-item__list-content base" role="presentation"><li role="presentation" class="ipc-inline-list__item"><span class="ipc-metadata-list-item__list-content-item">(.*)</span>~iUs', $this->getPage());
        if (count($result) == 2 && count($result[1]) > 0) {
            if (strpos($result[1], '(') !== false) {
                explode("(", $result[1]);
            }
            trim($result[1]);
            if ($result[1] != "" && $result[1] != null) {
                $this->getWatchable()->setBudget($result[1]);
            }
        }
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
        $this->checkNewPage("companycredits/", $url);
        $companiesList = Tools::getAllMatches('~<h4 class="dataHeaderWithBorder" id="production" name="production">Production Companies<\/h4>[\r\n]*\s*<ul .*<\/ul>~Us', $this->getPage());
        $result = Tools::getAllMatches('~<li>\s*<a href="(.*)\?.*"\s*>([A-Za-z0-9 ]*)<\/a>~iUs', $companiesList[0][0]);
        $data = [];
        $i = 0;
        foreach ($result[1] as $val) {
            $data[] = new Company(trim($val), trim($result[2][$i]));
            $i++;
        }
        $this->getWatchable()->setCompany($data);
        $this->setPageToDefault();
    }

    public function findMusicComposer($url = "")
    {
        $this->checkEmptyUrl($url);
        $this->checkNewPage("fullcredits/", $url);
        $musicTable = Tools::getAllMatches('~<h4\s*name="composer" id="composer"\s*class=".*">.*<\/h4>\s*<table.*<\/table>~iUs', $this->getPage());
        $result = Tools::getAllMatches('~<td class=".*">\s*<a href="(.*)\?.*"\s*>\s*([a-zA-Z].*)<\/a>~iUs', $musicTable[0][0]);
        $data = [];$i = 0;
        foreach ($result[2] as $value) {
            $data[] = new Cast(trim($value), trim($result[1][$i]));
            $i++;
        }
        $this->getWatchable()->setMusicComposer($data);
        $this->setPageToDefault();
    }

    public function findAwards($url = "")
    {
        $this->checkEmptyUrl($url);
        $this->checkNewPage("awards/", $url);
        $result = Tools::getAllMatches('~<td class="title_award_outcome" rowspan="\d">\s*<b>(.*)<\/b><br \/>\s*<span class="award_category">(.*)<\/span>~iUs', $this->getPage());
        $data = [];
        $i = 0;
        if (count($result) == 3) {
            foreach ($result[1] as $value) {
                $data[] = [
                    'status' => trim($value),
                    'award' => trim($result[2][$i])
                ];
                $i++;
            }
        }
        $this->getWatchable()->setAwards($data);
        $this->setPageToDefault();
    }

    public function findPictures($url = ""){}

    public function findProducers($url = "")
    {
        $this->checkEmptyUrl($url);
        $this->checkNewPage("fullcredits/", $url);
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

    public function findActors($url = "")
    {
        $this->checkEmptyUrl($url);
        $result = Tools::getAllMatches('~<div data-testid="title-cast-item" class="sc-36c36dd0-6 ewJBXI">.*<img.*src="(.*)" srcSet="(.*)".*<a data-testid="title-cast-item__actor" href="(.*)\?ref.*".*>(.*)<\/a>~iUs', $this->getPage()); 
        if (count($result) == 5) {
            $actors = [];
            $i = 0;
            foreach ($result[4] as $value) {
                $pictures = explode(", ", $result[2][$i]);
                $pictures['preferedPic'] = $result[1][$i];
                $actors[] = new Cast(trim($value), trim($result[3][$i]), $pictures);
                $i++;
            }
            $this->getWatchable()->setActors($actors);
        }
    }

    public function findEpisodes($url = "")
    {
        $this->checkEmptyUrl($url);
        $this->checkNewPage("episodes?season=1", $url);
        $seasonCount = $this->findNumberOfSeasons();
        $data = [];
        for ($i=1; $i <= $seasonCount; $i++) {
            $newUrl = CRAWLER_ON . $this->getWatchable()->getUrl() . "episodes?season=" . $i;
            $this->setPage(Tools::manageCUrl([], [], $newUrl));
            $eachSeasonResult = Tools::getAllMatches('~<div class="list_item.*class="info.*class="airdate">\s+(\d.*)\s+<\/div.*<a href="(.*)\?.*".*>(.*)<.*ipl-rating-star__rating.*>(.*)<.*ipl-rating-star__total-votes.*>.*([\d,]+)[\s.)].*item_description.*>\s+(.*)\s+<\/div>~iUs', $this->getPage());
            $j = 0;
            foreach ($eachSeasonResult[1]  as $value) {
                $data[$i][] = new Episode
                    (
                        $i, trim($eachSeasonResult[5][$j]), trim($eachSeasonResult[4][$j]), $eachSeasonResult[2][$j],
                        trim($eachSeasonResult[6][$j]), trim($eachSeasonResult[3][$j]), trim($value)
                    );
                $j++;
            }
        }
        $this->getWatchable()->setSeasons($data);
        $this->setPageToDefault();
    }

    public function findDirector($url = "")
    {
        $this->checkEmptyUrl($url);
        $this->checkNewPage("fullcredits/", $url);
        $directorTable = Tools::getAllMatches('~<h4\s*name="director" id="director"\s*.*<\/h4>\s*<table .*<\/table>~iUs', $this->getPage());
        $result = Tools::getAllMatches('~<tr>\s*<td class="name">\s*<a href="(.*)\?.*"\s*>\s*([a-zA-Z].*)[\r\n]+\s*.*~iUs', $directorTable[0][0]);
        $data = [];
        $i = 0;
        foreach ($result[1] as $value) {
            $data[] = new Cast($result[2][$i], $value);
            $i++;
        }
        $this->getWatchable()->setDirector($data);
        $this->setPageToDefault();
    }

    public function getAllData($url = "")
    {
        $this->singlePageSchema($url);
        $this->findCountry($url);
        $this->findBudget($url);
        $this->findLanguages($url);
        $this->findPictures($url);
        $this->findCompany($url);
        $this->findAwards($url);
        $this->findProducers($url);
        $this->findMusicComposer($url);
        $this->findActors($url);
        if ($this->getWatchable()->getType()->getValue() != "Movie") {
            $this->findEpisodes($url);
            $this->findDirector($url);
        }
    }

    private function setPageToDefault()
    {
        $this->setPage(Tools::manageCUrl([], [], $this->getSearchedUrl()));
    }

    private function findNumberOfSeasons()
    {
        $selectBox = Tools::getAllMatches('~<select id="bySeason".*<\/select>~iUs', $this->getPage());
        $result = Tools::getAllMatches('~<option.*value="(.)">~iUs', $selectBox[0][0]);
        return count($result[1]);
    }

    private function checkNewPage($param, $url)
    {
        if ($url == "") {
            $newUrl = CRAWLER_ON . $this->getWatchable()->getUrl() . $param;
            $this->setPage(Tools::manageCUrl([], [], $newUrl));
        }
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