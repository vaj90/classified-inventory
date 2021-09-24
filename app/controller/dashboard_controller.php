<?php
class DashboardController extends Controller {
    function __construct() {
        parent::__construct();
        parent::loadModel('account');
    }
    public function index() {
        $this->isAuthenticated();
        $this->view->render('dashboard/index');
    }
}