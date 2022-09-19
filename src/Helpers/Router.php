<?php

use App\Controllers\CronJobController;
use App\Helpers\Tools;
use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();
$action = Tools::getUrl();

$router->get(BASE_URI . '', [CONTROLLER_NAMESPACE . '\CronJobController', 'insertToDb']);
$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response   = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $action);
