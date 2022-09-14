<?php

use App\Databases\Database;
use App\Databases\Generators\Pdo;
use App\Models\Imdb;
use App\Models\Movie;
use App\Models\Series;

define("DIR", "");
date_default_timezone_set("Asia/Tehran");
require_once 'src/Configs/Static.php';

$action = $_SERVER['REQUEST_URI'];
$search = $_GET['search'];

$database = new Database();
$database->databaseConnection(new Pdo());
$imdb = new Imdb(new Series(), $search);
// $imdb->singlePageSchema();
// $imdb->findCompany();
// $imdb->findAwards();
// $imdb->findActors();
// $imdb->findEpisodes();
$imdb->getAllData();
print_f($imdb->getWatchable());

// notice : when creating a property as static , that property will be the same in your code for always;
