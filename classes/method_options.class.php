<?php

class MethodOptions {

    public $source;
    public $parameter;

    public function __construct($parameter_source, $types = null) {
        $this->source = $parameter_source;
        $parameter_array = MethodOptions::parse($this->source);
        if (is_array($types)) {
            $tmp = [];
            foreach ($parameter_array as $parameter_key => $parameter_value) {
                if (isset($types[$parameter_key])) {
                    if (is_array($types[$parameter_key])) {
                        $type_config = $types[$parameter_key];
                    } else if (is_string($types[$parameter_key])) {
                        $type_config = array('type' => $types[$parameter_key]);
                    } else {
                        $type_config = null;
                    }
                    if (is_array($type_config)) {
                        switch ($type_config['type']) {
                            case 'int':
                            case 'integer':
                                $parameter_array[$parameter_key] = intval($parameter_value);
                                break;
                            case 'str':
                            case 'string':
                                $parameter_array[$parameter_key] = strval($parameter_value);
                                break;
                            case 'bool':
                                $parameter_array[$parameter_key] = boolval($parameter_value);
                                break;
                            case 'json':
                                $parameter_array[$parameter_key] = @json_encode($parameter_value, true);
                                if(!is_array($parameter_array[$parameter_key])) {
                                    $parameter_array[$parameter_key] = array();
                                }
                                break;
                        }
                    }
                }
            }
        }
        $this->parameter = $parameter_array;
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
                    $return[$parameter] = true;
                }
            }
            return $return;
        } else {
            //Simple-Format
            $parameter_array = str_split($parameter_source);
            $parameters = array_flip($parameter_array);
            array_walk($parameters, function(&$item) {
                $item = true;
            });
            return $parameters;
        }
    }
    
    public function p($parameter_key, $value = true) {
        if(isset($this->parameter['r']) && $this->parameter['r'] == $value) {
            return true;
        } else {
            return false;
        }
    }

}
