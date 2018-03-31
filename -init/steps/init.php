<?php

$config = isset($_GET['config']) && strlen($_GET['config']) > 0 ? $_GET['config'] : 'basic';

echo '<h3>Initialize with Config: ' . $config . '</h3>';

switch ($config) {
    case 'basic':
        File::cp(APP_DIR . 'xtreme/-init/files/', APP_DIR, 'r');
        echo '<a href="../../index.html">Zur Startseite</a>';
        break;
}