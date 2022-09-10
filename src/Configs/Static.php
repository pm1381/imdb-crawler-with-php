<?php
define("DIRECTORY_SEPRATOR", "/");
define('ROOT', DIR);
define('SRC', ROOT . 'src' . DIRECTORY_SEPRATOR);
define('TEMPLATE', SRC . 'Views' . DIRECTORY_SEPRATOR);
define('ADMIN_TEMPLATE', TEMPLATE . 'admin' . DIRECTORY_SEPRATOR);
define('MODEL', SRC . 'Models' . DIRECTORY_SEPRATOR);
define('LIBRARY', SRC . 'Libs' . DIRECTORY_SEPRATOR);
define('CONFIG', SRC . 'Configs' . DIRECTORY_SEPRATOR);
define('CONTROLLER', SRC . 'Controllers' . DIRECTORY_SEPRATOR);
define('SITE_CONTROLLER', CONTROLLER . 'Site' . DIRECTORY_SEPRATOR);
define('ADMIN_CONTROLLER', CONTROLLER . 'Admin' . DIRECTORY_SEPRATOR);
define('REFRENCE_CONTROLLER', CONTROLLER . 'Refrence' . DIRECTORY_SEPRATOR);
// adding all require onces
require_once CONFIG    . 'Config.php';
require_once LIBRARY . 'Function.php';
require_once LIBRARY . 'simple_html_dom.php';
// and adding all files from library floder