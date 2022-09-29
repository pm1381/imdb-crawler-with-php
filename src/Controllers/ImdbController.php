<?php

namespace App\Controllers;

use App\Models\Imdb;
use App\Helpers\Input;
use App\Models\Watchable;

class ImdbController {

    public function addFilmToDb()
    {
        $searched = Input::get('search');
        if (strpos($searched, CRAWLER_ON) !== false) {
            $imdb = new Imdb($searched);
            $imdb->setDatabaseTable();
            $imdb->getAllData();
            $watchableData = $imdb->getWatchable();
            print_f($watchableData, true);
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
            $imdb->manageSelectQuery(['url' => 1, 'specialId' => 1], [], ['specialId'  => $watchableData->getSpecialId()]);
            if ($imdb->showSelectedDb()->getCount()) {
                $imdb->showSelectedDb()->where(['specialId' => $watchableData->getSpecialId()])->replace($data);
            } else {
                $imdb->manageInsertQuery($data);
            }
            print_f("done successfully");
        } else {
            print_f("wrong input");
        }
    }
}