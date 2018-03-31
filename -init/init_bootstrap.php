<?php

//Enable Requirements
ini_set('short_open_tag', 1);
ini_set('magic_quotes_gpc', 1);
ini_set("memory_limit", "512M");
//Enable PHP-Errors
if (!isset($surpress_init_errors) || $surpress_init_errors) {
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

if (strstr(__FILE__, 'xtreme' . DIRECTORY_SEPARATOR . '-init' . DIRECTORY_SEPARATOR)) {
    $app_dir = str_replace('\\', '/', __FILE__);
    $app_dir = explode('xtreme/-init/', $app_dir);
    $app_dir = reset($app_dir);
} else {
    $app_dir = '../../';
}
define('APP_DIR', $app_dir);

include_once APP_DIR . 'xtreme/library/ensure_functions.php';

include_once APP_DIR . 'xtreme/classes/file.class.php';
include_once APP_DIR . 'xtreme/classes/method_options.class.php';
include_once APP_DIR . 'xtreme/classes/utilities.class.php';
