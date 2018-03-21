<?php

class MethodOptions {

    public $source;
    public $parameter;

    public function __construct($parameter_source, $types = null) {
        $this->source = $parameter_source;
        $parameter_array = MethodOptions::parse($this->source);
        debug($parameter_array);
        if (is_array($types)) {
            $tmp = [];
            foreach ($parameter_array as $parameter) {
                
            }
        }
    }

    public static function parse($parameter_source = null) {
        if (!is_string($parameter_source)) {
            $parameter_source = '';
        }
        $parameter_source = preg_replace('/\s+/', '', $parameter_source);
        //
        if (strstr($parameter_source, ';')) {
            $parameter_array = explode(';', $parameter_source);
            $return = [];
            foreach ($parameter_array as $parameter_key => &$parameter) {
                if (strstr($parameter, '=')) {
                    $parameter_split = explode('=', $parameter);
                    $return[$parameter_split[0]] = trim($parameter_split[1]);
                } else {
                    $return[$parameter_split[0]] = true;
                }
            }
            return $return;
        } else {
            //Simple-Format
            $parameter_array = str_split($parameter_source);
            return array_flip($parameter_array);
        }
    }

}
