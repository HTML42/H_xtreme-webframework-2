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
        self::$content = $content;

        if (!App::$config['cache']) {
            self::header('Cache-Control:max-age=0;No-Cache, must-revalidate');
            self::header('Pragma:No-Cache, must-revalidate');
            self::header('Expires: ' . date('d M Y H:i:s \G\M\T', time() - DAY));
            self::header('Etag: "' . md5(microtime(true)) . '"');
        } else {
            self::header('Cache-Control:max-age=' . App::$expires);
            self::header('Pragma:Cache');
            self::header('Expires: ' . date('d M Y H:i:s \G\M\T', time() + App::$expires));
            self::header('Etag: "' . self::etag() . '"');
        }

        self::header('Content-length: ' . strlen(self::$content));
        self::header('Content-Type: ' . App::$mime . '; charset=' . App::$encoding, intval(App::$status));
        if (App::$config['cache'] && self::etag_match()) {
            self::header("HTTP/1.1 304 Not Modified");
        } else {
            echo self::$content;
        }
        exit();
    }

    private static function etag() {
        if (!isset(self::$classcache['etag'])) {
            self::$classcache['etag'] = App::$filename . '-' . strlen(self::$content);
            if (is_int(App::$last_change)) {
                self::$classcache['etag'] .= '-' . App::$last_change;
            }
        }
        return self::$classcache['etag'];
    }

    private static function etag_match() {
        return isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == '"' . self::etag() . '"';
    }

}
