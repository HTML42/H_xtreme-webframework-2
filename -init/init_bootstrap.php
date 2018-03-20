<?php

if (strstr(__FILE__, 'xtreme' . DIRECTORY_SEPARATOR . '-init' . DIRECTORY_SEPARATOR)) {
    $app_dir = str_replace('\\', '/', __FILE__);
    $app_dir = explode('xtreme/-init/', $app_dir);
    $app_dir = reset($app_dir);
} else {
    $app_dir = '../../';
}
define('APP_DIR', $app_dir);

//Init-Functions
function cp_r($target, $destination) {
    if (is_dir($destination) && substr($destination, -1) != '/') {
        $destination .= '/';
    }
    if (is_dir($target)) {
        if (substr($target, -1) != '/') {
            $target .= '/';
        }
        if (substr($destination, -1) != '/') {
            $destination .= '/';
        }
        if (!is_dir($destination)) {
            @mkdir($destination);
        }
        foreach (scandir($target) as $folder_item) {
            if ($folder_item != '.' && $folder_item != '..') {
                cp_r($target . $folder_item, $destination . $folder_item);
            }
        }
    } else if (is_file($target)) {
        if (!is_dir($destination)) {
            @mkdir($destination);
        }
        if (!is_file($destination)) {
            copy($target, $destination);
        }
    }
}

function debug($input) {
    echo '<pre style="background-color: rgba(0, 0, 0, 0.2);padding: 10px 20px;">';
    var_dump($input);
    echo '</pre>';
}
