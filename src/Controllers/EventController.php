event<?php

namespace App\Controllers;

use App\Models\Event;
use App\Helpers\Input;

class EventController {

    public function addEventToDb()
    {
        $awardName = Input::get('awardName');
        $event = new Event($awardName);
        $event->setDatabaseTable();
        $event->getAwardData();
        $prizes = $event->getPrizes();
        foreach ($prizes as $year => $prize) {
            foreach ($prize as $title => $nomineesArray) {
                $winner = '';
                $awardTitle = $title; 
                $eachnomineesArray = [];
                foreach ($nomineesArray as  $nominee) {
                    if ($nominee['winner'] == true) {
                        $winner = $nominee['specialId'];
                    }
                    $eachnomineesArray[] = $nominee['specialId'];
                }
                $data = [
                    'eventTitle' => $event->getAwardTitle(),
                    'slug' => $event->getSlug(),
                    'specialId' => $event->getSpecialId(),
                    'year' => $year,
                    'winner' => $winner,
                    'prize' => $awardTitle,
                    'nominees' => $eachnomineesArray
                ];
                $event->manageSelectQuery(
                    ['specialId' => 1, 'eventTitle' => 1], [],
                    ['eventTitle' => $event->getAwardTitle(), 'year' => $year, 'prize' => $awardTitle]
                );
                if ($event->showSelectedDb()->getCount()) {
                    $event->showSelectedDb()
                        ->where(['eventTitle' => $event->getAwardTitle(), 'year' => $year, 'prize' => $awardTitle])
                        ->replace($data);
                } else {
                    $event->manageInsertQuery($data);
                }
            }
        }
    }
}