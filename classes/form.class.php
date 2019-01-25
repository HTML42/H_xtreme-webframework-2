<?php

class Form {
    
    public $action = '';
    public $method = 'POST';
    public $enctype = 'application/x-www-form-urlencoded';
    
    private $enctypes = array(
        'normal' => 'application/x-www-form-urlencoded',
        'files' => ''
    );

    /**
     * 
     * @param array $config | keys: method, action, enctype
     */
    public function __construct($config) {
        
    }
    
    public function set_enctype($enctype) {
        $enctype = trim(strtolower($enctype));
        switch($enctype) {
            case 'file':
            case 'files':
                
                break;
        }
    }

}
