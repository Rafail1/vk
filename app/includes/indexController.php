<?php

class indexController extends Controller {
    
    public function prepare($action) {
        
        if(!$action) {
            $action = 'index';
        }
        if (method_exists($this, $action . 'Action')) {
            call_user_func([$this, $action . 'Action']);
        } else {
            $this->tpl = '404.tpl.php';
        }
    }

    

    public function registerAction() {
       
        if(!$this->user->isAuth()) {
            if(strlen(filter_input(INPUT_POST, 'name')) > 3
                    && strlen(filter_input(INPUT_POST, 'login')) > 3
                    && filter_input(INPUT_POST, 'pass') == filter_input(INPUT_POST, 'confirm_pass')
                    && strlen(filter_input(INPUT_POST, 'pass')) > 5) {
                if($this->user->isLoginFree(filter_input(INPUT_POST, 'login'))) {
                    if($user = $this->user->add(filter_input(INPUT_POST, 'name'),
                            filter_input(INPUT_POST, 'login'),
                            filter_input(INPUT_POST, 'confirm_pass'))) {
                        
                        $this->user->setUser($user);
                        $this->tpl = 'register_successfull.tpl.php';
                        return;
                    }
                }
            }
            $this->tpl = 'register.tpl.php';
        }
        
    }
    
    public function authAction() {
        if($this->user->isAuth()) {
            $this->indexAction();
            return;
        }
        if(filter_input(INPUT_POST, 'login') && filter_input(INPUT_POST, 'pass')) {
            
           if($this->user->auth(filter_input(INPUT_POST, 'login'),
                   filter_input(INPUT_POST, 'pass'))) {
                
               $this->indexAction();
               return;
           }
        }
        
        $this->tpl = 'auth.tpl.php';
    }
    
    public function indexAction() {
       
        if($this->user->isAuth()) {
            $vkConfig = Vk\Config::getInstance()->getParam('vk');
            $vkApi = new Vk\Api($vkConfig['client_id'], $vkConfig['sk'], $vkConfig['scope'], $vkConfig['redirect_uri']);
            $project = new Components\Project();
            $this->data['projects'] = $project->getProjects($this->user->getId());
            
            $this->tpl = 'index.tpl.php';
        } else {
            $this->tpl = 'auth.tpl.php';
        }
        
    }

}
