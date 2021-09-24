<?php
class HomeController extends Controller {
    private $db_manager;
    function __construct() {
        parent::__construct();
    }
    public function index($param) {
        $this->view->render('home/index');
    }
}