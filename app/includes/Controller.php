<?php

class Controller {
    protected $user;
    protected $tpl;
    protected $data;
    public function __construct() {
        $this->user = Components\User::getInstance();
    }
    public function render() {
        $ctrl = str_replace("Controller", "", get_class($this));
        require 'tpl/'.$ctrl.'/'.$this->tpl;
    }
}

