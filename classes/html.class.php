<?php

class Html {

    /**
     * Each item can be a string, representing the content.
     * Or an array with 2 items containing: 1.: LI-Content ; 2.: LI-Attributes
     * @param array $items
     * @param array $attr
     */
    public static function ul($items = array(), $attr = array()) {
        $attr = $attr + array(
            'data-length' => count($items)
        );
        //UL-Tag
        $html = '<ul' . self::_attributes($attr) . '>';
        //LI-Tags
        foreach ($items as $item_index => $item) {
            $li_content = '';
            $li_class = 'item_' . $item_index . ' ' . ($item_index % 2 == 0 ? 'even' : 'odd');
            if ($item_index == 0)
                $li_class .= ' first_item';
            if ($item_index == count($items) - 1)
                $li_class .= ' last_item';
            $li_attr = array();
            //
            if (is_string($item)) {
                $li_content = trim($item);
            } else if (is_array($item) &&
                    isset($item[0]) && isset($item[1]) &&
                    is_string($item[0]) && is_array($item[1])) {
                $li_content = trim($item[0]);
                $li_attr += $item[1];
            }
            //
            if (isset($li_attr['class'])) {
                $li_attr['class'] .= ' ' . $li_class;
            } else {
                $li_attr['class'] = $li_class;
            }
            //
            $html .= '<li' . self::_attributes($li_attr) . '>';
            //
            $html .= $li_content;
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public static function link($href, $content, $attr = array()) {
        $attr = $attr + array(
            'href' => trim($href)
        );
        if (Request::$requested_clean_path == $attr['href']) {
            $attr['class'] = (isset($attr['class']) ? $attr['class'] . ' active' : 'active');
        }
        $html = '<a' . self::_attributes($attr) . '>';
        $html .= $content;
        $html .= '</a>';
        return $html;
    }

    public static function _attributes($attr) {
        $html = '';
        foreach ($attr as $key => $value) {
            $html .= ' ' . $key . '="' . str_replace('"', '\\"', $value) . '" ';
        }
        return $html;
    }

}
