<?php

class File {

    public static function cp($source, $destination, $options = '') {
        debug('File::CP');
        $MethodOptions = new MethodOptions($options);
        $mop = $MethodOptions->parameter; // MethodOptions-Parameters (mop)
        $path = self::path($source);
        //
        if (is_dir($source)) {
            $folder = self::ls($source);
        } else if (is_file($source)) {
            $folder = array(self::name($source));
        }
        //
        $destination = self::n($destination);
        //
        foreach ($folder as $item) {
            $itempath = $path . $item;
            if (is_file($itempath)) {
                copy($itempath, $destination . $item);
            } else if ($mop['r'] && is_dir($itempath)) {
                self::cp(self::n($itempath), self::n($destination . $item), $mop);
            }
        }
    }

    public static function ls($source) {
        if (is_dir($source)) {
            $folder = scandir($source);
            $folder = array_filter($folder, function($item) {
                return $item != '.' && $item != '..';
            });
            return $folder;
        } else {
            return array();
        }
    }

    public static function name($source) {
        $name = explode('/', $source);
        $name = end($name);
        return $name;
    }

    public static function path($source) {
        $path = explode('/', $source);
        $path = array_slice($path, -1);
        $path = implode('/', $path);
        return $path;
    }

    public static function normalize($source) {
        if (is_string($source)) {
            $source = trim($source);
            if (is_dir($source)) {
                if (substr($source, -1) != '/') {
                    $source .= '/';
                }
            }
        }
        return $source;
    }
    
    public static function n($p) {
        return self::normalize($p);
    }

}
