<?php
class View{
    public $model;
    function __construct() {
    }
    public function render($view) {
        global $config;
        require $config['VIEW_PATH'] . $view . '.phtml';
    }
}