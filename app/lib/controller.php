<?php
class Controller{
    protected $logged = false;
    function __construct() {
        global $logged;
        $this->view = new View(); 
        Session::init();
        $logged = Session::get('LoggedIn');
        $this->logged = $logged;
    }
    public function loadModel($name){
        global $config;
        $path = $config['MODEL_PATH'] . $name . '_model.php';
        if(file_exists($path)){
            require $path;
        }
    }
    protected function isAuthenticated(){
        if($this->logged == false){
            Session::destroy();
            Session::unset("LoggedIn");
            header('Location: '  . URL . 'account/login');
            exit;
        }
    }
}