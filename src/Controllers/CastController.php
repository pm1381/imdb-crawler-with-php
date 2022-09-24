<?php

namespace App\Controllers;

use App\Helpers\Input;
use App\Models\Cast;

class HomeController {

    public function addCastToDb()
    {
        $searched = Input::get('search');
        if (strpos($searched, CRAWLER_ON) !== false) {
            $cast = new Cast();
            $cast->setDatabaseTable('Cast');
            $cast->getCastData();
            $data = [
                'name' => $cast->getName(),
                'url'  => $cast->getUrl(),
                'specialId' => $cast->getSpeciaId(),
                'birthDate' => $cast->getBirthDate(),
                'description' => $cast->getDescription(),
                'picture' => $cast->getPictures()
            ];
            $cast->manageInsertQuery($data);
        } else {
            print_f("wrong input");
        }
    }
}