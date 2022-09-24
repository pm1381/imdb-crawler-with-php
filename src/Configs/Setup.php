<?php

use App\Databases\Database;
use App\Databases\Generators\Mongo;
use App\Databases\Generators\Pdo;

define("DIR", "");
date_default_timezone_set("Asia/Tehran");
require_once 'src/Configs/Static.php';

$database = new Database();
$database->databaseConnection(new Mongo());
// $imdb = new Imdb(new Series(), $search);
// $imdb->getAllData();
// print_f($imdb->getWatchable());

// $cast = new Cast($search);
// $cast->getCastData();

// $event = new Event("emmys");
// $event->getAwardData();

// notice : when creating a property as static , that property will be the same in your code for always;
