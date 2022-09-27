<?php

namespace App\Controllers;

use App\Helpers\Input;
use App\Models\Cast;

class CastController {

    public function addCastToDb()
    {
        $searched = Input::get('search');
        if (strpos($searched, CRAWLER_ON) !== false) {
            $cast = new Cast($searched);
            $cast->setDatabaseTable('Cast');
            $cast->getCastData();
            $cast->manageSelectQuery(['name', 'specialId'],[], ['specialId' => $cast->getSpeciaId()]);
            $data = [
                'name' => $cast->getName(),
                'url'  => $cast->getUrl(),
                'specialId' => $cast->getSpeciaId(),
                'birthDate' => $cast->getBirthDate(),
                'description' => $cast->getDescription(),
                'picture' => $cast->getPictures()
            ];
            if ($cast->showSelectedDb()->getCount()) {
                $cast->showSelectedDb()->where(['specialId' => $cast->getSpeciaId()])->replace($data);
            } else {
                $cast->manageInsertQuery($data);
            }
            print_f("done successfully");
        } else {
            print_f("wrong input");
        }
    }
}