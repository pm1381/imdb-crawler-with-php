<?php

namespace App\Controllers;

use App\Models\Imdb;
use App\Helpers\Input;

class ImdbController {

    public function addToDb()
    {
        $searched = Input::get('search');
        if (strpos($searched, CRAWLER_ON) !== false) {
            // $imdb = new Imdb(new Series(), $searched);
            // $imdb->getAllData();
            // $watchableData = $imdb->getWatchable();
            //going to insert it in mongo db;
        } else {
            print_f("wrong input");
        }
    }
}