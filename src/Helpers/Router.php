<?php

use App\Helpers\Tools;
use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();
$action = Tools::getUrl();

$router->get(BASE_URI . '', [CONTROLLER_NAMESPACE . '\HomeController', 'home']);
$router->get(BASE_URI . "getFilmData", [CONTROLLER_NAMESPACE . '\ImdbController', 'addFilmToDb']);
$router->get(BASE_URI . "getCastData", [CONTROLLER_NAMESPACE . '\CastController', 'addCastToDb']);
$router->get(BASE_URI . "getCompanyData", [CONTROLLER_NAMESPACE . '\CompanyController', 'addCompanyToDb']);

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response   = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $action);
