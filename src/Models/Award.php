<?php

namespace App\Models;

use App\Helpers\Tools;

class Award {
    private string $awardTitle; // for example  oscars or golden globe
    private string $slug; // /event/ev00003/
    private string $specialId; // 00003
    private string $page;
    private int $year; // 2022
    private array $prizes = []; // array of prize
    private array $awardHistory = [];

    public function __construct($award)
    {
        $this->awardInitData($award);
    }

    public function getAwardData()
    {
        if ($this->getSpecialId() != null && $this->getSpecialId() != ""){
            $award = [];
            for ($i=0; $i < count($this->awardHistory) ; $i++) { 
                $current =  $this->awardHistory[$i];
                $url = DOMAIN . $this->getSlug() . $current . "/1/";
                $this->setPage(Tools::manageCUrl([], [], $url));
                $this->findAwardYear();
                $this->findAwardData($current, $award);
                if ($i > 2) {
                    break;
                }
            }
            $this->setPrizes($award);
        }
        print_f($this->getPrizes());
    }

    public function findAwardYear()
    {
        $result = Tools::getFirstMatch('~<div class="event-year-header__year">(.*) Awards</div>~iUs', $this->getPage());
        $this->setYear($result);
    }

    public function findAwardData($current, &$award)
    {
        $result = Tools::getAllMatches('~{"primaryNominees":\s*.*{\s*.*"name":"(.*)".*"const":"(.*)".*"categoryName":(.*),.*"isWinner":(.*)}~iUs', $this->getPage());
        $data = [];
        $i = 0;
        foreach ($result[2] as $value) {
            $specialId = Tools::getFirstMatch('~^.*(\d+)$~iUm', trim($value));
            $data[] = [
                'specialId' => $specialId,
                'name' => trim($result[1][$i]),
                'winner' => $result[4][$i]
            ];
            $i++;
        }
        $i = 0;
        foreach ($result[3] as $value) {
            if ($value != 'null') {
                $award[$current][$value][] = $data[$i];
            }
            $i++;
        }
    }

    private function awardInitData($award)
    {
        $nominationPage = Tools::manageCUrl([], [], DOMAIN . "/" . $award . "/nominations/");
        $pageresult = Tools::getFirstMatch('~<title>(.*)</title>~iUs', $nominationPage); 
        if (strpos($pageresult, "404") !== false) {
            $nominationPage = Tools::manageCUrl([], [], DOMAIN . "/awards-central/" . $award . "/");
            $pageresult = Tools::getAllMatches('~<title>(.*)</title>~iUs', $nominationPage); 
            if (empty($pageresult[1])) {
                return "wrong award name";
            }
        }
        $eventResult = Tools::getFirstMatch('~"eventHistoryWidgetModel":{"eventId":"(.*)"~iUs', $nominationPage);
        $this->setSlug("/event/" . $eventResult . "/");
        $this->setSpecialId($eventResult);
        $this->setAwardTitle($award);
        $this->getAllAwardYears($nominationPage);
    }

    private function getAllAwardYears($nominationPage)
    {
        $result = Tools::getAllMatches('~"eventEditionId":"[a-zA-Z0-9]+","year":(\d*),~iUs', $nominationPage);
        $this->awardHistory = $result[1];
    }

    /**
     * Get the value of awardTitle
    */ 
    public function getAwardTitle()
    {
        return $this->awardTitle;
    }

    /**
     * Set the value of awardTitle
     *
     * @return  self
    */ 
    public function setAwardTitle($awardTitle)
    {
        $this->awardTitle = $awardTitle;
        return $this;
    }

    /**
     * Get the value of slug
    */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
    */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
        $this->specialId = explode("ev", $specialId)[1];
        return $this;
    }

    /**
     * Get the value of year
    */ 
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set the value of year
     *
     * @return  self
    */ 
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Get the value of prizes
    */ 
    public function getPrizes()
    {
        return $this->prizes;
    }

    /**
     * Set the value of prizes
     *
     * @return  self
    */ 
    public function setPrizes($prizes)
    {
        $this->prizes = $prizes;
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
}