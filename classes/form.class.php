<?php

class Form {

    public $action = '';
    public $method = 'POST';
    public $enctype = 'application/x-www-form-urlencoded';
    public $css_class = 'standard_form';
    public $id = null;
    public $default_row = array(
        'css_class' => 'form_row',
        'label' => array(
            'type' => 'label',
            'main' => '',
            'sub' => null,
            'css_class' => 'form_label',
        ),
        'input' => array(
            'name' => '',
            'type' => 'text',
            'placeholder' => null,
            'wrap_css_class' => 'form_input',
            'css_class' => null,
            'id' => null,
            'value' => '',
            'attr' => array()
        ),
    );
    //
    public $rows = array();

    /**
     * 
     * @param array $config | keys: method, action, enctype
     */
    public function __construct($config = null) {
        if (is_array($config) && !empty($config)) {
            foreach (array('action', 'method', 'enctype') as $key) {
                if ((isset($config[$key]) || is_null($config[$key])) && method_exists($this, 'set_' . $key)) {
                    $this->{'set_' . $key}($config[$key]);
                }
            }
            foreach (array('row', 'rows', 'css_class') as $key) {
                if (isset($config[$key]) && method_exists($this, 'add_' . $key)) {
                    $this->{'add_' . $key}($config[$key]);
                }
            }
            if (isset($config['id'])) {
                $this->id = trim($config['id']);
            }
        }
    }

    public function output() {
        $html = '<form';
        foreach (array('action', 'method', 'enctype') as $key) {
            if (is_string($this->{$key})) {
                $html .= self::_html_attr($key, $this->{$key});
            }
        }
        if (is_string($this->css_class)) {
            $html .= self::_html_attr('class', $this->css_class);
        }
        if ($this->id) {
            $html .= ' id="' . $this->id . '" ';
        }
        $html .= '>' . "\r\n";
        //
        foreach ($this->rows as $row_index => $row) {
            $row = self::_merge($this->default_row, $row);
            $row_html = '<div' . self::_html_attr('class', $row['css_class']);
            if (isset($row['attr']) && is_array($row['attr']) && !empty($row['attr'])) {
                foreach ($row['attr'] as $attr_key => $attr_value) {
                    $row_html .= self::_html_attr($attr_key, $attr_value);
                }
            }
            $row_html .= '>';
            //
            if (isset($row['input']) && isset($row['input']['id'])) {
                $input_id = strval($row['input']['id']);
            } else {
                $input_id = strtoupper(md5(json_encode($row) . '_' . $row_index));
            }
            //
            if (isset($row['input'])) {
                if (is_string($row['input'])) {
                    $row['input'] = $this->default_row['input'] + array('name' => $row['input']);
                }
            } else {
                $row['input'] = $this->default_row['input'];
            }
            $input_data = $row['input'];
            //
            if (isset($row['label'])) {
                if (is_string($row['label'])) {
                    $row['label'] = $this->default_row['label'] + array('main' => $row['label']);
                }
                $label_data = $row['label'];
                $label_tag = $label_data['type'];
                if (isset($input_data['type']) && in_array($input_data['type'], array('checkbox', 'radio')) && $label_tag == 'label') {
                    $label_tag = 'div';
                }
                $row_html .= '<' . $label_tag . self::_html_attr('class', $label_data['css_class']) . ($label_tag == 'label' ? ' for="' . $input_id . '" ' : '') . '">';
                if (isset($label_data['main']) && is_string($label_data['main']) && !empty($label_data['main'])) {
                    $row_html .= '<span class="form_label_main">' . $label_data['main'] . '</span>';
                }
                if (isset($label_data['sub']) && is_string($label_data['sub']) && !empty($label_data['sub'])) {
                    $row_html .= '<span class="form_label_sub">' . $label_data['sub'] . '</span>';
                }
                if (isset($label_data['append']) && is_string($label_data['append']) && !empty($label_data['append'])) {
                    $row_html .= $label_data['append'];
                }
                $row_html .= '</' . $label_tag . '>';
            }
            //
            if (isset($row['input'])) {
                $row_html .= '<div' . self::_html_attr('class', $input_data['wrap_css_class']) . '>';
                $input_attributes = '';
                if (isset($input_data['attr']) && is_array($input_data['attr']) && !empty($input_data['attr'])) {
                    foreach ($input_data['attr'] as $attr_key => $attr_value) {
                        $input_attributes .= self::_html_attr($attr_key, $attr_value);
                    }
                }
                $input_attributes .= self::_html_attr('id', $input_id);
                $input_attributes .= self::_html_attr('name', $input_data['name']);
                $input_attributes .= self::_html_attr('placeholder', $input_data['placeholder']);
                $input_attributes .= self::_html_attr('class', $input_data['css_class']);
                $input_attributes .= self::_html_attr('value', $input_data['value']);

                if ($input_data['type'] == 'select') {
                    $row_html .= '<select' . $input_attributes . '>';
                    if (isset($input_data['options']) && is_array($input_data['options']) && !empty($input_data['options'])) {
                        foreach ($input_data['options'] as $option_key => $option_value) {
                            $value = trim(strval($option_value));
                            $data_value = (is_int($option_key) ? $value : trim(strval($option_key)));
                            $row_html .= '<option' . self::_html_attr('value', $data_value) . '>' . $value . '</option>';
                        }
                    }
                    $row_html .= '</select>';
                } else if ($input_data['type'] == 'textarea') {
                    $row_html .= '<textarea' . $input_attributes . '></textarea>';
                } else if ($input_data['type'] == 'radio') {
                    if (isset($input_data['items']) && is_array($input_data['items'])) {
                        foreach ($input_data['items'] as $value => $label) {
                            $row_html .= '<label class="form_input_item">';
                            $row_html .= '<input' . $input_attributes . ' value="' . $value . '" type="radio">';
                            $row_html .= $label;
                            $row_html .= '</label>';
                        }
                    } else if (isset($input_data['items']) && is_string($input_data['items'])) {
                        $row_html .= $input_data['items'];
                    }
                } else if ($input_data['type'] == 'checkbox') {
                    if (isset($input_data['items']) && is_array($input_data['items'])) {
                        foreach ($input_data['items'] as $value => $label) {
                            $row_html .= '<label class="form_input_item">';
                            $row_html .= '<input' . $input_attributes . ' value="' . $value . '" type="checkbox">';
                            $row_html .= $label;
                            $row_html .= '</label>';
                        }
                    } else if (isset($input_data['items']) && is_string($input_data['items'])) {
                        $row_html .= $input_data['items'];
                    }
                } else {
                    if (isset($input_data['min']) && is_numeric($input_data['min'])) {
                        $input_attributes .= ' min="' . Validate::strict_int($input_data['min']) . '" ';
                    }
                    if (isset($input_data['max']) && is_numeric($input_data['max'])) {
                        $input_attributes .= ' max="' . Validate::strict_int($input_data['max']) . '" ';
                    }
                    if (empty($input_data['value']) && $input_data['type'] == 'range') {
                        $input_attributes .= ' value="0" ';
                    }
                    $row_html .= '<input' . $input_attributes . ' type="' . $input_data['type'] . '">';
                }
                $row_html .= '</div>';
            }
            //
            $row_html .= '</div>' . "\r\n";
            //
            $html .= $row_html;
        }
        //
        $html .= '</form>';
        //
        $html = preg_replace('/\ +/', ' ', $html);
        $html = preg_replace('/\s\>/', '>', $html);
        $html = trim($html);
        //
        return $html;
    }

    //Set Methods

    public function set_action($action) {
        $this->action = is_null($action) ? null : self::_nice_string($action);
    }

    public function set_method($method) {
        $this->method = is_null($method) ? null : strtoupper(self::_nice_string($method));
    }

    public function set_row($row) {
        array_push($this->rows, $row);
    }

    public function add_rows($rows) {
        if (is_array($rows) && !empty($rows)) {
            foreach ($rows as $row) {
                $this->set_row($row);
            }
        }
    }

    public function add_css_class($css_class) {
        $this->css_class .= ' ' . $css_class;
    }

    public function set_enctype($enctype) {
        $enctype = is_null($enctype) ? null : self::_nice_string($enctype);
        switch ($enctype) {
            case 'file':
            case 'files':
                $enctype = 'multipart/form-data';
                break;
        }
        $this->enctype = $enctype;
    }

    public function clear_attributes($keys = null) {
        if (is_array($keys) && !empty($keys)) {
            foreach ($keys as $key) {
                if (isset($this->{$key})) {
                    $this->{$key} = null;
                }
            }
        } else {
            $this->action = null;
            $this->method = null;
            $this->enctype = null;
        }
    }

    //Helper Methods

    public static function plot($config) {
        $_Form = new Form($config);
        return $_Form->output();
    }

    public static function _html_attr($attr, $value) {
        if (is_null($value)) {
            return '';
        } else {
            return ' ' . trim(strval($attr)) . '="' . str_replace('"', '\'', trim(strval($value))) . '" ';
        }
    }

    public static function _nice_string($intput) {
        return trim(strtolower(strval($intput)));
    }

    public static function _merge($default, $data) {
        foreach ($data as $data_key => $data_value) {
            if (isset($default[$data_key]) && is_array($data_value)) {
                $default[$data_key] = self::_merge($default[$data_key], $data_value);
            } else {
                $default[$data_key] = $data_value;
            }
        }
        return $default;
    }

}
