<?php

if (strstr(__FILE__, 'xtreme' . DIRECTORY_SEPARATOR . '-init' . DIRECTORY_SEPARATOR)) {
    $app_dir = str_replace('\\', '/', __FILE__);
    $app_dir = explode('xtreme/-init/', $app_dir);
    $app_dir = reset($app_dir);
} else {
    $app_dir = '../../';
}
define('APP_DIR', $app_dir);

include_once APP_DIR . 'xtreme/classes/file.class.php';
include_once APP_DIR . 'xtreme/classes/method_options.class.php';

function debug($input) {
    echo '<pre style="background-color: rgba(0, 0, 0, 0.2);padding: 10px 20px;">';
    var_dump($input);
    echo '</pre>';
}
