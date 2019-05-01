<?php

class TinyPng {

    public static $api_key = '*ENTER API-KEY HERE*';

    public static function minimize($src) {
        if (is_file($src)) {
            $direct = exec("curl https://api.tinify.com/shrink \
                    --user api:" . self::$api_key . " \
                    --data-binary @" . $src . " \
                    --dump-header /dev/stdout", $output);

            $location = false;
            foreach ($output as $part) {
                if (preg_match('/Location\:\ (.+)/is', $part, $matches) && isset($matches[1])) {
                    $location = trim($matches[1]);
                }
            }

            if (strlen($location) <= 4) {
                #array_push($GLOBALS['response']['bad_locations'], $location);
            }


            return Curl::get($location);
        } else {
            return null;
        }
    }
    
    public static function min($src) {
        if (is_file($src)) {
            $cache_key = 'imagemin_' . sha1($src);
            if(Xcache::get($cache_key, DAY * 30)) {
                return Xcache::get($cache_key, DAY * 30);
            } else {
                $min_image = self::minimize($src);
                Xcache::set_big($cache_key, $min_image);
                return $min_image;
            }
        } else {
            return null;
        }
    }

}