<?php

/**
 * Description of response
 *
 * @author Paspirgilis
 */
class Response {

    public static $content;
    private static $classcache = array();

    public static function header($set = null, $status = null) {
        if (is_string($set)) {
            $set = trim($set);
            if (!headers_sent()) {
                if (is_int($status)) {
                    header($set, true, $status);
                } else {
                    header($set);
                }
            }
        }
    }

    public static function deliver($content) {
        $current_output = trim(ob_get_clean());
        if (strlen($current_output) > 0) {
            $content = $current_output . $content;
        }
        //Google PageSpeed:
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights')) {
            if (preg_match('/\<link.*\>/isU', $content, $matches)) {
                $link_matches = implode('', $matches);
                $content = str_replace($matches, '', $content);
                $content = str_replace('</body>', $link_matches, $content);
            }
        }
        if(!isset($GLOBALS['nohtmlmin'])) {
            $content = preg_replace('/\s\s+/', ' ', $content);
            $content = preg_replace('/\>\s+\</', '><', $content);
        }
        //
        self::$content = $content;

        self::header('Content-length: ' . strlen(self::$content));
        self::header('Content-Type: ' . App::$mime . '; charset=' . App::$encoding, intval(App::$status));

        echo self::$content;
    }

}
