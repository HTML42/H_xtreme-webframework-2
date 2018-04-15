<?php

//Variables / Constants
$thisFile = __FILE__;
$thisFile = str_replace('\\', '/', $thisFile);
$path = str_replace('library/' . basename(__FILE__), '', $thisFile);
define('ROOT', $path);
define('LIB', ROOT . 'library/');
define('CLASSES', ROOT . 'classes/');
define('CACHE', ROOT . '../cache/');
define('PROJECT_ROOT', str_replace('xtreme/', '', ROOT));
if (strstr($_SERVER['SERVER_NAME'], 'localhost') || strstr($_SERVER['SERVER_NAME'], '192.')) {
    define('ENV', 'dev');
} else if (strstr($_SERVER['SERVER_NAME'], 'staging')) {
    define('ENV', 'test');
} else {
    define('ENV', 'live');
}
//
define('DAY', 3600 * 24);


//Includes
include CLASSES . 'app.class.php';
include CLASSES . 'file.class.php';
include CLASSES . 'method_options.class.php';
include CLASSES . 'utilities.class.php';
include CLASSES . 'request.class.php';
include CLASSES . 'response.class.php';
include CLASSES . 'html.class.php';
//
include LIB . 'ensure_functions.php';

//Initiate RequestClass
Request::init();
define('BASEURL', "http" . (is_https() ? 's' : '') . "://" . $_SERVER['SERVER_NAME'] . '/' . Request::$url_path_to_script);
