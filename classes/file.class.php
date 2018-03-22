<?php

class File {

    public static function cp($source, $destination, $options = '') {
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
                if (!is_file($destination . $item) || $MethodOptions->p('f')) {
                    copy($itempath, $destination . $item);
                }
            } else if ($MethodOptions->p('r') && is_dir($itempath)) {+
                @mkdir(self::n($destination . $item));
                self::cp(self::n($itempath), self::n($destination . $item), $options);
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
        $path = array_slice($path, 0, count($path) - 1);
        $path = implode('/', $path);
        return $path . '/';
    }

    public static function normalize_folder($source) {
        if (is_string($source)) {
            $source = trim($source);
            if (substr($source, -1) != '/') {
                $source .= '/';
            }
        }
        return $source;
    }

    public static function n($p) {
        return self::normalize_folder($p);
    }

}
