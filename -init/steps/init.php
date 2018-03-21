<?php

$config = isset($_GET['config']) && strlen($_GET['config']) > 0 ? $_GET['config'] : 'basic';

echo '<h3>Initialize with Config: ' . $config . '</h3>';

switch ($config) {
    case 'basic':
        File::cp(APP_DIR . 'xtreme/-init/files/', APP_DIR, 'rd');
        new MethodOptions('abc');
        new MethodOptions('abc', array(
            'a' => array('type' => 'json'),
            'b' => array('type' => 'bool')
        ));
        debug('test');
        break;
}