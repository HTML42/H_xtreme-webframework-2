<?php
$step = isset($_GET['step']) && strlen($_GET['step']) > 0 ? $_GET['step'] : 'start';

switch($step) {
    case 'start':
        include "steps/start.php";
        break;
    case 'init':
        include "steps/init.php";
        break;
}
?>