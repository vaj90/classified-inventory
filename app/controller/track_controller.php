<?php
class TrackController extends Controller {
    private $db_manager;
    function __construct() {
        parent::__construct();
        parent::loadModel('account');
        $this->db_manager = new DbManager(DBHOST,DBUSER,DBPASS,DBNAME);
    }
    public function index() {
        $this->isAuthenticated();
        $model = $this->db_manager->getAllTrack();
        $items = $this->db_manager->getAllItems();
        $itemKeyValue = [];
        foreach($items as $key=>$value){
            $itemKeyValue[$value["id"]] = $value["title"];
        }
        $this->view->items = $itemKeyValue;
        $this->view->model = $model;
        $this->view->render('track/index');
    }   
}