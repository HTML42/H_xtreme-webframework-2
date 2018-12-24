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
define('SCRIPT_INCLUDES', PROJECT_ROOT . 'script_includes/');
if (strstr($_SERVER['SERVER_NAME'], 'localhost') || strstr($_SERVER['SERVER_NAME'], '192.')) {
    define('ENV', 'dev');
} else if (strstr($_SERVER['SERVER_NAME'], 'staging') || strstr($_SERVER['SERVER_NAME'], '.dev.html42') || strstr($_SERVER['SERVER_NAME'], '-dev.html42')) {
    define('ENV', 'test');
} else {
    define('ENV', 'live');
}
if (ENV != 'live') {
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
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
include CLASSES . 'curl.class.php';
include CLASSES . 'email.class.php';
//
include LIB . 'ensure_functions.php';

//Initiate RequestClass
Request::init();
define('BASEURL', "http" . (is_https() ? 's' : '') . "://" . $_SERVER['SERVER_NAME'] . '/' . Request::$url_path_to_script);

if(is_dir(SCRIPT_INCLUDES)) {
    foreach(Utilities::ls(SCRIPT_INCLUDES, true) as $script_filename) {
        if(File::_ext($script_filename) == 'php') {
            include SCRIPT_INCLUDES . $script_filename;
        }
    }
}