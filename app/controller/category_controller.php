<?php
class CategoryController extends Controller {
    private $db_manager;
    function __construct() {
        parent::__construct();
        // loadModel is a function that allows the program to reference the category model class
        parent::loadModel('category');
        /*  db manager use as main brain of the project where data are stored in the database*/
        $this->db_manager = new DbManager(DBHOST,DBUSER,DBPASS,DBNAME);
    }
    public function index() {
        $model = $this->db_manager->getAllShowCategories();
        $page_number = 1;
        $total_item = count($model);
        $page_count = $total_item < 3 ? 1 : ceil($total_item/3);
        if(isset($_GET['page'])){
            $page_number = intVal($_GET['page']);
        }
        $this->view->pagination = [
            'page_number' => $page_number,
            'page_count' => $page_count,
            'total_item' => $total_item
        ];
        $this->view->model = $model;
        $this->view->render('category/index');
    }
    public function list(){
        $this->isAuthenticated();
        $model = $this->db_manager->getAllCategories();
        $this->view->model= $model;
        $this->view->render('category/list');
    }
    public function add(){
        $this->isAuthenticated();
        $model = [
            'id' => 0,
            'title' => '',
            'description' => '',
            'status' => 'SHOW'
        ];
        $this->view->title= "Add new category";
        $this->view->task= "add";
        $this->view->model= $model;
        $this->view->render('category/addedit');
    }
    public function edit($id){
        $this->isAuthenticated();
        $model = $this->db_manager->getCategoryById($id);
        $model = [
            'id' => $model["id"],
            'title' => $model["title"],
            'description' => $model["description"],
            'status'=> $model["status"]
        ];
        $this->view->title= "Modify category";
        $this->view->task= "edit";
        $this->view->model= $model;
        $this->view->render('category/addedit');
    }
    public function delete($id){
        $this->isAuthenticated();
        $deleteCategory =  $this->db_manager->deleteCategory($id);
        if($deleteCategory){
            header('Location: '  . URL . 'category/list');
            exit;
        }
        $result = [
            'IsSuccess' => false,
            'Message' => []
        ];
        $result['Message'][] = "Some error occurs while deleting category";
        $this->view->link = "category/list";
        $this->view->title = "Error in deleting category";
        $this->view->model = $result['Message'];
        $this->view->render('shared/output');
    }
    public function savesubmit(){
        $this->isAuthenticated();
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $task = $_POST['task'];
        if($task=="add"){
            $result = $this->db_manager->addCategory($title,$description,$status);
        }
        else{
            $result = $this->db_manager->updateCategory($id,$title,$description,$status);
        }
        if($result['IsSuccess']){
            header('Location: '  . URL . 'category/list');
            exit;
        }
        $this->view->link = $task == "add" ? "category/add" : "category/edit/" . $id;
        $this->view->title = "Error in " . ($task == "add" ? "creating" : "modifying") . " category";
        $this->view->model = $result['Message'];
        $this->view->render('shared/output');
    }
}