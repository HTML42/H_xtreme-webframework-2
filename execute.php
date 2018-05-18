<?php
//Enable Requirements
ini_set('short_open_tag', 1);
ini_set('magic_quotes_gpc', 1);
ini_set("memory_limit", "512M");
//
if(!getenv('NOGPDR')) {
    include 'library/xgpdr.php';
}
include 'library/bootstrap.php';

include LIB . 'handle_file.php';

exit();