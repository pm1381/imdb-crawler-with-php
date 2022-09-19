<?php

namespace App\Controllers;

use App\Helpers\Input;

class CronJobController {

    public function insertToDb()
    {
        echo Input::get('search');
    }
}