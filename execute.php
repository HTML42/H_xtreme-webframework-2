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
//

include 'library/bootstrap.php';

include LIB . 'handle_file.php';

exit();