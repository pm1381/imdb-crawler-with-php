<?php

use App\Databases\Database;
use App\Databases\Generators\Pdo;
use App\Models\Imdb;

define("DIR", "");
date_default_timezone_set("Asia/Tehran");
require_once 'src/Configs/Static.php';

$action = $_SERVER['REQUEST_URI'];
$search = $_GET['search'];

$database = new Database();
$database->databaseConnection(new Pdo());
$imdb = new Imdb($search);
// $imdb->singlePageSchema();
// print_f($imdb->findTitle());
// print_f($imdb->findCountry());
// print_f($imdb->findLanguages());
print_f($imdb->findCompany());
// notice : when creating a property as static , that property will be the same in your code for always;
