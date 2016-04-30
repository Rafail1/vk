<?php
namespace Vk;

class Config {
    
    static private $instance;
    private $conf;

    private function __construct($filename = '/app/config.php') {
        $this->conf = require(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT').$filename);
    }
    
    private function __clone() {
        
    }
    
    private function __wakeup() {
        
    }
    
    static public function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getParam($paramName) {
        if (!$paramName) {
            return;
        }
        return $this->conf[$paramName];
    }
}