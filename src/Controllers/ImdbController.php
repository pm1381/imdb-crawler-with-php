<?php

namespace App\Controllers;

use App\Models\Imdb;
use App\Helpers\Input;
use App\Models\Watchable;

class ImdbController {

    public function addToDb()
    {
        $searched = Input::get('search');
        if (strpos($searched, CRAWLER_ON) !== false) {
            $imdb = new Imdb($searched);
            $imdb->getAllData();
            $watchableData = $imdb->getWatchable();
            print_f($watchableData, true);
            //going to insert it in mongo db;
        } else {
            print_f("wrong input");
        }
    }
}