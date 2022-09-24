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
            $imdb->setDatabaseTable();
            $imdb->getAllData();
            $watchableData = $imdb->getWatchable();
            //going to insert it in mongo db;
            $data = [
                'genre' => $watchableData->getGenre(),
                'ratingCount' => $watchableData->getRatingCount(),
                'rating' => $watchableData->getRating(),
                'title' => $watchableData->getTitle(),
                'trailerUrl' => $watchableData->getTrailerUrl(),
                'esrb' => $watchableData->getEsrb(),
                'releseDate' => $watchableData->getReleseDate(),
                'url' => $watchableData->getUrl(),
                'description' => $watchableData->getDescription(),
                'duration' => $watchableData->getDuration(),
                'poster' => $watchableData->getPoster(),
                'budget' => $watchableData->getBudget(),
                'type' => $watchableData->getType()->getValue(),
                'producer' => end($watchableData->getProducer()),
                'musicComposer' => end($watchableData->getMusicComposer()),
                'picture' => ['none'],
                'company' => end($watchableData->getCompany()),
                'writer' => end($watchableData->getWriter()),
                'language' => $watchableData->getLanguage(),
                'director' => end($watchableData->getDirector()),
                'country' => $watchableData->getCountry(),
                'specialId' => $watchableData->getSpecialId(),
                'actor' => end($watchableData->getActors())
            ];
            $imdb->manageInsertQuery($data);
        } else {
            print_f("wrong input");
        }
    }
}