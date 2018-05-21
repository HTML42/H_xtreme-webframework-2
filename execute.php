<?php
//Enable Requirements
ini_set('short_open_tag', 1);
ini_set('magic_quotes_gpc', 1);
ini_set("memory_limit", "512M");
//
if(!getenv('NOGDPR')) {
    include 'library/xgdpr.php';
}
include 'library/bootstrap.php';

include LIB . 'handle_file.php';

exit();