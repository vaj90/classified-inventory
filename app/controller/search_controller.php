<?php
class SearchController extends Controller {
    private $db_manager;
    function __construct() {
        parent::__construct();
        $this->db_manager = new DbManager(DBHOST,DBUSER,DBPASS,DBNAME);
    }
    public function index() {
        $model = [];
        $cat_id = "";
        $keyword = "";
        if(isset($_GET['category'])){
            $category =$this->db_manager->getCategoryById(intVal($_GET['category']));
            $cat_id = $category["id"];
        }
        if(isset($_GET['keyword'])){
            $keyword = $_GET['keyword'];
            if(!empty($keyword)){
                $model =$this->db_manager->findItems($keyword,$cat_id);
            }
        }
        $page_number = 1;
        $total_item = count($model);
        $page_count = $total_item < 3 ? 1 : ceil($total_item/3);
        if(isset($_GET['page'])){
            $page_number = intVal($_GET['page']);
        }
        $pagination = [
            'page_number' => $page_number,
            'page_count' => $page_count,
            'total_item' => $total_item
        ];
        $this->view->db_manager=$this->db_manager;
        $this->view->categories = $this->db_manager->getAllShowCategories();
        $this->view->pagination = $pagination;
        $this->view->keyword = $keyword;
        $this->view->model = $model;
        $this->view->render('search/index');
    } 
}