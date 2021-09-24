<?php
// class that manipulate the route and referencing of class needed to run
class Bootstrap {
    function __construct() {
        global $config;
        global $ctrlr;
        //global $rq_method;
        /* $url is a variable that holds the current url*/ 
        $url = $_SERVER['REQUEST_URI'];
        $url = substr($url,1);
        $pos_uri = strpos($url,'?') ;
        if($pos_uri> 0){
            $url = substr($url,0,$pos_uri);
        }

        $url = rtrim($url,'/');
        $url = explode('/', $url);

        //$rq_method = $_SERVER['REQUEST_METHOD'];
        //$query_string = $_SERVER['QUERY_STRING'];
        /*echo '<pre>';
        print_r($_SERVER);
        print_r($url);
        echo '</pre>';*/
        
        $controller = "home";
        $action="index";
        $param ="";
        if(isset($url[CONTROLLER_INDEX]) && !empty($url[CONTROLLER_INDEX])) {
            $controller = $url[CONTROLLER_INDEX];
            if($controller=="items"){
                $controller = "item";
            }
            if($controller=="categories"){
                $controller = "category";
            }
        }
    
        $cpath = $config['CONTROLLER_PATH'] . $controller . '_controller';
        $file = $cpath . '.php';
        //echo $file;
        if(!file_exists($file)){
            $controller = 'apperror';
            $action = 'notfound';
            $cpath = $config['CONTROLLER_PATH'] . $controller . '_controller';
            $file = $cpath . '.php';
        }
        //echo $file;
        require $file;
        $ctrlr = $controller;
        $controller = $controller . "Controller";
        $class = new $controller();

        if(isset($url[PARAMETER_INDEX])){
            $action = $url[METHOD_INDEX];
            $param = $url[PARAMETER_INDEX];
        }
        else{
            if(isset($url[METHOD_INDEX]) && !empty($url[METHOD_INDEX])){
                $action = $url[METHOD_INDEX];
            }
        }

        //Check method and controller if exists
        $method_checker = method_exists($controller,$action);
        if($method_checker){
            $class->{$action}($param);
        }
        else{
            header('Location: '  . URL . 'error');
            exit;
        }
    }
}