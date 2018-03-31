<?php

$request = @reset(explode('.', Request::$requested_clean_path));
$extensions = array('php', str_replace($request, '', Request::$requested_clean_path));
//
$File_page_trylist = File::_create_try_list($request, $extensions, 'pages/');
$File_page = File::instance_of_first_existing_file($File_page_trylist);
//
if($File_page->exists) {
    App::$content = $File_page->get_content();
} else {
    
}

$File_base = File::i(App::$response_base);
$html = $File_base->get_content();
$html = str_replace('##yield##', App::$content, $html);

Response::deliver($html);