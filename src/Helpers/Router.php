<?php

use App\Helpers\Tools;
use Phroute\Phroute\RouteCollector;

$route = new RouteCollector();
$action = Tools::getUrl();

$route->get('', ['CronJobController', 'insertToDb']);
