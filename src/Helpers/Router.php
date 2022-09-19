<?php

use App\Helpers\Tools;
use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();
$action = Tools::getUrl();

$router->get(BASE_URI . '', [CONTROLLER_NAMESPACE . '\CronJobController', 'checkMovies']);
$router->get(BASE_URI . "getFilmData", [CONTROLLER_NAMESPACE . '\ImdbController', 'addToDb']);

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response   = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $action);
