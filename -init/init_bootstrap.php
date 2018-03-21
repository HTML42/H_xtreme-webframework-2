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

include_once APP_DIR . 'xtreme/classes/file.class.php';
include_once APP_DIR . 'xtreme/classes/method_options.class.php';

function debug($var, $height = 'auto', $width = 'auto') {
    $backtrace = debug_backtrace();
    $file = 'Unknown';
    $line = 'Unknown';
    if (count($backtrace) > 1 && isset($backtrace[1]['function']) && in_array($backtrace[1]['function'], array('debug'))) {
        $file = '<span title="Through a debug from: ' . $backtrace[0]['file'] . '">' . $backtrace[1]['file'] . '</span>';
        $line = $backtrace[1]['line'];
    } else if (isset($backtrace[0]['file']) && isset($backtrace[0]['line'])) {
        $file = $backtrace[0]['file'];
        $line = $backtrace[0]['line'];
    }
    echo '<pre style="' .
    'word-break:break-word;border: 1px dashed #BBB;background-color: #CCC;padding:10px;color:#333;' . ($height == 'auto' ? '' : 'overflow-y:scroll;') .
    'height:' . ($height == 'auto' ? 'auto' : $height . 'px') . ';width:' . (strstr($width, '%') || strstr($width, 'px') ? $width : (strstr($width, 'auto') ? 'auto' : $width . 'px')) .
    ';' . ($width != '100%' ? 'margin: 0 auto 10px;' : '') . '">';
    echo '<span style="display: block; margin-bottom: 5px; font-weight: 700;">' . $file . ' (line ' . $line . '):</span>';
    ob_start();
    var_dump($var);
    echo htmlspecialchars(ob_get_clean());
    echo '</pre>';
}
